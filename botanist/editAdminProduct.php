<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="navbarAdmin.php" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">	
			<a href="navbarAdmin.php#manageUsers" class="w3-left">Manage Users</a>
			<a href="navbarAdmin.php#manageProducts" class="w3-left">Manage Products</a>
			<a href="navbarAdmin.php#editProfile" class="w3-left">Edit Profile</a>
			<a href="navbarAdmin.php#viewFeedbacks" class="w3-left">View Feedbacks</a>
			<a href="signedout.php" class="w3-left">Logout</a>
		</li>
	
		<marquee direction="left" speed="normal" behavior="loop" >Edit Products</marquee> 
	</ul>
</div>
<br><br>

<?php 
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/editAdminProduct.php");
	die();
}

$err = "false";
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
		$query = $con1->prepare ( "select role from user where UserID=?" );
		$query->bind_param('s',$currID);
		$query->execute ();
		$query->bind_result ($Role);
		while ( $query->fetch () ) {
			$role = $Role;
		}
	}
}

if($role != "Admin") {
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

if ($autherror == "true") {
	$err = "true";
}

if ($err == "true") {
	header("Location: https://localhost/botanist/signedout.php");
}
?>
<form method="post" action="navbarAdmin.php">
<table align="center" border="0">
<tr>
<td>Product Code:</td>
<td><input type="text" name="ProductCode" value="<?php echo $_GET['ProductCode']; ?>"/></td>
</tr>
<tr>
<td>Product Name:</td>
<td><input type="text" name="ProductName" value="<?php echo $_GET['ProductName']; ?>"/></td>
</tr>
<tr>
<td>Product Description:</td>
<td><input type="text" name="ProductDesc" value="<?php echo $_GET['ProductDesc']; ?>"/></td>
</tr>
<tr>
<td>Product Image Directory:</td>
<td><input type="text" name="ProductImgName" value="<?php echo $_GET['ProductImgName']; ?>"/></td>
</tr>
<tr>
<td>Price:</td>
<td><input type="text" name="Price" value="<?php echo $_GET['Price']; ?>"/></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right">
<input type="hidden" name="ProductID" value="<?php echo $_GET['ProductID'] ?>" />
<input type="hidden" name="updateProduct" value="yes" />
<input type="submit" value="Update Record"/>
</td>
</tr>
</table>
</form>
</body>
<html>