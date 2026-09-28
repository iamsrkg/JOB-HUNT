<?php
require_once __DIR__ . '/connection/db.php';
require_user();

// Handle the application before any HTML, so errors and redirects work.
$result = null;   // ['ok' => bool, 'message' => string]
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    require_csrf();

    $firstName = trim((string) ($_POST['first_name'] ?? ''));
    $lastName = trim((string) ($_POST['last_name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = preg_replace('/\D+/', '', (string) ($_POST['phone'] ?? ''));
    $dob = (string) ($_POST['dob'] ?? '');
    $jobId = (int) ($_POST['id_job'] ?? 0);

    $dobDate = DateTime::createFromFormat('Y-m-d', $dob);
    if ($firstName === '' || $lastName === '' || mb_strlen($firstName) > 100 || mb_strlen($lastName) > 100) {
        $result = ['ok' => false, 'message' => 'Please enter your first and last name.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = ['ok' => false, 'message' => 'Please enter a valid email address.'];
    } elseif (strlen($phone) < 7 || strlen($phone) > 15) {
        $result = ['ok' => false, 'message' => 'Please enter a valid phone number.'];
    } elseif (!$dobDate || $dobDate->format('Y-m-d') !== $dob) {
        $result = ['ok' => false, 'message' => 'Please enter your date of birth.'];
    } elseif (!db("SELECT 1 FROM all_jobs WHERE job_id = ?", [$jobId])->fetch_row()) {
        $result = ['ok' => false, 'message' => 'That job no longer exists.'];
    } elseif (db("SELECT 1 FROM job_apply WHERE email = ? AND id_job = ?", [$email, $jobId])->fetch_row()) {
        $result = ['ok' => false, 'message' => 'You have already applied for this job.'];
    } else {
        // Random name + content-checked type: the uploader never controls the path or extension.
        $stored = store_upload($_FILES['file'] ?? [], APP_ROOT . '/files', RESUME_TYPES, 5 * 1024 * 1024, $uploadError);
        if ($stored === null) {
            $result = ['ok' => false, 'message' => $uploadError];
        } else {
            db("INSERT INTO job_apply (first_name, last_name, dob, file, id_job, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?)",
               [$firstName, $lastName, $dob, $stored, $jobId, $email, $phone]);
            $result = ['ok' => true, 'message' => 'Your application has been submitted.'];
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Cover Template for JOBHUNT</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style type="text/css">
   
a,
a:focus,
a:hover {
  color: #fff;
}

/* Custom default button */
.btn-secondary,
.btn-secondary:hover,
.btn-secondary:focus {
  color: #333;
  text-shadow: none; /* Prevent inheritance from `body` */
  background-color: #fff;
  border: .05rem solid #fff;
}


/*
 * Base structure
 */

html,
body {
  height: 100%;
  background-color: #00FFFF;
}

body {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-pack: center;
  -webkit-box-pack: center;
  justify-content: center;
  color: #fff;
  text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
  box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
}

.cover-container {
  max-width: 42em;
}



.cover-heading{
	padding-bottom: 50px;
	padding-bottom:  5px;

}

/*
 * Header
 */
.masthead {
  margin-bottom: 2rem;
}

.masthead-brand {
  margin-bottom: 0;
}

.nav-masthead .nav-link {
  padding: .25rem 0;
  font-weight: 700;
  color: rgba(255, 255, 255, .5);
  background-color: transparent;
  border-bottom: .25rem solid transparent;
}

.nav-masthead .nav-link:hover,
.nav-masthead .nav-link:focus {
  border-bottom-color: rgba(255, 255, 255, .25);
}

.nav-masthead .nav-link + .nav-link {
  margin-left: 1rem;
}

.nav-masthead .active {
  color: #fff;
  border-bottom-color: #fff;
}

@media (min-width: 48em) {
  .masthead-brand {
    float: left;
  }
  .nav-masthead {
    float: right;
  }
}


/*
 * Cover
 */
.cover {
  padding: 10 1.5rem;
}
.cover .btn-lg {
  padding: .75rem 1.25rem;
  font-weight: 700;
}


/*
 * Footer
 */
.mastfoot {
  color: rgba(255, 255, 255, .5);
}
    </style>
  </head>

  <body class="text-center">

    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
      <!-- <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand">Cover</h3>
          <nav class="nav nav-masthead justify-content-center">
            <a class="nav-link active" href="#">Home</a>
            <a class="nav-link" href="#">Features</a>
            <a class="nav-link" href="#">Contact</a>
          </nav>
        </div>
      </header> -->

      <main role="main" class="inner cover">
        <h1 class="cover-heading" style="color:silver">Cover your page.</h1>

<?php if ($result !== null) { ?>
        <p class="lead" style="color:<?php echo $result['ok'] ? 'black' : 'darkred'; ?>"><?php echo e($result['message']); ?></p>
<?php } ?>


        


        <p class="lead">
          <a href="index.php" class="btn btn-lg btn-secondary">Back to jobs</a>
        </p>
      </main>

      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p style="color:black">Cover template for <a href="">JOBHUNT</a>, by <a href="">@Sudheer Gupta</a>.</p>
        </div>
      </footer>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>
