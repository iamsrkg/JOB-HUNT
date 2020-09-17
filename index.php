<?php
$page='home';
include('include/header.php');


?>

    <div class="hero-wrap js-fullheight" style="background-image: url('images/bgi2.jpeg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-xl-10 ftco-animate mb-5 pb-5" data-scrollax=" properties: { translateY: '70%' }">
            <p  class="mb-4 mt-5 pt-5" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: black;"><marquee><b>We are providing more than</b> <span class="number" data-number="650000">0</span> <b>great job offers you deserve!</b></marquee></p>
            <h1 class="mb-5" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color: white;">Your Dream <br><span>Career is Waiting</span></h1>

            <div class="ftco-search">
              <div class="row">
                <div class="col-md-12 nav-link-wrap">
                  <div class="nav nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active mr-md-1" id="v-pills-1-tab" data-toggle="pill" href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Find a Job</a>

                    <a class="nav-link" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2" role="tab" aria-controls="v-pills-2" aria-selected="false">Find a Candidate</a>

                  </div>
                </div>
                <div class="col-md-12 tab-wrap">
                  
                  <div class="tab-content p-4" id="v-pills-tabContent">

                    <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-nextgen-tab">
                      <form action="index.php" method="post" class="search-job">
                        <div class="row">
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="icon"><span class="icon-briefcase"></span></div>
                                <input type="text" name="key" id="key" class="form-control" placeholder="eg. Garphic. Web Developer">
                              </div>
                            </div>
                          </div>
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="select-wrap">
                                  <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                  <select name="category" id="category" class="form-control">
                                    <option value="">Category</option>
                                      <?php
                                      include('connection/db.php');
                                      $query=mysqli_query($conn, "SELECT * FROM job_category");
                                      while($row=mysqli_fetch_array($query)){ ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['category']; ?></option>
                                      <?php  } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        <!--  <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="icon"><span class="icon-map-marker"></span></div>
                                <input type="text" class="form-control" placeholder="Location">
                              </div>
                            </div>
                          </div> -->
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <input type="submit" value="Search" name="search" id="search" class="form-control btn btn-primary">
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                    <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-performance-tab">
                      <form action="#" class="search-job">
                        <div class="row">
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="icon"><span class="icon-user"></span></div>
                                <input type="text" class="form-control" placeholder="eg. Adam Scott">
                              </div>
                            </div>
                          </div>
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="select-wrap">
                                  <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                  <select name="" id="" class="form-control">
                                    <option value="">Category</option>
                                    <option value="">Full Time</option>
                                    <option value="">Part Time</option>
                                    <option value="">Freelance</option>
                                    <option value="">Internship</option>
                                    <option value="">Temporary</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <div class="icon"><span class="icon-map-marker"></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Location">
                              </div>
                            </div>
                          </div> -->
                          <div class="col-md">
                            <div class="form-group">
                              <div class="form-field">
                                <input type="submit" value="Search" class="form-control btn btn-primary">
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
include('connection/db.php');
if (isset($_POST['search']) or($_GET['page'])) {

  $page=$_GET['page'];
  if($page==""){
    $page1=0;
  $keyword=$_POST['key'];
  $category=$_POST['category'];
  }
  else{
    $keyword=$_GET['keyword'];
    $category=$_GET['category'];
    $page1=($page*3)-3;
    
  }

  
  
  $sql1="SELECT * FROM all_jobs LEFT JOIN company ON all_jobs.customer_email=company.admin where keyword LIKE '%$keyword%' OR category='$category' limit $page1,3";
$sql=mysqli_query($conn,$sql1 );
$error=mysqli_num_rows($sql1);
 
}
 
?>
<div id="id_all_j">
<section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
            
