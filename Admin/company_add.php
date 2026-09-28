<?php
// AJAX handler: add a company and link it to a company-admin account. Super admins only.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}
require_csrf();

$company = trim((string) ($_POST['Company'] ?? ''));
$description = trim((string) ($_POST['Description'] ?? ''));
$admin = trim((string) ($_POST['admin'] ?? ''));

if ($company === '' || mb_strlen($company) > 100) exit('Enter a company name (up to 100 characters).');
if (mb_strlen($description) > 1000) exit('Description is too long.');
if (!db("SELECT 1 FROM admin_login WHERE admin_email = ? AND admin_type = '2'", [$admin])->fetch_row()) exit('Choose a valid company admin.');

db("INSERT INTO company (company_name, des, admin) VALUES (?, ?, ?)", [$company, $description, $admin]);
echo 'Company added.';
