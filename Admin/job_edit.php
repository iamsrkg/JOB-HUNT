<?php
require_once __DIR__ . '/connection/db.php';
require_admin();

// Recruiters may only edit their own jobs; super admins may edit any.
$id = (int) ($_GET['edit'] ?? $_POST['id'] ?? 0);
$job = is_super_admin()
  ? db("SELECT * FROM all_jobs WHERE job_id = ?", [$id])->fetch_assoc()
  : db("SELECT * FROM all_jobs WHERE job_id = ? AND customer_email = ?", [$id, current_admin_email()])->fetch_assoc();
if (!$job) {
  http_response_code(404);
  exit('Job not found. <a href="job_create.php">Back</a>');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $fields = [
    'job_title' => trim((string) ($_POST['job_title'] ?? '')),
    'des' => trim((string) ($_POST['Description'] ?? '')),
    'country' => trim((string) ($_POST['country'] ?? '')),
    'state' => trim((string) ($_POST['state'] ?? '')),
    'city' => trim((string) ($_POST['city'] ?? '')),
    'keyword' => trim((string) ($_POST['Keyword'] ?? '')),
    'category' => (string) ($_POST['category'] ?? ''),
  ];
  if ($fields['job_title'] === '' || mb_strlen($fields['job_title']) > 150) {
    $error = 'Enter a job title (up to 150 characters).';
  } elseif ($fields['des'] === '' || mb_strlen($fields['des']) > 5000) {
    $error = 'Enter a description (up to 5000 characters).';
  } elseif ($fields['country'] === '' || mb_strlen($fields['country']) > 100 || mb_strlen($fields['state']) > 100 || mb_strlen($fields['city']) > 100) {
    $error = 'Enter a location.';
  } elseif (mb_strlen($fields['keyword']) > 100) {
    $error = 'Keyword is too long.';
  } elseif (!db("SELECT 1 FROM job_category WHERE id = ?", [$fields['category']])->fetch_row()) {
    $error = 'Choose a category.';
  } else {
    db("UPDATE all_jobs SET job_title = ?, des = ?, country = ?, state = ?, city = ?, keyword = ?, category = ? WHERE job_id = ?",
       [$fields['job_title'], $fields['des'], $fields['country'], $fields['state'], $fields['city'], $fields['keyword'], $fields['category'], $id]);
    redirect('job_create.php');
  }
  $job = array_merge($job, $fields);
}

$categories = db("SELECT id, category FROM job_category ORDER BY category");
include('include/header.php');
include('include/sidebar.php');
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="job_create.php">All Jobs Lists</a></li>
                <li class="breadcrumb-item"><a href="#">Edit Job</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Job</h1>
          </div>

          <div style="width: 60%; margin-left: 20%; background-color: #EBEDEF;">
            <form action="job_edit.php" method="post" style="margin: 3%; padding: 3%;" name="job_form" id="job_form">
              <?php if ($error) { ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php } ?>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="id" value="<?php echo (int) $job['job_id']; ?>">
              <div class="form-group">
               <label for="job_title">Job Title</label>
               <input type="text" value="<?php echo e($job['job_title']); ?>" name="job_title" id="job_title" class="form-control" maxlength="150" required>
              </div>
              <div class="form-group">
               <label for="Description">Description</label>
               <textarea name="Description" id="Description" class="form-control" cols="30" rows="10" maxlength="5000" required><?php echo e($job['des']); ?></textarea>
              </div>
              <div class="form-group">
               <label for="Keyword">Keyword</label>
               <input type="text" value="<?php echo e($job['keyword']); ?>" name="Keyword" id="Keyword" class="form-control" maxlength="100">
              </div>
              <div class="form-group">
               <label for="country">Country</label>
               <input type="text" value="<?php echo e($job['country']); ?>" name="country" id="country" class="form-control" maxlength="100" required>
              </div>
              <div class="form-group">
               <label for="state">State</label>
               <input type="text" value="<?php echo e($job['state']); ?>" name="state" id="state" class="form-control" maxlength="100">
              </div>
              <div class="form-group">
               <label for="city">City</label>
               <input type="text" value="<?php echo e($job['city']); ?>" name="city" id="city" class="form-control" maxlength="100">
              </div>
              <div class="form-group">
               <label for="category">Category</label>
               <select name="category" id="category" class="form-control">
                 <?php while ($row = $categories->fetch_assoc()) { ?>
                   <option value="<?php echo (int) $row['id']; ?>" <?php echo (string) $row['id'] === (string) $job['category'] ? 'selected' : ''; ?>><?php echo e($row['category']); ?></option>
                 <?php } ?>
               </select>
              </div>
              <div class="form-group">
               <input type="submit" class="btn btn-block btn-success" value="Save" name="submit" id="submit">
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
