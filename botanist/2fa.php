<html>
<title>Botanist Floral</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">

<body>
<?php 
include 'function.php';
require_once('dbconnect.php');

/*
Add the code below for any forms linking to 2fa.php

<input type="hidden" id="pcsrf" name="pcsrf" value=<?php echo $_SESSION['Token'];?> />

*/

if(!isset($_SERVER['HTTPS'])){ //Ensures that the user browse in https
	header("Location: https://localhost/botanist/2fa.php");
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
echo "<br><br><br><br>";

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

if($role=="Member"){
	$nnerror = "True";
}
if($role=="Guest"){
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
if(!empty($_SESSION['Token'])){ 
	if(isset($_POST['pcsrf'])){
		if($_SESSION['Token'] != $_POST['pcsrf'] ){
			echo "CSRF !=";
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

if ($nnerror == "True") {
	$autherror = "true";
}

if ($autherror == "true") {
		$err = "true";
}
if ($CSRFError == "True") {
		$err = "true";
}

if ($err == "false") {
	if (!empty($_POST['codereq'])) {
		//SMS 2fa
		$UID = $_SESSION['LogID'];
		$vcode = generateRandomString ( "6" );
		$UID = $_SESSION['LogID'];
		echo "<br>";
		$NewTimecreated = time();
		$query = $con1->prepare ( "INSERT INTO `verification_database`(`UserID`, `Timecreated`, `Secretkey`) VALUES (?,?,?)" );
		$query->bind_param('sss',$UID , $NewTimecreated, $vcode);
		//echo this to know the code is in database but is not for knowing if the sms sent out
		if ($query->execute ()) {
			echo "<p>Code Sent!</p><br>";
		}
		//Get contact number
		$query =$con2->prepare ( "select Contact from user where UserID=?" );
		$query->bind_param('s',$UID);
		$query->execute ();
		$query->bind_result ($Contact);
		while ( $query->fetch () ) {
			$No = $Contact;
		}
		// Got this account from seniors, the account credits expire on 3rd March 2017 (400+SMS credits left)
		$username = urlencode("WinetasterSG");
		$password = urlencode("WineTaster2016!");
		$dstno = "65" . $No;
		//echo $dstno;
		$msg = "Verification Code: " . $vcode;
		$senderid = "Botanist";
		//echo $msg;
		//echo $sendlink;
		$sendlink = "http://www.isms.com.my/isms_send.php?un=".urlencode($username)."&pwd=".urlencode($password)."&dstno=".$dstno."&msg=".urlencode($msg)."&type=1"."&sendid=".$senderid;
		//send sms
		fopen($sendlink, "r");
		echo "<p>Please check your phone (".$dstno.")</p>";
	}
}
else {
	header("Location: https://localhost/botanist/signedout.php");
}
?>
<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="navbarAdmin.php" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">	
			<a href="#manageUsers" class="w3-left">Manage Users</a>
			<a href="#manageProducts" class="w3-left">Manage Products</a>
			<a href="#editProfile" class="w3-left">Edit Profile</a>
			<a href="#viewFeedbacks" class="w3-left">View Feedbacks</a>
			<a href="signedout.php" class="w3-left">Logout</a>
		</li>
		
		<marquee direction="left" speed="normal" behavior="loop" >This is the administrator panel of Botanist Floral!</marquee> 
	</ul>
</div>

<form name="codegen" action="2fa.php" method="post">
<input type="submit" id="codereq" name="codereq" value="Request for OTP">
<input type="hidden" id="pcsrf" name="pcsrf" value=<?php echo $_SESSION['Token'];?> />
</form>


<form name="2fa" action="2fa2.php" method="post">
Verification Code: <input type="text" id="vcode" name="vcode" />
<input id="submit" type="submit" name="submit" value="Submit" />
<input type="hidden" id="pcsrf" name="pcsrf" value=<?php echo $_SESSION['Token'];?> />
</form>

<form action="https://localhost/botanist/navbarAdmin.php">
    <input type="submit" value="Back to Admin Page" style="height:50px;width:200px"/>
</form>

</body>
</html>
