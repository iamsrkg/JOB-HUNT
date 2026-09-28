<?php
require_once __DIR__ . '/connection/db.php';
require_admin(true);

$id = (int) ($_GET['edit'] ?? $_POST['id'] ?? 0);
$cat = db("SELECT id, category, des FROM job_category WHERE id = ?", [$id])->fetch_assoc();
if (!$cat) {
  http_response_code(404);
  exit('Category not found. <a href="category.php">Back</a>');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  require_csrf();
  $category = trim((string) ($_POST['category'] ?? ''));
  $des = trim((string) ($_POST['des'] ?? ''));

  if ($category === '' || mb_strlen($category) > 100) {
    $error = 'Enter a category name (up to 100 characters).';
  } elseif (mb_strlen($des) > 100) {
    $error = 'Description is too long (up to 100 characters).';
  } elseif (db("SELECT 1 FROM job_category WHERE category = ? AND id <> ?", [$category, $id])->fetch_row()) {
    $error = 'Another category already has this name.';
  } else {
    db("UPDATE job_category SET category = ?, des = ? WHERE id = ?", [$category, $des, $id]);
    redirect('category.php');
  }
  $cat = array_merge($cat, ['category' => $category, 'des' => $des]);
}

include('include/header.php');
include('include/sidebar.php');
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="category.php">Category</a></li>
                <li class="breadcrumb-item"><a href="#">Update Category</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Update Category</h1>
          </div>

           <div style="width: 60%; margin-left: 20%; background-color: #EBEDEF;">
            <form action="edit_category.php" method="post" style="margin: 3%; padding: 3%;" name="category_form" id="category_form">
              <?php if ($error) { ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php } ?>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="id" value="<?php echo (int) $cat['id']; ?>">
              <div class="form-group">
               <label for="category">Category name</label>
               <input type="text" name="category" id="category" value="<?php echo e($cat['category']); ?>" class="form-control" required>
              </div>
              <div class="form-group">
               <label for="des">Description</label>
               <textarea name="des" id="des" class="form-control" cols="30" rows="5" maxlength="100"><?php echo e($cat['des']); ?></textarea>
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
