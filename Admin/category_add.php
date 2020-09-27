<?php
include('connection/db.php');
 $category=$_POST['category'];
 $Description=$_POST['Description'];
 

$query=mysqli_query($conn,"insert into job_category(category,des)values('$category','$Description')");
if($query){
	echo "Data has been inserted successfully ";
}else{
	echo "Some error occur";
}
?>

