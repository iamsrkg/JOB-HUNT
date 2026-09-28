<?php
// AJAX handler: create a job-provider (admin) account. Super admins only.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}
require_csrf();

$email = trim((string) ($_POST['email'] ?? ''));
$username = trim((string) ($_POST['Username'] ?? ''));
$password = (string) ($_POST['Password'] ?? '');
$firstName = trim((string) ($_POST['first_name'] ?? ''));
$lastName = trim((string) ($_POST['last_name'] ?? ''));
$adminType = (string) ($_POST['admin_type'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) exit('Enter a valid email address.');
if ($username === '' || $firstName === '' || $lastName === '') exit('Username, first name and last name are required.');
if (strlen($password) < 8) exit('Password must be at least 8 characters.');
if (!in_array($adminType, ['1', '2'], true)) exit('Choose an account type.');
if (db("SELECT 1 FROM admin_login WHERE admin_email = ?", [$email])->fetch_row()) exit('An account with this email already exists.');

db("INSERT INTO admin_login (admin_email, admin_pass, admin_username, first_name, last_name, admin_type) VALUES (?, ?, ?, ?, ?, ?)",
   [$email, password_hash($password, PASSWORD_DEFAULT), $username, $firstName, $lastName, $adminType]);
echo 'Job provider added.';
