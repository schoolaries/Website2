<?php
include('config.php');
$session_uid='';
$session_googleCode='';
$_SESSION['uid']='';
$_SESSION['googleCode']='';
session_destroy();
if(empty($session_uid) && empty($_SESSION['uid']))
{
	header("Location: index.php");
}
header("Location: index.php");
?>