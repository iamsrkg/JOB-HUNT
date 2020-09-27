<?php
include('connection/db.php');
 $Company=$_POST['Company'];
 $Description=$_POST['Description'];
 $Admin=$_POST['admin'];
 

$query=mysqli_query($conn,"insert into Company(company_name,des,admin)values('$Company','$Description','$Admin')");
if($query){
	echo "Data has been inserted successfully ";
}else{
	echo "Some error occur";
}
?>

