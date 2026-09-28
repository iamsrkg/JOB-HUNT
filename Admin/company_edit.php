<?php
require_once __DIR__ . '/connection/db.php';
require_admin(true);

$id = (int) ($_GET['edit'] ?? $_POST['id'] ?? 0);
$company = db("SELECT company_id, company_name, des, admin FROM company WHERE company_id = ?", [$id])->fetch_assoc();
if (!$company) {
  http_response_code(404);
  exit('Company not found. <a href="create_company.php">Back</a>');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $name = trim((string) ($_POST['Company'] ?? ''));
  $des = trim((string) ($_POST['des'] ?? ''));
  $admin = trim((string) ($_POST['admin'] ?? ''));

  if ($name === '' || mb_strlen($name) > 100) {
    $error = 'Enter a company name (up to 100 characters).';
  } elseif (mb_strlen($des) > 1000) {
    $error = 'Description is too long.';
  } elseif (!db("SELECT 1 FROM admin_login WHERE admin_email = ? AND admin_type = '2'", [$admin])->fetch_row()) {
    $error = 'Choose a valid company admin.';
  } else {
    db("UPDATE company SET company_name = ?, des = ?, admin = ? WHERE company_id = ?", [$name, $des, $admin, $id]);
    redirect('create_company.php');
  }
  $company = array_merge($company, ['company_name' => $name, 'des' => $des, 'admin' => $admin]);
}

$admins = db("SELECT admin_email FROM admin_login WHERE admin_type = '2' ORDER BY admin_email");
include('include/header.php');
include('include/sidebar.php');
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="create_company.php">Company</a></li>
                <li class="breadcrumb-item"><a href="#">Update Company</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Update Company</h1>
          </div>

           <div style="width: 60%; margin-left: 20%; background-color: #EBEDEF;">
            <form action="company_edit.php" method="post" style="margin: 3%; padding: 3%;" name="company_form" id="company_form">
              <?php if ($error) { ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php } ?>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="id" value="<?php echo (int) $company['company_id']; ?>">
              <div class="form-group">
               <label for="Company">Company name</label>
               <input type="text" name="Company" id="Company" value="<?php echo e($company['company_name']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="des">Description</label>
               <textarea name="des" id="des" class="form-control" cols="30" rows="10"><?php echo e($company['des']); ?></textarea>
              </div>
              <div class="form-group">
               <label for="admin">Company admin</label>
               <select name="admin" id="admin" class="form-control">
                 <?php while ($row = $admins->fetch_assoc()) { ?>
                   <option value="<?php echo e($row['admin_email']); ?>" <?php echo $row['admin_email'] === $company['admin'] ? 'selected' : ''; ?>><?php echo e($row['admin_email']); ?></option>
                 <?php } ?>
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
