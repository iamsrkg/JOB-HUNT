<?php
// Sign-in for company recruiters (admin_type 2); they manage their own jobs in /Admin.
require_once __DIR__ . '/connection/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $email = trim((string) ($_POST['email'] ?? ''));
  $password = (string) ($_POST['Password'] ?? '');

  $admin = db("SELECT id, admin_email, admin_pass, admin_type FROM admin_login WHERE admin_email = ? AND admin_type = '2' LIMIT 1", [$email])->fetch_assoc();
  if ($admin && password_matches($password, $admin['admin_pass'], $needsRehash)) {
    if ($needsRehash) {
      db("UPDATE admin_login SET admin_pass = ? WHERE id = ?", [password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
    }
    login_admin($admin);
    redirect('Admin/admin_dashboard.php');
  }
  $error = 'Email or password is incorrect. Please try again.';
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Recruiter sign-in · JOBHUNT</title>

    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="new-post.php" method="post" >
      <?php echo csrf_field(); ?>
      <img class="mb-4" src="profile_img/logo1.png" alt="" width="100" height="100">
      <h1 class="h3 mb-3 font-weight-normal">Recruiter sign-in</h1>
      <?php if ($error) { ?><div class="alert alert-danger" role="alert"><?php echo e($error); ?></div><?php } ?>
      <label for="inputEmail" class="sr-only">Email Address</label>
      <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" value="<?php echo e($_POST['email'] ?? ''); ?>" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="Password" class="form-control" placeholder="Password" required>

      <input class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Sign in">
      <p class="mt-5 mb-3 text-muted">&copy; 2020-2021</p>
    </form>
  </body>

</html>
