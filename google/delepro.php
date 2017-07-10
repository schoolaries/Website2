<?php
$connect=mysqli_connect("localhost","root","P@ssw0rd!","demos");
if(isset($_GET['operation'])){
	if($_GET['operation']=="delete")
	{
		$query=$connect->prepare("delete from products where id=".$_GET['id']);
		if($query->execute())
		{
			echo "<center>Record Deleted!</center><br>";
		}
	}
}
header("Location: viewaccount.php");
?>