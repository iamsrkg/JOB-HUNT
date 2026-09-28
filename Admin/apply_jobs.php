<?php
include('include/header.php');
include('include/sidebar.php');
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                      <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Apply Jobs</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            
            <h1 class="h2">Applications</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                
              </div>
              
            </div>
          </div>

          <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Job Title</th>
                <th>Description</th>
                <th>Job Seeker Name</th>
                <th>Job Seeker Email</th>    
                <th>Resume</th>
                <th>Status</th>
               <th>Action</th>
                
            </tr>
        </thead>
        <tbody>
            
        <?php
        $a = 1;
        $query = visible_applications();
        while($row = $query->fetch_assoc()){
        ?>
            <tr>
            	<td><?php echo $a; ?></td>
                <td><?php echo e($row['job_title']); ?></td>
                <td><?php echo e(mb_strimwidth($row['des'], 0, 80, '…')); ?></td>
                <td><?php echo e($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo e($row['email']); ?></td>
                <td><?php if ($row['file'] !== '' && $row['file'] !== null) { ?><a href="download_resume.php?id=<?php echo (int) $row['id']; ?>">Download</a><?php } else { ?>—<?php } ?></td>
                <td><?php echo e(ucfirst($row['status'])); ?></td>
               <td>
                    <div class="row">
                      <div class="btn-group">
                        <a href="view_applied_jobs.php?id=<?php echo (int) $row['id']; ?>"><span class="glyphicon glyphicon-eye-open"></span></a>
                        
                      </div>

                    </div>
               </td>
                
            </tr>
        <?php $a++; } ?>
        </tbody>
        <tfoot>
            <tr>
               <th>#SL</th>
                <th>Job Title</th>
                <th>Description</th>
                <th>Job Seeker Name</th>
                <th>Job Seeker Email</th>    
                <th>Resume</th>
                <th>Status</th>
               <th>Action</th>

            </tr>
        </tfoot>
    </table>

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