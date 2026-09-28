<?php
require_once __DIR__ . '/connection/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $email = trim((string) ($_POST['email'] ?? ''));
  $password = (string) ($_POST['Password'] ?? '');

  $user = db("SELECT id, email, password FROM jobseeker WHERE email = ? LIMIT 1", [$email])->fetch_assoc();
  // Same message for unknown email and wrong password, so accounts can't be enumerated.
  if ($user && password_matches($password, $user['password'], $needsRehash)) {
    if ($needsRehash) {
      db("UPDATE jobseeker SET password = ? WHERE id = ?", [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
    login_user($user['email']);
    redirect('index.php');
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

    <title>Sign in · JOBHUNT</title>

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="job-post.php" method="post" style="border: 1px solid gray;" >
      <?php echo csrf_field(); ?>
      <img class="mb-4" src="profile_img/logo1.png" alt="" width="100" height="100">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <?php if ($error) { ?><div class="alert alert-danger" role="alert"><?php echo e($error); ?></div><?php } ?>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email address" value="<?php echo e($_POST['email'] ?? ''); ?>" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" name="Password" placeholder="Password" required>
      <input class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Sign in">
      <a href="sign_up.php">Create an Account</a>
      <p class="mt-5 mb-3 text-muted">&copy; 2020-2021</p>
    </form>
  </body>
</html>
