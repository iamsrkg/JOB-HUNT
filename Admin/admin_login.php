<?php
require_once __DIR__ . '/connection/db.php';

if (current_admin_email() !== null) {
  redirect('admin_dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $email = trim((string) ($_POST['email'] ?? ''));
  $password = (string) ($_POST['pass'] ?? '');

  $admin = db("SELECT id, admin_email, admin_pass, admin_type FROM admin_login WHERE admin_email = ? LIMIT 1", [$email])->fetch_assoc();
  // Same message for unknown email and wrong password, so accounts can't be enumerated.
  if ($admin && password_matches($password, $admin['admin_pass'], $needsRehash)) {
    if ($needsRehash) {
      db("UPDATE admin_login SET admin_pass = ? WHERE id = ?", [password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
    }
    login_admin($admin);
    redirect('admin_dashboard.php');
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="css/admin_login.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" id="admin_login" method="post" action="admin_login.php" name="admin_login" >
      <?php echo csrf_field(); ?>
      <img class="mb-4" style=" margin-left: 2%;" src="img/logo.png" alt="" width="110" height="110">
      <h1 class="h3 mb-3 font-weight-normal" style=" margin-left: 4%;">Please Sign In</h1>
      <?php if ($error) { ?><div class="alert alert-danger" role="alert"><?php echo e($error); ?></div><?php } ?>
      <label for="email" class="sr-only">Email address</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Email address" value="<?php echo e($_POST['email'] ?? ''); ?>" required autofocus>
      <label for="pass" class="sr-only">Password</label>
      <input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required>

      <input class="btn btn-lg btn-primary btn-block" name="submit" id="submit" type="submit" value="Sign in">
      <p class="mt-5 mb-3 text-muted" style=" margin-left: 3%;">&copy; 2020-2021</p>
    </form>
  </body>

</html>
