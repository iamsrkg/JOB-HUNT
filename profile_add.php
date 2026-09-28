<?php
// Saves the job seeker's profile, then redirects back (post/redirect/get) with a one-time notice.
require_once __DIR__ . '/connection/db.php';
require_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('myprofile.php');
}
require_csrf();

$userEmail = current_user_email();
$name = trim((string) ($_POST['name'] ?? ''));
$dob = (string) ($_POST['dob'] ?? '');
$number = preg_replace('/\D+/', '', (string) ($_POST['number'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));

$notice = null;
if (mb_strlen($name) > 100) {
  $notice = ['ok' => false, 'message' => 'Name is too long.'];
} elseif ($dob !== '' && (!($d = DateTime::createFromFormat('Y-m-d', $dob)) || $d->format('Y-m-d') !== $dob)) {
  $notice = ['ok' => false, 'message' => 'Enter a valid date of birth.'];
} elseif ($number !== '' && (strlen($number) < 7 || strlen($number) > 15)) {
  $notice = ['ok' => false, 'message' => 'Enter a valid mobile number.'];
} elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $notice = ['ok' => false, 'message' => 'Enter a valid contact email.'];
}

$existing = db("SELECT id, img FROM profile WHERE user_email = ? LIMIT 1", [$userEmail])->fetch_assoc();
$img = $existing['img'] ?? '';

// A new photo is optional: keep the current one unless a valid image was uploaded.
if (!$notice && ($_FILES['img']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
  $stored = store_upload($_FILES['img'], APP_ROOT . '/profile_img', IMAGE_TYPES, 2 * 1024 * 1024, $uploadError);
  if ($stored === null) {
    $notice = ['ok' => false, 'message' => $uploadError];
  } else {
    $img = $stored;
  }
}

if (!$notice) {
  if ($existing) {
    db("UPDATE profile SET img = ?, name = ?, dob = ?, number = ?, email = ? WHERE id = ?",
       [$img, $name, $dob, $number, $email, $existing['id']]);
  } else {
    db("INSERT INTO profile (img, name, dob, number, email, user_email) VALUES (?, ?, ?, ?, ?, ?)",
       [$img, $name, $dob, $number, $email, $userEmail]);
  }
  $notice = ['ok' => true, 'message' => 'Profile saved.'];
}

$_SESSION['profile_notice'] = $notice;
redirect('myprofile.php');
