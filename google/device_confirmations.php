<?php
include('config.php'); //connect database

if(empty($_SESSION['uid'])) //if uid is empty, return to index
{
	header("Location: index.php");
}

include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);
$secret=$userDetails->google_auth_code;
$email=$userDetails->email;

require_once 'GoogleAuthenticator.php';

$ga = new GoogleAuthenticator();

$qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret,'2FA Website Demo');

 //Ensures that the user browse in https
/*if(!isset($_SERVER['HTTP'])){
	header("Location: http://ec2-34-211-115-232.us-west-2.compute.amazonaws.com/google");
	die();
}*/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Authentication</title>
    <link rel="stylesheet" type="text/css" href="style.css" charset="utf-8" />
</head>
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.w3-sidenav a {font-family: "Roboto", sans-serif}
body,h1,h2,h3,h4,h5,h6,.w3-wide {font-family: "Montserrat", sans-serif;}
</style>
<header class="w3-container w3-large" style="background-color: #e60000; color: white; height: 90px">
   <h2 align="center" style="padding-top: 15px">Welcome to Liquorholic</h2>
</header>
<body style="background-color: #e60000">
	<div id="container" align="center" style="background-color: white; opacity:0.9">
		<br>
		<h1>2-Step Verification using Google Authenticator</h1>
		<div id='device'>

		<p>Enter the verification code generated by Google Authenticator app on your phone.</p>
		<div id="img">
			<img src='<?php echo $qrCodeUrl; ?>' />
		</div>

		<form method="post" action="authenticate.php" style="padding-top: 10px">
			<label>Enter Google Authenticator Code</label>
			<input type="text" name="code" />
			<button type="submit" class="w3-btn w3-red">Authenticate</button>
		</form>
		<br>
		<br>
	</div>
</body>
</html>