<!-- xml validation/ xml codes -->
<xml id="note">
<note>
<heading>RECENTLY ADDED JOBS</heading>
</note>
</xml>
<xml id="note" src="xmlvalidation.xml">
</xml>


            <marquee direction="right"><h2 style="color: red;" class="mb-4"><span>Recent</span> Jobs</h2></marquee>
            <!-- <br>  <br>
            <h3> <?php
            if ($error=="") {
              echo "Data Not Found!!!! ";
            }
            ?></h3> -->

          </div>
        </div>
        <div class="row">
          
          <?php 

          
          while ($row=mysqli_fetch_array($sql)) { ?>
          
          <div class="col-md-12 ftco-animate">

            <div class="job-post-item bg-white p-4 d-block d-md-flex align-items-center">

              <div class="mb-4 mb-md-0 mr-5">
                <div class="job-post-item-header d-flex align-items-center">
                  <h2 class="mr-3 text-black h3"><?php echo $row['job_title'];?></h2>
                  <div class="badge-wrap">
                   <span class="bg-primary text-white badge py-2 px-3"></span>
                  </div>
                </div>
                <div class="job-post-item-body d-block d-md-flex">
                  <div class="mr-3"><span class="icon-layers"></span> <a href="#"><?php echo $row['company_name'];?>,<?php echo $row['des'];?></a></div>
                  <div><span class="icon-my_location"></span> <span><?php echo $row['country'];?>,<?php echo $row['state'];?>,<?php echo $row['city'];?></span></div>
                </div>
              </div>

              <div class="ml-auto d-flex">
                <a href="blog-single.php?id=<?php echo $row['job_id'];?>" class="btn btn-primary py-2 mr-1">Apply Job</a>
                <a href="#" class="btn btn-secondary rounded-circle btn-favorite d-flex align-items-center icon">
                  <span class="icon-heart"></span>
                </a>
              </div>
            </div>
          </div><!-- end -->

        <?php } ?>

          
         </div> 
        
         
        </div>
        <div class="row mt-5">
          <div class="col text-center">
            <div class="block-27">
              <ul>
                <li><a href="#">&lt;</a></li>
                <?php 
                
                $sql2=mysqli_query($conn,"SELECT * FROM all_jobs LEFT JOIN company ON all_jobs.customer_email=company.admin where keyword LIKE '%$keyword%' OR category='$category'");
                $count=mysqli_num_rows($sql2);
                $a=$count/3;
                ceil($a);
                for ($b=1; $b <=$a ; $b++) { 
                ?>

               <li><a href="index.php?page=<?php echo $b;?> & keyword=<?php echo $keyword;?> & category=<?php echo $category;?>"><?php echo $b;?></a></li>
                <?php } ?>
                <li><a href="#">&gt;</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="ftco-section services-section bg-light">
      <div class="container">
        <div class="row d-flex">
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block">
              <div class="icon"><span class="flaticon-resume"></span></div>
              <div class="media-body">
                <h3 class="heading mb-3">Explore More Jobs</h3>
                <p>We become each and every piece within the game called life!</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block">
              <div class="icon"><span class="flaticon-collaboration"></span></div>
              <div class="media-body">
                <h3 class="heading mb-3">Easy To Administer Jobs</h3>
                <p>Remember, you have been criticizing yourself for years and it hasn’t worked. Try approving of yourself and see what happens..</p>
              </div>
            </div>    
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block">
              <div class="icon"><span class="flaticon-promotions"></span></div>
              <div class="media-body">
                <h3 class="heading mb-3">Top Line of Works</h3>
                <p>A positive attitude may not solve all our problems but that is the only option we have if we want to get out of problems..</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block">
              <div class="icon"><span class="flaticon-employee"></span></div>
              <div class="media-body">
                <h3 class="heading mb-3">Search Skilled Applicantes</h3>
                <p>Echo of your thoughts are more important than your actions because that has greater impact on the world.</p>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </section>

    
    <section class="ftco-section ftco-counter">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
            <span class="subheading">Job Categories</span>
            <h2 class="mb-4"><span>Current</span> Job Posts</h2>
          </div>
        </div>
     
        <div class="row">
          <div class="col-md-3 ftco-animate">
            <ul class="category">
              <li><a href="#">Web Development <span class="number" data-number="1000">0</span></a></li>
              <li><a href="#">Graphic Designer <span class="number" data-number="1000">0</span></a></li>
              <li><a href="#">Multimedia <span class="number" data-number="2000">0</span></a></li>
              <li><a href="#">Advertising <span class="number" data-number="900">0</span></a></li>
            </ul>
          </div>
          <div class="col-md-3 ftco-animate">
            <ul class="category">
              <li><a href="#">Education &amp; Training <span class="number" data-number="3500">0</span></a></li>
              <li><a href="#">System Programmer <span class="number" data-number="1560">0</span></a></li>
              <li><a href="#">Social Media <span class="number" data-number="1000">0</span></a></li>
              <li><a href="#">BackEnd Developer <span class="number" data-number="2500">0</span></a></li>
            </ul>
          </div>
          <div class="col-md-3 ftco-animate">
            <ul class="category">
              <li><a href="#">PHP Programming <span class="number" data-number="5500">0</span></a></li>
              <li><a href="#">Project Management <span class="number" data-number="2000">0</span></a></li>
              <li><a href="#">Finance Management <span class="number" data-number="800">0</span></a></li>
              <li><a href="#">System Analyst <span class="number" data-number="7000">0</span></a></li>
            </ul>
          </div>
          <div class="col-md-3 ftco-animate">
            <ul class="category">
              <li><a href="#">Web Designer <span><span class="number" data-number="8000">0</span></span></a></li>
              <li><a href="#">Customer Service <span class="number" data-number="4000">0</span></a></li>
              <li><a href="#">Marketing &amp; Sales <span class="number" data-number="3300">0</span></a></li>
              <li><a href="#">Software Development <span class="number" data-number="1356">0</span></a></li>
            </ul>
          </div>
        </div>
  
      </div>
    </section>

    
   <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_1.jpg);" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-10">
            <div class="row">
              <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                    <strong class="number" data-number="1350000">0</strong>
                    <span>Jobs</span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                    <strong class="number" data-number="40000">0</strong>
                    <span>Members</span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                    <strong class="number" data-number="30000">0</strong>
                    <span>Resume</span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18 text-center">
                  <div class="text">
                    <strong class="number" data-number="10500">0</strong>
                    <span>Company</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="ftco-section testimony-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 text-center heading-section ftco-animate">
            
            <h2 class="mb-4"><span>Happy</span> Clients</h2>
          </div>
        </div>
        <div class="row ftco-animate">
          <div class="col-md-12">
            <div class="carousel-testimony owl-carousel ftco-owl">
              <div class="item">
                <div class="testimony-wrap py-4 pb-5">
                  <div class="user-img mb-4" style="background-image: url(images/person_1.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-4">“It’s not always what is said, but what is left on the plate, that tells the true story.”</p>
                    <p class="name">Roger Scott</p>
                    <span class="position">Marketing Manager</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4 pb-5">
                  <div class="user-img mb-4" style="background-image: url(images/person_2.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-4">“Capitalize on charm by continually captivating your customer.”</p>
                    <p class="name">Roger Scott</p>
                    <span class="position">Interface Designer</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4 pb-5">
                  <div class="user-img mb-4" style="background-image: url(images/person_3.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-4">A customer talking about their experience with you is worth ten times that which you write or say about yourself.”</p>
                    <p class="name">Roger Scott</p>
                    <span class="position">UI Designer</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4 pb-5">
                  <div class="user-img mb-4" style="background-image: url(images/person_1.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    <p class="name">Roger Scott</p>
                    <span class="position">Web Developer</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4 pb-5">
                  <div class="user-img mb-4" style="background-image: url(images/person_1.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-4">“It’s not always what is said, but what is left on the plate, that tells the true story.”</p>
                    <p class="name">Roger Scott</p>
                    <span class="position">System Analyst</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    
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

    <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">About</h2>
              <p>" A clay pot sitting in the sun will always be a clay pot. It has to go through the white heat of the furnace to become porcelain.”</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Employers</h2>

