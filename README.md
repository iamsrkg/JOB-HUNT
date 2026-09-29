# JOB-HUNT: job portal (PHP + MySQL), security-hardened

A job board with three roles:
- **Job seekers** sign up, search jobs, apply with a resume and manage a profile.
- **Company recruiters** post jobs and review the applications to *their* jobs.
- **Super admins** manage recruiter accounts, companies and job categories.

This started as a 2020 college project. In 2026 I revisited it as a **security-hardening exercise**. The original had the classic web vulnerabilities, and fixing them properly (then proving it with tests) is a better showcase than the original features.

## What was fixed

| Problem in the original | Fix |
|---|---|
| **SQL injection** in all 45 queries. `' OR '1'='1' --` logged anyone into the admin panel. | Every query is a prepared statement (`db()` helper). The only values placed in SQL text directly are integer-cast numbers. |
| **Plain-text passwords** in the database | `password_hash` / `password_verify` (bcrypt). Legacy plain-text accounts are upgraded to a hash on their next login. |
| **Remote code execution via upload.** The uploaded file name was `email + "." + original name`, so a `.php` "resume" could be executed. | Size limit, extension whitelist **and** content-sniffed MIME type (`finfo`), a random stored name, and no script execution in upload folders |
| **Resumes were publicly downloadable** | `files/` is denied by the web server. Resumes stream through `Admin/download_resume.php`, which checks ownership. |
| **Admin action endpoints had no login check.** Anyone could delete companies or users via URL. | Every admin page and action calls `require_admin()`, with super-admin-only pages enforced server-side |
| **Broken access control.** Job seekers and admins shared `$_SESSION['email']`, so a job seeker could open `/Admin`. | Separate session identities, and `session_regenerate_id` on login |
| **Horizontal access.** One recruiter could view, edit or delete another company's jobs and applicants. | Ownership is enforced in the SQL itself (the same "not found" answer for missing and foreign records) |
| **No CSRF protection** on any form or delete link | Per-session CSRF tokens on every form and state-changing link |
| **Stored and reflected XSS** everywhere | All output is escaped with `e()` (`htmlspecialchars`) |
| **SMTP password committed to the repo.** The "accept" email went to a hard-coded test inbox. | SMTP settings come from environment variables. Mail goes to the applicant as plain text, with the recruiter as reply-to. |
| **Outdated PHPMailer 6.1.5** with known advisories | PHPMailer 7.1.1 |
| **Third-party script** (`geodata.solutions`) loaded into admin pages | Removed. Location is plain input. |
| **Bugs:** the site crashed on a fresh clone (missing DB connection file), resumes were stored in a `DATE` column, the "edit account" form silently promoted recruiters to super admin, pagination dropped the last page, job edits never saved, and the profile page rendered twice | All fixed |
| **Real people's data** in the seed SQL | Replaced with fictional demo data |

## Proof: end-to-end tests
`tests/e2e.sh` drives the running app over HTTP and checks the database. It has 43 checks, covering:
- the SQL-injection bypass failing
- CSRF rejection
- a job seeker being locked out of the admin area
- a recruiter getting 403/404 on another company's data
- a PHP file disguised as a PDF being rejected
- resumes being unreachable directly
- XSS escaping, duplicate-application blocking
- the reject workflow
- legacy password upgrades

```bash
B=http://127.0.0.1:8000 DB_PORT=3306 bash tests/e2e.sh     # RESULT: 43 passed, 0 failed
```

## Try it in your browser

[![Open in GitHub Codespaces](https://github.com/codespaces/badge.svg)](https://codespaces.new/iamsrkg/JOB-HUNT?quickstart=1)

One click builds the same Docker image CI tests (Apache + PHP 8.3 + its own MariaDB with demo data) in your own GitHub account. After about 2 minutes, the portal opens in a new tab. Log in with one of the demo accounts below.

**With Docker:** `docker build -t job-hunt . && docker run -e PORT=8080 -p 8080:8080 job-hunt`, then open `http://localhost:8080`.

## Run it locally

**XAMPP / any Apache + PHP 8.1+ + MySQL/MariaDB:** put the folder in `htdocs`, create a `job_portal` database, import `job_portal.sql`, and open `http://localhost/JOB-HUNT/`.

**PHP's built-in server:**
```bash
mysql -u root -e "CREATE DATABASE job_portal" && mysql -u root job_portal < job_portal.sql
php -S 127.0.0.1:8000 router.php        # router.php mirrors the .htaccess deny rules
```

Configuration comes from environment variables. The defaults match XAMPP.

| Variable | Default | Purpose |
|---|---|---|
| `DB_HOST` `DB_PORT` `DB_USER` `DB_PASS` `DB_NAME` | `localhost` `3306` `root` *(empty)* `job_portal` | Database |
| `SMTP_HOST` `SMTP_PORT` `SMTP_USER` `SMTP_PASS` | *(unset)* `587` | Outgoing mail for "Accept". Without them, Accept explains that email isn't configured. |
| `SMTP_SECURE` | `tls` | `tls`, `ssl` or `none` (local mail catchers only) |
| `SMTP_FROM` `SMTP_FROM_NAME` | `SMTP_USER`, `JobHunt` | Sender |

### Demo accounts (fictional)
| Role | Sign-in page | Email | Password |
|---|---|---|---|
| Super admin | `/Admin/admin_login.php` | admin@jobhunt.test | `Admin@12345` |
| Recruiter | `/new-post.php` | talent@acmecloud.test | `Recruit@12345` |
| Job seeker | `/job-post.php` | jane.doe@example.com | `Seeker@12345` |

## Structure
```
lib/bootstrap.php        DB connection, prepared-statement helper, sessions, CSRF, auth guards, safe uploads
connection/db.php        entry points that load the bootstrap (public side / Admin side)
index.php, blog-single.php, apply_job.php, sign_up.php, job-post.php, myprofile.php, profile_add.php
Admin/                   dashboard, jobs, companies, categories, accounts, applications, download_resume.php
tests/e2e.sh             end-to-end HTTP + database tests
job_portal.sql           schema + fictional demo data
```

## Stack
PHP 8 (mysqli, prepared statements) · MySQL/MariaDB · Bootstrap 4 · jQuery · PHPMailer 7
