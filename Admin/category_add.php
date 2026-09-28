<?php
// AJAX handler: add a job category. Super admins only.
require_once __DIR__ . '/connection/db.php';
require_admin(true);
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}
require_csrf();

$category = trim((string) ($_POST['category'] ?? ''));
$description = trim((string) ($_POST['Description'] ?? ''));

if ($category === '' || mb_strlen($category) > 100) exit('Enter a category name (up to 100 characters).');
if (mb_strlen($description) > 100) exit('Description is too long (up to 100 characters).');
if (db("SELECT 1 FROM job_category WHERE category = ?", [$category])->fetch_row()) exit('That category already exists.');

db("INSERT INTO job_category (category, des) VALUES (?, ?)", [$category, $description]);
echo 'Category added.';
