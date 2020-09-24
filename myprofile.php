<?php

include('connection/db.php');
include('include/myprofile_header.php');
include('include/header.php');
$query=mysqli_query($conn,"select * from profile where user_email='{$_SESSION['email']}'");
while($row=mysqli_fetch_array($query)){
        $img=$row['img'];
        $name=$row['name'];
        $dob=$row['dob'];
        $number=$row['number'];
        $email=$row['email'];
}
?>

    
<br>
<div style="margin-left: 25%; width: 50%; border: 1px solid gray; padding: 10px;">
  <form action="profile_add.php" method="POST" id="profile_form" name="profile_form" enctype="multipart/form-data">
    

    <div class="row">
      <div class="col-md-6">
         <img src="profile_img/<?php if(!empty($img)){echo $img;} else{echo "avtaar.png" ;} ?>" class="img-thumbnail" alt="Cinque Terre">
      </div>
      
      <div class="col-md-4">
        <input type="file" class="form-control" name="img" id="img">
        
      </div>
      
    </div>

<div style=" margin-left: 20%; ">
      <div class="row">
        
            <div class="col-md-6">
            <td> Enter Your Name:</td>
            </div>

            <div class="col-md-6">
            <td><input type="text" name="name" id="name" value="<?php  if(!empty($name)) echo $name;?>" placeholder="Enter Your Name..." class="form-group"></td>
            </div>
        </div>

       
       <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your DOB:</td>
          </div>

          <div class="col-md-6">
          <td><input type="date" name="dob" id="dob" value="<?php  if(!empty($dob)) echo $dob;?>" placeholder="Enter Your DOB..." class="form-group"></td>
          </div>
      </div>

      
      <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your Mobile Number:</td>
          </div>

          <div class="col-md-6">
          <td><input type="number" name="number" id="number" value="<?php  if(!empty($number)) echo $number;?>" placeholder="Enter Your Mobile Number..." class="form-group"></td>
          </div>
    </div>

      
      <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your Email:</td>
          </div>

          <div class="col-md-6">
          <td><input type="text" name="email" id="email" value="<?php  if(!empty($email)) echo $email;?>" placeholder="Enter Your Email..." class="form-group"></td>
          </div>
     </div>

<div class="form-group">
   <input type="submit" name="submit" id="submit" placeholder="update"n value="Update" class="btn btn-success">
</div>
</div>

</form>

</div>




   <br>
		<section class="ftco-section-parallax">
      <div class="parallax-img d-flex align-items-center">
        <div class="container">
          <div class="row d-flex justify-content-center">
            <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
              <h2>Subcribe to our page for more information</h2>
              <p>" A clay pot sitting in the sun will always be a clay pot. It has to go through the white heat of the furnace to become porcelain.”</p>
              <div class="row d-flex justify-content-center mt-4 mb-4">
                <div class="col-md-8">
                  <form action="#" class="subscribe-form">
                    <div class="form-group d-flex">
                      <input type="text" class="form-control" placeholder="Enter email address">
                      <input type="submit" value="Subscribe" class="submit px-3">
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php
    include('include/footer.php');
    ?>

    <!-- <script>
      $(document).ready(function(){

        $("#submit").click(function(e){
          e.preventDefault();  
          var data=$("#profile_form").serialize();
            $.ajax({
            type:"POST",
            url:"profile_add.php",
            data: data,
            success: function(data){
            alert(data);
            }
       });
        })
      });
    </script> -->