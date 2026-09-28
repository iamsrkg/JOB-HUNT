#!/usr/bin/env bash
# End-to-end tests for JOB-HUNT against a live local server + database.
# Usage: import job_portal.sql into a fresh DB, start the app (php -S 127.0.0.1:8000 router.php), then:
#   B=http://127.0.0.1:8000 DB_PORT=3306 bash tests/e2e.sh
# Needs curl and the mysql/mariadb client. It resets nothing: run it against a freshly imported database.
# Against the Docker image (as CI does): MYSQL="docker exec -i jobhunt mariadb" DB_HOST=localhost B=http://127.0.0.1:8080 bash tests/e2e.sh
B=${B:-http://127.0.0.1:8000}
MYSQL=${MYSQL:-mysql}      # may be a full command, e.g. "docker exec -i jobhunt mariadb"
APP_DIR=${APP_DIR:-$(cd "$(dirname "$0")/.." && pwd)}
LOG=${LOG:-}   # optional: path to the PHP server log, to assert no PHP warnings were logged
T=$(mktemp -d); command -v cygpath >/dev/null && T=$(cygpath -m "$T")
pass=0; fail=0
ok()   { pass=$((pass+1)); echo "  PASS  $1"; }
bad()  { fail=$((fail+1)); echo "  FAIL  $1"; }
check(){ if eval "$2"; then ok "$1"; else bad "$1"; fi; }
sql()  { $MYSQL -h"${DB_HOST:-127.0.0.1}" -P"${DB_PORT:-3306}" -u"${DB_USER:-root}" ${DB_PASS:+-p"$DB_PASS"} "${DB_NAME:-job_portal}" -N -e "$1" 2>/dev/null; }
csrf() { curl -s -b "$1" -c "$1" "$B/$2" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | sed 's/.*value="//;s/"//'; }
status(){ curl -s -o /dev/null -w '%{http_code}' "$@"; }
location(){ curl -s -o /dev/null -D - "$@" | tr -d '\r' | awk 'tolower($1)=="location:"{print $2}'; }
start_line=0; [ -n "$LOG" ] && start_line=$(wc -l < "$LOG")

echo "== pages render without PHP errors"
for p in index.php blog.php about.php contact.php sign_up.php job-post.php new-post.php Admin/admin_login.php; do
  body=$(curl -s "$B/$p"); code=$(status "$B/$p")
  check "$p -> 200, no PHP error" '[ "$code" = 200 ] && ! echo "$body" | grep -qE "Fatal error|Warning:|Notice:|Deprecated:"'
done

echo "== search & pagination"
body=$(curl -s -X POST "$B/index.php" --data "key=Java&category=&search=1")
check "keyword search finds the Java job" 'echo "$body" | grep -q "Backend Engineer (Java / Spring Boot)"'
check "keyword search excludes others" '! echo "$body" | grep -q "Data Engineer"'
body=$(curl -s -X POST "$B/index.php" --data "key=&category=4&search=1")
check "category filter returns only that category" 'echo "$body" | grep -q "Data Engineer" && ! echo "$body" | grep -q "QA Automation"'
body=$(curl -s "$B/index.php")
check "home lists 3 jobs + a page-2 link for 6 jobs" '[ $(echo "$body" | grep -c "Apply Job") = 3 ] && echo "$body" | grep -q "page=2"'
body=$(curl -s "$B/index.php?page=2&keyword=%3Cscript%3Ealert(1)%3C%2Fscript%3E&category=")
check "search input is HTML-escaped (no reflected XSS)" '! echo "$body" | grep -q "<script>alert(1)"'

echo "== admin login & SQL injection"
J=$T/anon; code=$(status -X POST -b $J -c $J "$B/Admin/admin_login.php" --data "email=admin@jobhunt.test&pass=Admin@12345&submit=1")
check "login without CSRF token is refused (403)" '[ "$code" = 403 ]'
J=$T/sqli; tok=$(csrf $J Admin/admin_login.php)
loc=$(location -b $J -c $J -X POST "$B/Admin/admin_login.php" --data-urlencode "email=' OR '1'='1' -- " --data "pass=x&submit=1&csrf=$tok")
check "SQL-injection login bypass fails" '[ -z "$loc" ]'
loc=$(location -b $J "$B/Admin/admin_dashboard.php")
check "...and the dashboard stays locked" '[ "$loc" = "admin_login.php" ]'
SA=$T/super; tok=$(csrf $SA Admin/admin_login.php)
loc=$(location -b $SA -c $SA -X POST "$B/Admin/admin_login.php" --data "email=admin@jobhunt.test&pass=Admin@12345&submit=1&csrf=$tok")
check "super admin logs in" '[ "$loc" = "admin_dashboard.php" ]'
check "super admin can open Customers" '[ $(status -b $SA "$B/Admin/Customers.php") = 200 ]'
body=$(curl -s -b $SA "$B/Admin/Customers.php")
check "customer list never shows password hashes" '! echo "$body" | grep -q "2y\$10"'

echo "== access control"
loc=$(location "$B/Admin/job_delete.php?del=1")
check "anonymous delete is redirected to login" '[ "$loc" = "admin_login.php" ] && [ "$(sql "SELECT COUNT(*) FROM all_jobs WHERE job_id=1")" = 1 ]'
JS=$T/seeker; tok=$(csrf $JS job-post.php)
loc=$(location -b $JS -c $JS -X POST "$B/job-post.php" --data "email=jane.doe@example.com&Password=Seeker@12345&submit=1&csrf=$tok")
check "job seeker logs in" '[ "$loc" = "index.php" ]'
loc=$(location -b $JS "$B/Admin/admin_dashboard.php")
check "job seeker session cannot enter the admin area" '[ "$loc" = "admin_login.php" ]'
RC=$T/recruiter; tok=$(csrf $RC new-post.php)
loc=$(location -b $RC -c $RC -X POST "$B/new-post.php" --data "email=talent@acmecloud.test&Password=Recruit@12345&submit=1&csrf=$tok")
check "recruiter logs in" '[ "$loc" = "Admin/admin_dashboard.php" ]'
check "recruiter blocked from super-admin pages (403)" '[ $(status -b $RC "$B/Admin/Customers.php") = 403 ] && [ $(status -b $RC "$B/Admin/create_company.php") = 403 ]'
check "recruiter cannot edit another company's job (404)" '[ $(status -b $RC "$B/Admin/job_edit.php?edit=4") = 404 ]'
check "recruiter can edit own job" '[ $(status -b $RC "$B/Admin/job_edit.php?edit=1") = 200 ]'
check "recruiter cannot view another company's applicant (404)" '[ $(status -b $RC "$B/Admin/view_applied_jobs.php?id=2") = 404 ]'
check "recruiter can view own applicant" '[ $(status -b $RC "$B/Admin/view_applied_jobs.php?id=1") = 200 ]'
tok=$(csrf $RC Admin/job_create.php)
curl -s -o /dev/null -b $RC "$B/Admin/job_delete.php?del=4&csrf=$tok"
check "recruiter cannot delete another company's job" '[ "$(sql "SELECT COUNT(*) FROM all_jobs WHERE job_id=4")" = 1 ]'
curl -s -o /dev/null -b $RC "$B/Admin/job_delete.php?del=3"
check "delete without CSRF token is refused" '[ "$(sql "SELECT COUNT(*) FROM all_jobs WHERE job_id=3")" = 1 ]'

echo "== uploads"
printf '<?php system($_GET["c"]); ?>' > $T/evil.pdf
printf '%%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%%%EOF\n' > $T/cv.pdf
tok=$(csrf $JS "blog-single.php?id=2")
body=$(curl -s -b $JS -c $JS -X POST "$B/apply_job.php" -F csrf=$tok -F id_job=2 -F first_name=Jane -F last_name=Doe -F email=jane.doe@example.com -F phone=0812345678 -F dob=1996-04-12 -F "file=@$T/evil.pdf;type=application/pdf" -F submit=submit)
check "PHP disguised as .pdf is rejected" 'echo "$body" | grep -q "not allowed" && [ "$(sql "SELECT COUNT(*) FROM job_apply WHERE id_job=2")" = 0 ]'
tok=$(csrf $JS "blog-single.php?id=2")
body=$(curl -s -b $JS -c $JS -X POST "$B/apply_job.php" -F csrf=$tok -F id_job=2 -F first_name=Jane -F last_name=Doe -F email=jane.doe@example.com -F phone=0812345678 -F dob=1996-04-12 -F "file=@$T/cv.pdf;type=application/pdf" -F submit=submit)
stored=$(sql "SELECT file FROM job_apply WHERE id_job=2 AND email='jane.doe@example.com'")
check "a real PDF is accepted and stored under a random name" 'echo "$body" | grep -q "submitted" && echo "$stored" | grep -qE "^[a-f0-9]{32}\.pdf$"'
check "schema dump is not downloadable (403)" '[ $(status "$B/job_portal.sql") = 403 ]'
check "resumes are not directly reachable (/files -> 403)" '[ $(status "$B/files/$stored") = 403 ]'
appid=$(sql "SELECT id FROM job_apply WHERE id_job=2 AND email='jane.doe@example.com'")
ct=$(curl -s -o /dev/null -w '%{content_type}' -b $RC "$B/Admin/download_resume.php?id=$appid")
check "owning recruiter downloads it via the protected endpoint" '[ "$ct" = "application/pdf" ]'
check "anonymous download is redirected to login" '[ "$(location "$B/Admin/download_resume.php?id=$appid")" = "admin_login.php" ]'
tok=$(csrf $JS "blog-single.php?id=2")
body=$(curl -s -b $JS -c $JS -X POST "$B/apply_job.php" -F csrf=$tok -F id_job=2 -F first_name=Jane -F last_name=Doe -F email=jane.doe@example.com -F phone=0812345678 -F dob=1996-04-12 -F "file=@$T/cv.pdf;type=application/pdf" -F submit=submit)
check "duplicate application is refused" 'echo "$body" | grep -q "already applied"'
cp $APP_DIR/profile_img/avtaar.png "$T/me.png"
tok=$(csrf $JS myprofile.php)
curl -s -o /dev/null -b $JS -c $JS -X POST "$B/profile_add.php" -F csrf=$tok -F name="Jane Q. Doe" -F dob=1996-04-12 -F number=0812345678 -F email=jane.doe@example.com -F "img=@$T/me.png;type=image/png"
img=$(sql "SELECT img FROM profile WHERE user_email='jane.doe@example.com'")
check "profile photo saved with a random name and served" 'echo "$img" | grep -qE "^[a-f0-9]{32}[.]png$" && [ $(status "$B/profile_img/$img") = 200 ]'
body=$(curl -s -b $JS "$B/myprofile.php")
check "profile page renders one nav bar and the saved name" '[ $(echo "$body" | grep -c "id=\"ftco-navbar\"") = 1 ] && echo "$body" | grep -q "Jane Q. Doe"'

echo "== application workflow"
tok=$(csrf $RC "Admin/view_applied_jobs.php?id=$appid")
body=$(curl -s -b $RC -X POST "$B/Admin/mailer.php" --data "id=$appid&subject=Hi&body=Hello&csrf=$tok")
check "accept without SMTP configured leaves the application unchanged" '[ "$(sql "SELECT status FROM job_apply WHERE id=$appid")" = new ]'
check "...and says why" 'echo "$body" | grep -q "configured"'
curl -s -o /dev/null -b $RC -X POST "$B/Admin/reject_job.php" --data "id=$appid&csrf=$tok"
check "reject keeps the record with status rejected" '[ "$(sql "SELECT status FROM job_apply WHERE id=$appid")" = rejected ]'

echo "== legacy plaintext password is upgraded on login"
sql "INSERT INTO jobseeker (email,password,first_name,last_name,dob,mobile_number) VALUES ('legacy@example.com','oldpass1','Old','User','1990-01-01','0000000009')"
LG=$T/legacy; tok=$(csrf $LG job-post.php)
loc=$(location -b $LG -c $LG -X POST "$B/job-post.php" --data "email=legacy@example.com&Password=oldpass1&submit=1&csrf=$tok")
hash=$(sql "SELECT password FROM jobseeker WHERE email='legacy@example.com'")
check "legacy account logs in and is re-hashed" '[ "$loc" = "index.php" ] && [ "${hash:0:4}" = "\$2y\$" ]'

echo "== server log"
new_errors=0; [ -n "$LOG" ] && new_errors=$(tail -n +$((start_line+1)) "$LOG" | grep -cE "PHP (Fatal|Warning|Deprecated|Notice)")
check "no PHP warnings/errors logged during the run" '[ "$new_errors" = 0 ]'
[ "$new_errors" != 0 ] && tail -n +$((start_line+1)) "$LOG" | grep -E "PHP (Fatal|Warning|Deprecated|Notice)" | head -5

echo; echo "RESULT: $pass passed, $fail failed"
[ "$fail" = 0 ]
