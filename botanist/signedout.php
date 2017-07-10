<?php
session_name('id');
session_start();
session_destroy();
$_SESSION['LogRole'] = "";
$_SESSION['LogID'] = "";
$_SESSION['LogEmail'] = "";
$_SESSION['LogUser'] = "";

session_start ();
unset($_COOKIE['id']);
setcookie('id', null, -1, '/');
header("Location: https://localhost/botanist/homepage.php");
die();
?>
<html>
<head>
<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">
<title>Signed out</title></head>
<br><br><br>
</html>