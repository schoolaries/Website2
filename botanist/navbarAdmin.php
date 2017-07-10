<!DOCTYPE html>
<html>
<head>
<title>Admin Page</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<?php

//START HERE
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){ 
	header("Location: https://localhost/botanist/navbarAdmin.php");
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

//UNTIL HERE +=================

//operation for deleting users
if(isset($_GET['operation']))
{
	if($_GET['operation']=="deleteUser")
	{
		$UserID = $_GET['UserID'];
		$query=$con1->prepare("delete from user where UserID=?");
		$query->bind_param('s', $UserID);
		if($query->execute())
		{
			echo "<center>Record Deleted!</center><br>";
		}
	}
}
	
//operation for updating users
if(isset($_POST["updateUser"]))
{
	if($_POST["updateUser"]=="yes")
	{
		$firstname = $lastname = $gender = $email = $username = $contact = $birthdate = "";
		$err = "no";
		
		//Check Fname
		if (empty($_POST["FirstName"])) {
			$err = "yes";
		} else {
			$firstname = test_input($_POST["FirstName"]);
			// check if Fname only contains letters
			if (!preg_match("/^[a-zA-Z]*$/",$firstname)) {
				$err = "yes";
			}
		}
		
		//Check Lname
		if (empty($_POST["LastName"])) {
			$err = "yes";
		} else {
			$lastname = test_input($_POST["LastName"]);
			// check if Lname only contains letters
			if (!preg_match("/^[a-zA-Z]*$/",$lastname)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Gender"])) {
			$err = "yes";
		} else {
			$gender = test_input($_POST["Gender"]);
			if (!preg_match("/^[mf]{1}$/", $gender)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Email"])) {
			$err = "yes";
		} else {
			$email = test_input($_POST["Email"]);
			// check if e-mail address is valid, regex expression below is the same one used in filter_var() based on a regex by Michael Rushton
			// /^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD
		
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$err = "yes";
			}
		}
		
		//check username
		if (empty($_POST["Username"])) {
			$err = "yes";
		} else {
			$username = test_input($_POST["Username"]);
			// check if username does not have whitespace
			if (!preg_match("/^[^\s]*$/",$username)) {
				$err = "yes";
			}
		}
		
		
		if (empty($_POST["Contact"])) {
			$err = "yes";
		} else {
			$contact = test_input($_POST["Contact"]);
			// check if contact only contains 8 numbers
			if (!preg_match("/^([0-9]){8}$/",$contact)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Birthdate"])) {
			$err = "yes";
		}
		else {
			$birthdate = test_input($_POST["Birthdate"]);
		}
		
		if ($err == "no") {
			$role=$_SESSION['LogRole'];
			$uid=test_input($_POST['UserID']);
			
			$query=$con1->prepare("update user set FirstName=?, LastName=?, Gender=?, Email=?, Username=?, Contact=?, Birthdate=?, Role=? where UserID=?");
			$query->bind_param('sssssisss', $firstname, $lastname, $gender, $email, $username, $contact, $birthdate, $role, $uid);//bind the parameters
			
			if($query->execute()){
				echo "<center>Record Updated!</center><br>";
			}
		}
		else {
			echo "Invalid Parameters!";
		}
	}
}
	
//operation for deleting products
if(isset($_GET['operation']))
{
	if($_GET['operation']=="deleteProduct")
	{
		$ProductID = $_GET['ProductID'];
		$query=$con1->prepare("delete from products where ProductID=?");
		$query->bind_param('i', $ProductID);
	
		if($query->execute())
		{
			echo "<center>Record Deleted!</center><br>";
		}
	}
}
	
//operation for updating products
if(isset($_POST["updateProduct"]))
{
	if($_POST["updateProduct"]=="yes")
	{
		$ProductCode = $ProductName = $ProductDesc = $ProductImgName = $Price = "";
		$err = "no";
		
		if (empty($_POST["ProductCode"])) {
			$err = "yes";
		} else {
			$ProductCode = test_input($_POST["ProductCode"]);
			if (!preg_match("/^[a-zA-Z0-9]*$/",$ProductCode)) {
				$err = "yes";
			}
		}
		if (empty($_POST["ProductName"])) {
			$err = "yes";
		} else {
			$ProductName = test_input($_POST["ProductName"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$ProductName)) {
				$err = "yes";
			}
		}
		if (empty($_POST["ProductDesc"])) {
			$err = "yes";
		} else {
			$ProductDesc = test_input($_POST["ProductDesc"]);
		}
		
		$ProductImgName=test_input($_POST["ProductImgName"]);
		
		if (empty($_POST["Price"])) {
			$err = "yes";
		} else {
			$Price = test_input($_POST["Price"]);
			if (!preg_match("/^[0-9]*[.]?[0-9]{0,2}$/",$Price)) {
				$err = "yes";
			}
		}
				
		
		if ($err == "no") {
			$pid=test_input($_POST["ProductID"]);
			
			$query=$con1->prepare("update products set ProductCode=?, ProductName=?, ProductDesc=?, ProductImgName=?, Price=? where ProductID=?");
			$query->bind_param('ssssdi', $ProductCode, $ProductName, $ProductDesc, $ProductImgName, $Price, $pid);//bind the parameters
				
			if($query->execute())
			{
				echo "<center>Record Updated!</center><br>";
			}
		}
	}
}
	
	//operation for deleting feedbacks
if(isset($_GET['operation'])) {
	if($_GET['operation']=="deleteFeedback") {
		$FeedbackID = $_GET['FeedbackID'];
		$query=$con1->prepare("delete from suggestion where FeedbackID=?");
		$query->bind_param('i', $FeedbackID);
		if($query->execute()) {
			echo "<center>Record Deleted!</center><br>";
		}
	}
}

if (isset($_SESSION['2fa'])){
	echo "<center>Authenticated! You may proceed to click link.</center>";
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
	
		<marquee direction="left" speed="normal" behavior="loop" >Welcome back <?php echo $_SESSION['LogFName']." ".$_SESSION['LogLName'];?>! This is the administrator panel of Botanist Floral!</marquee> 
	</ul>
</div>

<!-- Manage Users Section -->
<div class="w3-container w3-padding-32" id="manageUsers">
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">Manage Users</h2>
	

<?php
	$query=$con1->prepare("select * from user");
	$query->execute();
	$query->bind_result($UserID, $FirstName, $LastName, $Gender, $Email, $Username, $storedHash, $base64salt, $Contact, $Birthdate, $Role);

	echo "<table align='left' border='1'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>First Name</th>";
	echo "<th>Last Name</th>";
	echo "<th>Gender</th>";
	echo "<th>Email</th>";
	echo "<th>Username</th>";
	echo "<th>Contact</th>";
	echo "<th>Birthdate</th>";
	echo "<th>Role</th>";
	echo "</tr>";
	
	while($query->fetch())
	{
		echo "<tr>";
		echo "<td>".$UserID."</td>";
		echo "<td>".$FirstName."</td>";
		echo "<td>".$LastName."</td>";
		echo "<td>".$Gender."</td>";
		echo "<td>".$Email."</td>";
		echo "<td>".$Username."</td>";
		echo "<td>".$Contact."</td>";
		echo "<td>".$Birthdate."</td>";
		echo "<td>".$Role."</td>";
		if ($UserID == $_SESSION['LogID']) {
			echo "<td></td>";
		}
		else {
		echo "<td><a href='navbarAdmin.php?operation=deleteUser&UserID=".$UserID."'>Delete</a></td>";
		}
		echo "</tr>";	
	}
		echo "</table>";
?>
</div>

<!-- Manage Products Section -->
<div class="w3-container w3-padding-32" id="manageProducts">
<br />
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">Manage Products</h2>
	
<?php
	$query=$con1->prepare("select * from products");
	$query->execute();
	$query->bind_result($ProductID, $ProductCode, $ProductName, $ProductDesc, $ProductImgName, $Price);

	echo "<table align='left' border='1'>";
	echo "<tr>";
	echo "<th>Product ID</th>";
	echo "<th>Product Code</th>";
	echo "<th>Product Name</th>";
	echo "<th>Product Description</th>";
	echo "<th>Product Image Directory</th>";
	echo "<th>Price</th>";
	echo "</tr>";
	
	while($query->fetch())
	{
		echo "<tr>";
		echo "<td>".$ProductID."</td>";
		echo "<td>".$ProductCode."</td>";
		echo "<td>".$ProductName."</td>";
		echo "<td>".$ProductDesc."</td>";
		echo "<td>".$ProductImgName."</td>";
		echo "<td>".$Price."</td>";
		if ($_SESSION['ManageProduct'] == "False") {
			echo "<form method='post' action='2fa.php'>";
			echo "<input type='hidden' id='pcsrf' name='pcsrf' value=". $_SESSION['Token'].">";
			echo "<td><input type='submit' name='submit' value='Update'/></td>";
			echo "<td><input type='submit' name='submit' value='Delete'/></td>";
			echo"</form>";
		}
		else {
			echo "<td><a href='editAdminProduct.php?operation=updateProduct&ProductID=".$ProductID."&ProductCode=".$ProductCode."&ProductName=".$ProductName."&ProductDesc=".$ProductDesc."&ProductImgName=".$ProductImgName."&Price=".$Price."'>Update</a></td>";
			echo "<td><a href='navbarAdmin.php?operation=deleteProduct&ProductID=".$ProductID."'>Delete</a></td>";	
		}
		echo "</tr>";
	}
		echo "</table>";
?>
<!-- Send hidden value to prevent CSRF -->
<form method="post" action="2fa.php">
<input type="hidden" id="pcsrf" name="pcsrf" value=<?php echo $_SESSION['Token'];?> />
</form>

</div>

<!-- Edit Profile Section -->
<div class="w3-container w3-padding-32" id="editProfile">
<br />
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">Edit Profile</h2>
	
<?php
	$logID = $_SESSION['LogID'];
	$query=$con1->prepare("select * from user where UserID='$logID'");
	$query->execute();
	$query->bind_result($UserID, $FirstName, $LastName, $Gender, $Email, $Username, $storedHash, $base64salt, $Contact, $Birthdate, $Role);

	echo "<table align='left' border='1'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>First Name</th>";
	echo "<th>Last Name</th>";
	echo "<th>Gender</th>";
	echo "<th>Email</th>";
	echo "<th>Username</th>";
	echo "<th>Contact</th>";
	echo "<th>Birthdate</th>";
	echo "<th>Role</th>";
	echo "</tr>";

	while($query->fetch())
	{
		echo "<tr>";
		echo "<td>".$UserID."</td>";
		echo "<td>".$FirstName."</td>";
		echo "<td>".$LastName."</td>";
		echo "<td>".$Gender."</td>";
		echo "<td>".$Email."</td>";
		echo "<td>".$Username."</td>";
		echo "<td>".$Contact."</td>";
		echo "<td>".$Birthdate."</td>";
		echo "<td>".$Role."</td>";
		echo "<td><a href='editAdminProfile.php?operation=updateUser&UserID=".$UserID."&FirstName=".$FirstName."&LastName=".$LastName."&Gender=".$Gender."&Email=".$Email."&Username=".$Username."&Contact=".$Contact."&Birthdate=".$Birthdate."&Role=".$Role."'>Update</a></td>";
		echo "</tr>";	
	}
		echo "</table>";
?>
</div>

<!-- View Feedbacks Section -->
<div class="w3-container w3-padding-32" id="viewFeedbacks">
<br />
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">View Feedbacks</h2>
	
<?php
	$query=$con1->prepare("select * from suggestion");
	$query->execute();
	$query->bind_result($FeedbackID, $Email, $Comment, $Timestamp);

	echo "<table align='left' border='1'>";
	echo "<tr>";
	echo "<th>Feedback ID</th>";
	echo "<th>Email</th>";
	echo "<th>Comment</th>";
	echo "<th>Timestamp</th>";
	echo "</tr>";
	
	while($query->fetch())
	{
		echo "<tr>";
		echo "<td>".$FeedbackID."</td>";
		echo "<td>".$Email."</td>";
		echo "<td>".$Comment."</td>";
		echo "<td>".$Timestamp."</td>";
		echo "<td><a href='navbarAdmin.php?operation=deleteFeedback&FeedbackID=".$FeedbackID."'>Delete</a></td>";
		echo "</tr>";	
	}
		echo "</table>";
?>
</div>

</body>
</html>