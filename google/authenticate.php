<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);
 
if($_POST['code'])
{
	$code=$_POST['code'];
	$secret=$userDetails->google_auth_code;
	require_once 'GoogleAuthenticator.php';
	$ga = new GoogleAuthenticator();
	$checkResult = $ga->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance

	if ($checkResult)
	{
		$_SESSION['googleCode']=$code;
		header('Location:home.php');


	}
	else
	{
		echo 'FAILED';
	}

}

?>