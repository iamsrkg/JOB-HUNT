<?php
require_once __DIR__ . '/connection/db.php';
require_admin(true);

$id = (int) ($_GET['edit'] ?? $_POST['id'] ?? 0);
$account = db("SELECT id, admin_email, admin_username, first_name, last_name, admin_type FROM admin_login WHERE id = ?", [$id])->fetch_assoc();
if (!$account) {
  http_response_code(404);
  exit('Account not found. <a href="Customers.php">Back</a>');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $email = trim((string) ($_POST['email'] ?? ''));
  $username = trim((string) ($_POST['Username'] ?? ''));
  $password = (string) ($_POST['Password'] ?? '');
  $firstName = trim((string) ($_POST['first_name'] ?? ''));
  $lastName = trim((string) ($_POST['last_name'] ?? ''));
  $adminType = (string) ($_POST['admin_type'] ?? '');

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Enter a valid email address.';
  } elseif ($username === '' || $firstName === '' || $lastName === '') {
    $error = 'Username, first name and last name are required.';
  } elseif ($password !== '' && strlen($password) < 8) {
    $error = 'A new password must be at least 8 characters.';
  } elseif (!in_array($adminType, ['1', '2'], true)) {
    $error = 'Choose an account type.';
  } elseif ($id === (int) $_SESSION['admin_id'] && $adminType !== '1') {
    $error = 'You cannot remove your own super-admin role.';
  } elseif (db("SELECT 1 FROM admin_login WHERE admin_email = ? AND id <> ?", [$email, $id])->fetch_row()) {
    $error = 'Another account already uses this email.';
  } else {
    db("UPDATE admin_login SET admin_email = ?, admin_username = ?, first_name = ?, last_name = ?, admin_type = ? WHERE id = ?",
       [$email, $username, $firstName, $lastName, $adminType, $id]);
    // Blank password means "keep the current one".
    if ($password !== '') {
      db("UPDATE admin_login SET admin_pass = ? WHERE id = ?", [password_hash($password, PASSWORD_DEFAULT), $id]);
    }
    redirect('Customers.php');
  }
  $account = array_merge($account, ['admin_email' => $email, 'admin_username' => $username,
    'first_name' => $firstName, 'last_name' => $lastName, 'admin_type' => $adminType]);
}

include('include/header.php');
include('include/sidebar.php');
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="Customers.php">Job providers</a></li>
                <li class="breadcrumb-item"><a href="#">Update account</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Update account</h1>
          </div>

           <div style="width: 60%; margin-left: 20%; background-color: #EBEDEF;">
            <form action="customer_edit.php" method="post" style="margin: 3%; padding: 3%;" name="customer_form" id="customer_form">
              <?php if ($error) { ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php } ?>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="id" value="<?php echo (int) $account['id']; ?>">
              <div class="form-group">
               <label for="email">Email</label>
               <input type="email" name="email" id="email" value="<?php echo e($account['admin_email']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="Username">Username</label>
               <input type="text" name="Username" id="Username" value="<?php echo e($account['admin_username']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="Password">New password</label>
               <input type="password" name="Password" id="Password" class="form-control" placeholder="Leave blank to keep the current password" minlength="8" autocomplete="new-password">
              </div>
              <div class="form-group">
               <label for="first_name">First name</label>
               <input type="text" name="first_name" id="first_name" value="<?php echo e($account['first_name']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="last_name">Last name</label>
               <input type="text" name="last_name" id="last_name" value="<?php echo e($account['last_name']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="admin_type">Account type</label>
               <select name="admin_type" class="form-control" id="admin_type">
                 <option value="1" <?php echo $account['admin_type'] === '1' ? 'selected' : ''; ?>>Super Admin</option>
                 <option value="2" <?php echo $account['admin_type'] === '2' ? 'selected' : ''; ?>>Company (Customer Admin)</option>
               </select>
              </div>
              <div class="form-group">
               <input type="submit" class="btn btn-block btn-success" value="Update" name="submit" id="submit">
              </div>
            </form>
           </div>
        </main>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>feather.replace()</script>
  </body>
</html>
