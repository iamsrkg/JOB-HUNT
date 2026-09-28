<?php
require_once __DIR__ . '/connection/db.php';
require_admin();
$app = find_application((int) ($_GET['id'] ?? 0));
if (!$app) {
  http_response_code(404);
  exit('Application not found. <a href="apply_jobs.php">Back</a>');
}
include('include/header.php');
include('include/sidebar.php');
$applicant = $app['first_name'] . ' ' . $app['last_name'];
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                      <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Send E-Mail</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            
            <h1 class="h2">Send E-Mail</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                
              </div>
              
            </div>
          </div>

         <form action="mailer.php" method="post" style="border: 1px solid gray; width: 60%; margin-left: 10%; padding: 10px">

          <?php echo csrf_field(); ?>
          <h1><?php echo e($applicant); ?></h1>
          <p class="text-muted">Application for <?php echo e($app['job_title']); ?></p>
          <hr>
          <input type="hidden" name="id" value="<?php echo (int) $app['id']; ?>">
          <div class="form-group">
            <label>To:</label>
            <input type="email" class="form-control" value="<?php echo e($app['email']); ?>" readonly>
            <small class="text-muted">Replies from the applicant go to <?php echo e(current_admin_email()); ?>.</small>
          </div>
          <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" class="form-control" maxlength="150" value="<?php echo e('Your application for ' . $app['job_title']); ?>" required>
          </div>
          <div class="form-group">
            <label for="body">Message:</label>
            <textarea name="body" id="body" class="form-control" cols="30" rows="10" maxlength="5000" required><?php echo e("Dear $applicant,\n\nThank you for applying. We'd like to move forward with your application.\n\n"); ?></textarea>
          </div>
         <input type="submit" class="btn btn-success" name="submit" id="submit" value="Send &amp; mark accepted">
        
        

        </form>
        

         <canvas class="my-4" id="myChart" width="900" height="380"></canvas>

          <div class="table-responsive">
            
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>
<!-- datatables plugin -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
  </body>
</html>