<!-- XML CODES/VALIDATION -->
<xml id="note">
<note>
<b>
<text>How it works </text>
<br>
<text>Register </text>
<br>
<text>Post a Job </text>
<br>
<text>Advance Skill Search </text>
<br>
<text>Job Search </text>
<br>
<text>Faq </text>
<br>
</b>
</note>
</xml>
<xml id="note" src="xmlvalidation.xml">
</xml>

            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Workers</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">How it works</a></li>
                <li><a href="#" class="py-2 d-block">Register</a></li>
                <li><a href="#" class="py-2 d-block">Post Your Skills</a></li>
                <li><a href="#" class="py-2 d-block">Job Search</a></li>
                <li><a href="#" class="py-2 d-block">Emploer Search</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Have any Doubt?</h2>
              <div class="block-23 mb-3">
                <ul>
                  <li><span class="icon icon-map-marker"></span><span class="text">123 Unknown, Chicklandu Appartment Greater Noida, Delhi,India</span></li>
                  <li><a href="#"><span class="icon icon-phone"></span><span class="text">+911122334455</span></a></li>
                  <li><a href="#"><span class="icon icon-envelope"></span><span class="text">askanything@gmail.com</span></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icon-heart text-danger" aria-hidden="true"></i><a href="https://colorlib.com" target="_blank"></a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>
<script> 
  $(document).ready(function(){
  $("#id_all_jobs").hide();
  $("#search").click(function(e){
    
  $("#id_all_jobs").hide();
  });
  });

</script>