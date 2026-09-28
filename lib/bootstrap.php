<?php
/**
 * Shared bootstrap for every page: config, database connection, session and security helpers.
 * Included through connection/db.php and Admin/connection/db.php, so existing includes keep working.
 */

if (defined('JOBHUNT_BOOTSTRAPPED')) {
    return;
}
define('JOBHUNT_BOOTSTRAPPED', true);
define('APP_ROOT', dirname(__DIR__));

// Log errors, never print them to visitors.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// ---------- session ----------
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

// ---------- database ----------
// Configure with environment variables; the defaults match a stock XAMPP install.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli(
    getenv('DB_HOST') ?: 'localhost',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: '',
    getenv('DB_NAME') ?: 'job_portal',
    (int) (getenv('DB_PORT') ?: 3306)
);
$conn->set_charset('utf8mb4');

/**
 * Run a prepared statement. Values are always bound, never concatenated into SQL.
 * Returns the mysqli_result for queries that produce rows, otherwise true.
 */
function db(string $sql, array $params = [])
{
    global $conn;
    $stmt = $conn->prepare($sql);
    if ($params) {
        $values = array_map(static fn($v) => $v === null ? null : (string) $v, array_values($params));
        $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result === false ? true : $result;
}

/** Escape a value for HTML output (text and attributes). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

// ---------- CSRF ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Valid token in POST (forms) or GET (action links such as delete). */
function csrf_ok(): bool
{
    $sent = $_POST['csrf'] ?? $_GET['csrf'] ?? '';
    return is_string($sent) && $sent !== '' && hash_equals($_SESSION['csrf'] ?? '', $sent);
}

function require_csrf(): void
{
    if (!csrf_ok()) {
        http_response_code(403);
        exit('Invalid or expired form. Please go back, reload the page and try again.');
    }
}

// ---------- passwords ----------
/**
 * Verify a password against a stored hash. Accounts created before hashing was added
 * still hold plaintext: accept those once and let the caller upgrade them to a hash.
 */
function password_matches(string $password, string $stored, ?bool &$needsRehash = null): bool
{
    $needsRehash = false;
    if (password_get_info($stored)['algo'] !== null) {
        $ok = password_verify($password, $stored);
        $needsRehash = $ok && password_needs_rehash($stored, PASSWORD_DEFAULT);
        return $ok;
    }
    $ok = hash_equals($stored, $password);
    $needsRehash = $ok;
    return $ok;
}

// ---------- authentication ----------
// Job seekers and admins use separate session keys, so one can never pass as the other.
function login_admin(array $admin): void
{
    session_regenerate_id(true);
    unset($_SESSION['user_email']);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_email'] = $admin['admin_email'];
    $_SESSION['admin_type'] = (string) $admin['admin_type'];
}

function login_user(string $email): void
{
    session_regenerate_id(true);
    unset($_SESSION['admin_id'], $_SESSION['admin_email'], $_SESSION['admin_type']);
    $_SESSION['user_email'] = $email;
}

function current_admin_email(): ?string
{
    return $_SESSION['admin_email'] ?? null;
}

function is_super_admin(): bool
{
    return ($_SESSION['admin_type'] ?? '') === '1';
}

function current_user_email(): ?string
{
    return $_SESSION['user_email'] ?? null;
}

/** Admin pages: must be logged in as an admin; $superOnly restricts to type 1. */
function require_admin(bool $superOnly = false): void
{
    if (current_admin_email() === null) {
        redirect('admin_login.php');
    }
    if ($superOnly && !is_super_admin()) {
        http_response_code(403);
        exit('This page is for super admins only.');
    }
}

function require_user(): void
{
    if (current_user_email() === null) {
        redirect('job-post.php');
    }
}

// ---------- job applications ----------
const APPLICATION_SQL = "SELECT job_apply.*, all_jobs.job_title, all_jobs.des, all_jobs.customer_email
    FROM job_apply JOIN all_jobs ON job_apply.id_job = all_jobs.job_id";

/** Applications the signed-in admin may see: recruiters get their own jobs' applicants, super admins all. */
function visible_applications(): mysqli_result
{
    return is_super_admin()
        ? db(APPLICATION_SQL . " ORDER BY job_apply.id DESC")
        : db(APPLICATION_SQL . " WHERE all_jobs.customer_email = ? ORDER BY job_apply.id DESC", [current_admin_email()]);
}

/** One application, or null if it doesn't exist or belongs to another recruiter (same answer). */
function find_application(int $id): ?array
{
    $row = is_super_admin()
        ? db(APPLICATION_SQL . " WHERE job_apply.id = ?", [$id])->fetch_assoc()
        : db(APPLICATION_SQL . " WHERE job_apply.id = ? AND all_jobs.customer_email = ?", [$id, current_admin_email()])->fetch_assoc();
    return $row ?: null;
}

// ---------- uploads ----------
/**
 * Validate and store an uploaded file. Checks size, extension AND the real MIME type
 * (from the file's content), and stores it under a random name so it can't overwrite
 * files or be executed as code. Returns the stored file name, or null with $error set.
 */
function store_upload(array $file, string $dir, array $allowed, int $maxBytes, ?string &$error = null): ?string
{
    $error = null;
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        $error = 'Please choose a file to upload.';
        return null;
    }
    if ($file['size'] > $maxBytes) {
        $error = 'File is too large (max ' . round($maxBytes / 1048576) . ' MB).';
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$ext]) || !in_array($mime, (array) $allowed[$ext], true)) {
        $error = 'That file type is not allowed.';
        return null;
    }
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $name = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $name)) {
        $error = 'Upload failed. Please try again.';
        return null;
    }
    return $name;
}

const RESUME_TYPES = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
];

const IMAGE_TYPES = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
];
