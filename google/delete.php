<?php
session_start();

$conn = mysqli_connect("localhost","root","","demos");
$storedid = $_SESSION['uid'];
if(isset($_POST["deleteAcc"])) {
	if($_POST["deleteAcc"]=="yes") {
		$username = $_POST["usernameEmail"];
		$password = hash('sha256', $_POST['password']);
		$salt= md5($username);
		$spwd = "{$password}{$salt}";
		
		$query=$conn->prepare('delete from users WHERE uid=? and username=? and password=?');
		$query->bind_param('iss',$storedid,$username,$spwd);
		if($query->execute()){
			session_destroy();
			header('Location: index.php');
		}
	}
}

?>