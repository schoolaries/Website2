<?php
session_start();
/* DATABASE CONFIGURATION */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123');
define('DB_DATABASE', 'Shop');
define("BASE_URL", "http://http://ec2-34-211-48-35.us-west-2.compute.amazonaws.com/"); // Eg. http://yourwebsite.com


function getDB() //connect to database
{
	$dbhost=DB_SERVER; //localhost
	$dbuser=DB_USERNAME; //root
	$dbpass=DB_PASSWORD; // "123"
	$dbname=DB_DATABASE; // shop
	try {
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
		$dbConnection->exec("set names utf8");
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}
	catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
}
?>
<?php

//MySql 
$db_username 	= 'root';
$db_password 	= '123';
$db_name 		= 'Shop';
$db_host 		= 'localhost';

//connection to MySql						
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);						
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>
