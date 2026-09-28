<?php
// AJAX handler: post a job owned by the signed-in admin/recruiter.
require_once __DIR__ . '/connection/db.php';
require_admin();
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}
require_csrf();

$title = trim((string) ($_POST['job_title'] ?? ''));
$description = trim((string) ($_POST['Description'] ?? ''));
$country = trim((string) ($_POST['country'] ?? ''));
$state = trim((string) ($_POST['state'] ?? ''));
$city = trim((string) ($_POST['city'] ?? ''));
$category = (string) ($_POST['category'] ?? '');
$keyword = trim((string) ($_POST['Keyword'] ?? ''));

if ($title === '' || mb_strlen($title) > 150) exit('Enter a job title (up to 150 characters).');
if ($description === '' || mb_strlen($description) > 5000) exit('Enter a description (up to 5000 characters).');
if ($country === '' || mb_strlen($country) > 100 || mb_strlen($state) > 100 || mb_strlen($city) > 100) exit('Choose a location.');
if (mb_strlen($keyword) > 100) exit('Keyword is too long.');
if (!db("SELECT 1 FROM job_category WHERE id = ?", [$category])->fetch_row()) exit('Choose a category.');

// The owner comes from the session, never from the form.
db("INSERT INTO all_jobs (customer_email, job_title, des, country, state, city, category, keyword) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
   [current_admin_email(), $title, $description, $country, $state, $city, $category, $keyword]);
echo 'Job posted.';
