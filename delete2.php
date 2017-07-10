<?php
$connect=mysqli_connect("localhost","root","","demos");
if(isset($_GET['operation'])){
	if($_GET['operation']=="delete")
	{
		$query=$connect->prepare("delete from users where uid=".$_GET['uid']);
		if($query->execute())
		{
			echo "<center>Record Deleted!</center><br>";
		}
	}
}
header("Location: viewausers.php");
?>

