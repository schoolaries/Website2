<?php

require_once('dbconnect.php');

function test_input($data) { // Prevents SQL Injection
	$con = mysqli_connect("localhost","bobo","chimpanxi","botanist");
	//check conn
	if (mysqli_connect_errno()) {
		echo "Conn fail, Err code: " . mysqli_connect_error();
	}
	
	$data = trim ( $data );
	$data = stripslashes ( $data );
	$data = htmlspecialchars ( $data );
	$data = mysqli_real_escape_string($con, $data);
	return $data;
}

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen ( $characters );
	$randomString = '';
	for($i = 0; $i < $length; $i ++) {
		$randomString .= $characters [rand ( 0, $charactersLength - 1 )];
	}
	return $randomString;
}

//Prevent session hijacking
function preventHijacking() 
{	//checks if first time use, if so will trigger the session to store the current ip and browser agent
	if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent'])){
		return false;
	} 
	//checks the IP against current IP
	if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']){
		return false;
	} 
	//checks the browser agent against current one
	if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']){
		return false;
	}
	return true;
}

function verifypw( $mypassword2, $password, $base64salt)
{
	// base64 decode salt first
	$salt = base64_decode($base64salt);
	// concatenate salt and user entered password before validation
	$validateHash = $salt.$mypassword2;
	
	// hash the concatenated salt and password
	for ($i=0; $i<50000; $i++)
	{
		$validateHash = hash("sha256", $validateHash);
	}
	
	// validate storedHash password from database against user-entered validateHash password
	if ($validateHash == $password) {
		return  true;
	}
	else {
		return false;
	}
}


?>