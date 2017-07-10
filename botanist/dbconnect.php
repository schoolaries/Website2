<?php
function db_connect1() {

	// Define connection as a static variable, to avoid connecting more than once
	static $con1;

	// Try and connect to the database, if a connection has not been established yet
	if(!isset($con1)) {
		// Load configuration as an array. Use the actual location of your configuration file
		$config = parse_ini_file('C:\xampp\htdocs\botanist\config.ini');
		$con1 = mysqli_connect($config['servername'],$config['username1'],$config['password1'],$config['dbname']);
	}

	// If connection was not successful, handle the error
	if($con1 === false) {
		// Handle error - notify administrator, log to a file, show an error screen, etc.
		return mysqli_connect_error();
	}
	return $con1;

}

function db_connect2() {

	// Define connection as a static variable, to avoid connecting more than once
	static $con2;

	// Try and connect to the database, if a connection has not been established yet
	if(!isset($con2)) {
		// Load configuration as an array. Use the actual location of your configuration file
		$config = parse_ini_file('./config.ini');
		$con2 = mysqli_connect($config['servername'],$config['username2'],$config['password2'],$config['dbname']);
	}

	// If connection was not successful, handle the error
	if($con2 === false) {
		// Handle error - notify administrator, log to a file, show an error screen, etc.
		return mysqli_connect_error();
	}
	return $con2;

}

// Connect to the database
$con1 = db_connect1();
$con2 = db_connect2();

// Check connection
if ($con1->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
if ($con2->connect_error) {
	die("Connection failed: " . $connection->connect_error);
}
?> 