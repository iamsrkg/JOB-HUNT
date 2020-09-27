<?php
include('connection/db.php');

include('include/header.php');
include('include/sidebar.php');

$id=$_GET['edit'];
$query=mysqli_query($conn,"select * from admin_login where id= '$id'");

while($row=mysqli_fetch_array($query)){
$email=$row['admin_email'];
$first_name=$row['first_name'];
$last_name=$row['last_name'];
$admin_pass=$row['admin_pass'];
$admin_username=$row['admin_username'];
$admin_type=$row['admin_type'];


}




?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                      <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="Customers.php">Employees</a></li>
                <li class="breadcrumb-item"><a href="#">Update Employee</a></li>
              </ol>
            </nav>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            
            <h1 class="h2">Update Employee</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                
              </div>
              <!-- <a class="btn btn-primary" href="add_customer.php">Add Customer</a> -->
            </div>
          </div>
          
           <div style="width: 60%; margin-left: 20%; background-color: #EBEDEF;">
            
            <form action="" method="post" style="margin: 3%; padding: 3%;" name="customer_form" id="customer_form">
              <div id="msg"> </div>
              <div class="form-group">
               <label for="Customer Email">Enter Email</label> 
               <input type="email" name="email" id="email" value="<?php echo $email; ?>" class="form-control" placeholder="Enter Employee email"> 
              </div>
                  <div class="form-group">
                   <label for="Customer Username">Enter Username</label> 
                   <input type="text" name="Username" value="<?php echo $admin_username; ?>" id="Username" class="form-control" placeholder="Enter Employee Username"> 
                  </div>

                       <div class="form-group">
                       <label for="Password">Enter Password</label> 
                       <input type="pass" name="Password" value="<?php echo $admin_pass; ?>" id="Password" class="form-control" placeholder="Enter Password"> 
                      </div>

                       <div class="form-group">
                       <label for="First Name">Enter First Name</label> 
                       <input type="text" name="first_name" value="<?php echo $first_name; ?>" id="first_name" class="form-control" placeholder="Enter First Name"> 
                      </div>

                       <div class="form-group">
                       <label for="Last Name">Enter Last Name</label> 
                       <input type="text" name="last_name" value="<?php echo $last_name; ?>"id="last_name" class="form-control" placeholder="Enter Last Name"> 
                      </div>

                      <div class="form-group">
                       <label for="Admin Type">Admin Type</label> 
                       <select name="admin_type" name="admin_type" value="<?php echo $admin_type; ?>" class="form-control" id="admin_type">
                       <option value="1">Super Admin</option> 
                       <option value="2">Customer Admin</option> 
                     </select>
                      </div>
                          
                          <input type="hidden" name="id" id="id" value="<?php echo $_GET['edit'];?>">
                      <div class="form-group">
                        
                       <input type="submit" class="btn btn-block btn-success" placeholder="Update" name="submit" id="submit"> 
                      </div>

                </form>
              </div>

         <canvas class="my-4" id="myChart" width="900" height="380"></canvas>

          <div class="table-responsive">
            
          </div>
        </main>
      </div>
    </div>

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

<?php
include('connection/db.php');
if(isset($_POST['submit'])){
	$id=$_POST['id'];
	$email=$_POST['email'];
	$Username=$_POST['Username'];
	$Password=$_POST['Password'];
	$first_name=$_POST['first_name'];
	$last_name=$_POST['last_name'];
	$admin_type=$_POST['admin_type'];
	
   $query1=mysqli_query($conn,"update admin_login set admin_email='$email',admin_username='$Username',admin_pass='$Password',first_name='$first_name',last_name='$last_name',admin_type='$admin_type' where id='$id'");
   if($query1){
   	echo "<script>alert('Record has been Updated successfully !!!')</script>";
   }else{
   	echo "<script>alert('some error please try again')</script>";
   }
}



?>