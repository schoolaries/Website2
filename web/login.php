<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['login'])) 
{
echo "123";
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{
// Define $username and $password
$email=$_POST['email'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("localhost", "root", "123");
// Selecting Database
$db = mysql_select_db("Shop", $connection);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from user where password='$password' AND email='$email'", $connection);
$rows = mysql_num_rows($query);
if ($rows == 1) {
echo "hello";
$_SESSION['login_user']=$email; // Initializing Session
header("location: profile.php"); // Redirecting To Other Page
} else {
$error = "Email or Password is invalid";
}
mysql_close($connection); // Closing Connection
}
}
?>
