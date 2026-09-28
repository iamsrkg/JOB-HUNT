<?php
// Reject an application (POST + CSRF). The record is kept with status "rejected", not deleted.
require_once __DIR__ . '/connection/db.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}
require_csrf();

$app = find_application((int) ($_POST['id'] ?? 0));
if (!$app) {
  http_response_code(404);
  exit('Application not found. <a href="apply_jobs.php">Back</a>');
}
db("UPDATE job_apply SET status = 'rejected' WHERE id = ?", [$app['id']]);
redirect('apply_jobs.php');
