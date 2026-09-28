<?php
require_once __DIR__ . '/connection/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $email = trim((string) ($_POST['email'] ?? ''));
  $password = (string) ($_POST['password'] ?? '');
  $firstName = trim((string) ($_POST['first_name'] ?? ''));
  $lastName = trim((string) ($_POST['last_name'] ?? ''));
  $mobile = preg_replace('/\D+/', '', (string) ($_POST['mobile_number'] ?? ''));
  $dob = (string) ($_POST['dob'] ?? '');
  $dobDate = DateTime::createFromFormat('Y-m-d', $dob);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
  if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
  if ($firstName === '' || $lastName === '') $errors[] = 'Enter your first and last name.';
  if (strlen($mobile) < 7 || strlen($mobile) > 15) $errors[] = 'Enter a valid mobile number.';
  if (!$dobDate || $dobDate->format('Y-m-d') !== $dob) $errors[] = 'Enter your date of birth.';

  if (!$errors) {
    if (db("SELECT 1 FROM jobseeker WHERE email = ?", [$email])->fetch_row()) {
      $errors[] = 'An account with this email already exists. Try signing in.';
    } else {
      db("INSERT INTO jobseeker (email, password, first_name, last_name, dob, mobile_number) VALUES (?, ?, ?, ?, ?, ?)",
         [$email, password_hash($password, PASSWORD_DEFAULT), $firstName, $lastName, $dob, $mobile]);
      login_user($email);
      redirect('index.php');
    }
  }
}
$old = fn($key) => e($_POST[$key] ?? '');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sign up · JOBHUNT</title>

<!-- CORE STYLES FOR PAGE -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="sign_up.php" method="post">
      <?php echo csrf_field(); ?>
      <img class="mb-4" src="profile_img/logo1.png" alt="" width="100" height="100">
      <h1 class="h3 mb-3 font-weight-normal">Please Sign up</h1>
      <?php if ($errors) { ?>
        <div class="alert alert-danger text-left" role="alert"><?php foreach ($errors as $err) { echo e($err) . '<br>'; } ?></div>
      <?php } ?>
      <label for="inputEmail" class="sr-only">Email Address</label>
      <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php echo $old('email'); ?>" required autofocus>

      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password (min 8 characters)" minlength="8" required>

      <label for="first_name" class="sr-only">First Name</label>
      <input type="text" id="first_name" class="form-control" name="first_name" placeholder="Enter Your First Name" value="<?php echo $old('first_name'); ?>" required>

      <label for="last_name" class="sr-only">Last Name</label>
      <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Enter Your Last Name" value="<?php echo $old('last_name'); ?>" required>

      <label for="mobile_number" class="sr-only">Mobile Number</label>
      <input type="tel" id="mobile_number" class="form-control" name="mobile_number" placeholder="Enter Your Mobile Number" value="<?php echo $old('mobile_number'); ?>" required>

      <label for="dob" class="sr-only">Date of Birth</label>
      <input type="date" id="dob" class="form-control" name="dob" placeholder="Enter Your Date of Birth" value="<?php echo $old('dob'); ?>" required>

      <input type="submit" class="btn btn-lg btn-primary btn-block" name="submit" value="Sign-up">
      <a href="job-post.php">Already have an account? Sign in</a>
      <p class="mt-5 mb-3 text-muted">&copy; 2020-2021</p>
    </form>
  </body>

</html>
