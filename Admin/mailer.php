<?php
// Accept an application: email the applicant, then mark the application "accepted".
// SMTP settings come from environment variables; credentials never live in the code.
require_once __DIR__ . '/connection/db.php';
require_admin();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/email/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('apply_jobs.php');
}
require_csrf();

$app = find_application((int) ($_POST['id'] ?? 0));
if (!$app) {
  http_response_code(404);
  exit('Application not found. <a href="apply_jobs.php">Back</a>');
}

$subject = trim((string) ($_POST['subject'] ?? ''));
$body = trim((string) ($_POST['body'] ?? ''));
$outcome = null;   // ['ok' => bool, 'message' => string]

if ($subject === '' || mb_strlen($subject) > 150 || $body === '' || mb_strlen($body) > 5000) {
  $outcome = ['ok' => false, 'message' => 'Enter a subject (up to 150 characters) and a message (up to 5000).'];
} elseif (!getenv('SMTP_HOST')) {
  $outcome = ['ok' => false, 'message' => "Email isn't configured on this server. Set SMTP_HOST, SMTP_USER and SMTP_PASS (see README). The application was not changed."];
} else {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST');
    $mail->Port = (int) (getenv('SMTP_PORT') ?: 587);
    $mail->SMTPAuth = getenv('SMTP_USER') !== false && getenv('SMTP_USER') !== '';
    $mail->Username = (string) getenv('SMTP_USER');
    $mail->Password = (string) getenv('SMTP_PASS');
    // SMTP_SECURE: tls (STARTTLS, default) | ssl (implicit TLS, port 465) | none (local mail catchers only)
    $secure = strtolower((string) (getenv('SMTP_SECURE') ?: 'tls'));
    if ($secure === 'ssl') {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($secure === 'none') {
      $mail->SMTPSecure = '';
      $mail->SMTPAutoTLS = false;
    } else {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }
    $mail->CharSet = PHPMailer::CHARSET_UTF8;

    $mail->setFrom((string) (getenv('SMTP_FROM') ?: getenv('SMTP_USER')), (string) (getenv('SMTP_FROM_NAME') ?: 'JobHunt'));
    $mail->addReplyTo(current_admin_email());
    $mail->addAddress($app['email'], $app['first_name'] . ' ' . $app['last_name']);
    // Plain text only: the recruiter's message is never interpreted as HTML.
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();

    db("UPDATE job_apply SET status = 'accepted' WHERE id = ?", [$app['id']]);
    $outcome = ['ok' => true, 'message' => 'Email sent to ' . $app['email'] . ' and the application is marked accepted.'];
  } catch (Exception $ex) {
    error_log('Mailer error: ' . $mail->ErrorInfo);
    $outcome = ['ok' => false, 'message' => 'The email could not be sent. Please try again later.'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Send email · JobHunt</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body class="p-5">
  <div class="alert alert-<?php echo $outcome['ok'] ? 'success' : 'danger'; ?>"><?php echo e($outcome['message']); ?></div>
  <a href="apply_jobs.php" class="btn btn-secondary">Back to applications</a>
  <?php if (!$outcome['ok']) { ?><a href="send_email.php?id=<?php echo (int) $app['id']; ?>" class="btn btn-link">Try again</a><?php } ?>
</body>
</html>
