<?php
// Streams an applicant's resume to an admin allowed to see that application.
// Resumes are personal data: the files/ folder itself is not web-accessible.
require_once __DIR__ . '/connection/db.php';
require_admin();

$app = find_application((int) ($_GET['id'] ?? 0));
$name = $app['file'] ?? '';
// Stored names are random hex + extension; anything else is rejected outright.
if (!$app || !preg_match('/^[a-f0-9]{32}\.(pdf|doc|docx)$/', $name) || !is_file(APP_ROOT . '/files/' . $name)) {
  http_response_code(404);
  exit('Resume not found.');
}

$types = ['pdf' => 'application/pdf', 'doc' => 'application/msword',
          'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$ext = pathinfo($name, PATHINFO_EXTENSION);
$download = preg_replace('/[^A-Za-z0-9_-]+/', '_', $app['first_name'] . '_' . $app['last_name']) . '_resume.' . $ext;

header('Content-Type: ' . $types[$ext]);
header('Content-Disposition: attachment; filename="' . $download . '"');
header('X-Content-Type-Options: nosniff');
header('Content-Length: ' . filesize(APP_ROOT . '/files/' . $name));
readfile(APP_ROOT . '/files/' . $name);
