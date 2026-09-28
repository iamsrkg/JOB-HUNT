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
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                      <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Applied Jobs</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            
            <h1 class="h2">Applied Jobs</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                
              </div>
              
            </div>
          </div>

         <div style="border: 1px solid gray; width: 80%; margin-left: 10%; padding: 10px">
           <div class="form-group"><label>Job title:</label> <?php echo e($app['job_title']); ?></div>
           <div class="form-group"><label>Description:</label> <?php echo nl2br(e($app['des'])); ?></div>
           <div class="form-group"><label>Applicant:</label> <?php echo e($app['first_name'] . ' ' . $app['last_name']); ?></div>
           <div class="form-group"><label>Email:</label> <?php echo e($app['email']); ?></div>
           <div class="form-group"><label>Contact number:</label> <?php echo e($app['phone']); ?></div>
           <div class="form-group"><label>Date of birth:</label> <?php echo e($app['dob']); ?></div>
           <div class="form-group"><label>Resume:</label>
             <?php if ($app['file'] !== '' && $app['file'] !== null) { ?><a href="download_resume.php?id=<?php echo (int) $app['id']; ?>">Download</a><?php } else { ?>Not uploaded<?php } ?>
           </div>
           <div class="form-group"><label>Status:</label> <?php echo e(ucfirst($app['status'])); ?></div>

           <?php if ($app['status'] === 'new') { ?>
             <a href="send_email.php?id=<?php echo (int) $app['id']; ?>" class="btn btn-success">Accept &amp; email applicant</a>
             <form action="reject_job.php" method="post" style="display:inline" onsubmit="return confirm('Reject this application?')">
               <?php echo csrf_field(); ?>
               <input type="hidden" name="id" value="<?php echo (int) $app['id']; ?>">
               <button type="submit" class="btn btn-danger">Reject</button>
             </form>
           <?php } ?>
           <a href="apply_jobs.php" class="btn btn-secondary">Back to applications</a>
         </div>
        

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