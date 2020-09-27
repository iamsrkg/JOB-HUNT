<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.2/js/bootstrap.min.js"></script>
</head>
<body>


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'email/vendor/autoload.php';
$to=$_POST['to'];
$from=$_POST['from'];
$body=$_POST['body'];
$id=$_POST['id'];
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 1;                     
    $mail->isSMTP();                                           
    $mail->Host      = 'smtp.gmail.com';                    
    $mail->SMTPAuth  = true;                                   
    $mail->Username  = $from;                     
    $mail->Password  = 'Krishna@1977';                              
    $mail->SMTPSecure= 'tls';
    $mail->Port      = 587;

//Recipient

    $mail->setFrom($to, '@');
    $mail->addAddress('dewuma@gmail.com', 'Joe User');  
	    
//Content
    $mail->isHTML(true);                               
    $mail->Subject = 'The Subject';
    $mail->Body    = $body;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo '<h1>Message has been sent</h1>';
    include('connection/db.php');
    $query=mysqli_query($conn,"delete from job_apply where id='$id'");

     echo "<script>
     window.setTimeout(function(){
     	window.location.href = 'http://localhost/job_portal/Admin/apply_jobs.php';}, 5000);
     	</script>"; 



} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
<button href="http://localhost/job_portal/Admin" class="btn btn-success btn-lg">Back to the page</button>



</body>
</html>