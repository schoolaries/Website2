<html>
<body>

<?php
include 'function.php';
require_once('dbconnect.php');

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){ 
	header("Location: https://localhost/botanist/Member2fa2.php");
	die();
}

session_name('id');
session_start();

$err = "false";
$CSRFError = "False";
$autherror = "false";
$nnerror = "False";
//Prevent Session Forging
$role = "Guest";
$SessionError = "";

//Role Check
if(isset($_SESSION['LogID'])){ 
	//Attackers must know both UserID and salt to be able to session hijack
	if(isset($_SESSION['Token'])){
		$currID = test_input($_SESSION['LogID']); 
		$query = $con1 ->prepare ( "select role from user where UserID=?" ); 
		$query->bind_param('s',$currID);
		$query->execute ();
		$query->bind_result ($Role);
		while ( $query->fetch () ) {
			$role = $Role;
		}
	}
}

if($role != "Member"){
	$nnerror = "True";
}
//Check if user is logged in
if(isset($_SESSION['Token'])){ 
	if(!preventHijacking()) {
		$SessionError = "Hijack";
	}
}

if($SessionError == "Hijack"){
	//Terminates the compromised session ID
	session_destroy();
	session_commit();
	//Generate new Session ID
	session_start();
	//store current IP into session variable
	$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
	//store current browser into session variable
	$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
	//Redirect the user
	header("Location: https://localhost/botanist/signedout.php");
}

//Check if logged in
if ($nnerror == "True") { 
	$autherror = "true";
}


//Set timeout duration
$timeoutduration = 300;
if(isset($_SESSION['LAST_ACTIVITY'])){
	//check if session is destroyed
	if(time() - $_SESSION['LAST_ACTIVITY'] > $timeoutduration){ 
		//Destroy sessions
		session_unset(); 
		session_destroy();
		echo("<meta http-equiv='refresh' content='0'>");
		$autherror = "true";
	}
	else{
		$_SESSION['LAST_ACTIVITY'] = time();
	}
}
else{
	$_SESSION['LAST_ACTIVITY'] = time();
}

//checks for CSRF
if(isset($_SESSION['Token'])){ 
	if(isset($_POST['pcsrf'])){
		if($_SESSION['Token'] != $_POST['pcsrf'] ){
			echo "CSRF Positive";
			$CSRFError = "True";
		}
	}else{
		echo "CSRF Positive";
		$CSRFError = "True";
	}
}
else {
	echo "No token, CSRF Positive";
	$CSRFError = "True";
}

if ($autherror == "true") {
		$err = "true";
}
else {
	if ($CSRFError == "True") {
		$err = "true";
	}
}

//redirect user
if ($err == "true") {
	header("Location: https://localhost/botanist/signedout.php");
}
//No error
else {
	if(isset($_POST['submit'])){
		$secret = $timecreated = "";
		$error = "false";
		$error1 = "false";
		$vcode = test_input($_POST ['vcode']);
		$UserID = $_SESSION['LogID'];
		$query = $con1->prepare ( "select * from verification_database where UserID=?" );
		$query->bind_param('s',$UserID);
		$query->execute ();
		$query->bind_result ($UserID,$Timecreated,$Secretkey);
		while ( $query->fetch () ) {
			$timecreated = $Timecreated;
			$secret = $Secretkey;
		}
		if($vcode != $secret) {
			echo "<br> Verification code is wrong! Try again!";
			$error = "true";
		}
		$keyage = time() - $timecreated;
		$tenmins = 600; 
		if($keyage > $tenmins) {
			$error = "true";
		}
		if ($error == "true" || $error1 == "true") {
			echo "<br> Error authenticating the code";
		}
		else {
			//manageProduct.php set session check to false then redirect
			$_SESSION['ManageProfile'] = "True";
			$_SESSION['2fa'] = "Succ";
			header("Location: https://localhost/botanist/navbarMember.php");
		}
	}
}
?>
<form action="https://localhost/botanist/navbarMember.php">
    <input type="submit" value="Back to homepage" style="height:50px;width:200px"/>
</form>
</body>
</html>