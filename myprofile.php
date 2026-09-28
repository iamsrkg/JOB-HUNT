<?php
require_once __DIR__ . '/connection/db.php';
require_user();
include('include/myprofile_header.php');   // already includes the site header

$profile = db("SELECT img, name, dob, number, email FROM profile WHERE user_email = ? LIMIT 1", [current_user_email()])->fetch_assoc() ?: [];
$img = $profile['img'] ?? '';
$name = $profile['name'] ?? '';
$dob = $profile['dob'] ?? '';
$number = $profile['number'] ?? '';
$email = $profile['email'] ?? '';
$notice = $_SESSION['profile_notice'] ?? null;
unset($_SESSION['profile_notice']);
?>

    
<br>
<div style="margin-left: 25%; width: 50%; border: 1px solid gray; padding: 10px;">
  <?php if ($notice) { ?>
    <div class="alert alert-<?php echo $notice['ok'] ? 'success' : 'danger'; ?>" role="alert"><?php echo e($notice['message']); ?></div>
  <?php } ?>
  <form action="profile_add.php" method="POST" id="profile_form" name="profile_form" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="row">
      <div class="col-md-6">
         <img src="profile_img/<?php echo e($img !== '' ? $img : 'avtaar.png'); ?>" class="img-thumbnail" alt="Profile photo">
      </div>
      
      <div class="col-md-4">
        <input type="file" class="form-control" name="img" id="img" accept=".jpg,.jpeg,.png,.webp">
        <small class="text-muted">JPG, PNG or WebP, max 2 MB</small>
        
      </div>
      
    </div>

<div style=" margin-left: 20%; ">
      <div class="row">
        
            <div class="col-md-6">
            <td> Enter Your Name:</td>
            </div>

            <div class="col-md-6">
            <td><input type="text" name="name" id="name" value="<?php echo e($name); ?>" placeholder="Enter Your Name..." class="form-group"></td>
            </div>
        </div>

       
       <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your DOB:</td>
          </div>

          <div class="col-md-6">
          <td><input type="date" name="dob" id="dob" value="<?php echo e($dob); ?>" placeholder="Enter Your DOB..." class="form-group"></td>
          </div>
      </div>

      
      <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your Mobile Number:</td>
          </div>

          <div class="col-md-6">
          <td><input type="tel" name="number" id="number" value="<?php echo e($number); ?>" placeholder="Enter Your Mobile Number..." class="form-group"></td>
          </div>
    </div>

      
      <div class="row">
      
          <div class="col-md-6">
          <td> Enter Your Email:</td>
          </div>

          <div class="col-md-6">
          <td><input type="email" name="email" id="email" value="<?php echo e($email); ?>" placeholder="Enter Your Email..." class="form-group"></td>
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
              <h2>Subscribe to our page for more information</h2>
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