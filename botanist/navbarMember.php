<!DOCTYPE html>
<html>
<title>Botanist Floral</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">

<body>

<?php
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();
?>
<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="#home" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">
			<a href="product.php" class="w3-left">Products</a>
			<?php 
			if(isset($_SESSION["cart_products"])) {?>
			<a href="viewCart.php" class="w3-left">Shopping Cart</a>
			<?php 
			}
			?>
			<a href="#editProfile" class="w3-left">Edit Profile</a>
			<a href="#showcases" class="w3-left">Showcases</a>
			<a href="#about" class="w3-left">About</a>
			<a href="contactus.php" class="w3-left">Contact us</a>
			<a href="signedout.php" class="w3-left w3-margin-right">Logout</a>
		</li>
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome back <?php echo $_SESSION['LogFName']." ".$_SESSION['LogLName'];?>! This is the member panel of Botanist Floral!</marquee> 
	</ul>
</div>	


<!-- Header -->
<header class="w3-content w3-wide"style="max-width:1500px;" id="home">
	<img src="images/b_header.jpg" width="1500" height="710">
	<div class="w3-display-middle w3-margin-top w3-center">
		<h1 class="w3-xxlarge w3-text-white"><span class="w3-padding w3-black w3-opacity-min"><b>Botanist</b></span> <span class="w3-hide-small w3-text-light-grey">Floral</span></h1>
		</div>
</header>
<?php 
//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/navbarMember.php");
	die();
}

$err = "false";
$autherror = "false";
$nnerror = "False";
//Prevent Session Forging
$role = "Guest";
$SessionError = "";

//Role Check
if(isset($_SESSION['LogID'])){
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

if($role != "Member") {
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

//UNTIL HERE========================
	
//operation for updating users
if(isset($_POST["updateUser"])) {
	if($_POST["updateUser"]=="yes")
	{
		$FirstName = $LastName = $Gender = $Email = $Contact = $Birthdate = $Role = "";
		
		$err = "no";
		
		//Check Fname
		if (empty($_POST["FirstName"])) {
			$err = "yes";
		} else {
			$FirstName = test_input($_POST["FirstName"]);
			// check if Fname only contains letters
			if (!preg_match("/^[a-zA-Z]*$/",$FirstName)) {
				$err = "yes";
			}
		}
		
		//Check Lname
		if (empty($_POST["LastName"])) {
			$err = "yes";
		} else {
			$LastName = test_input($_POST["LastName"]);
			// check if Lname only contains letters
			if (!preg_match("/^[a-zA-Z]*$/",$LastName)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Gender"])) {
			$err = "yes";
		} else {
			$Gender = test_input($_POST["Gender"]);
			if (!preg_match("/^[mf]{1}$/", $Gender)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Email"])) {
			$err = "yes";
		} else {
			$Email = test_input($_POST["Email"]);
			// check if e-mail address is valid, regex expression below is the same one used in filter_var() based on a regex by Michael Rushton
			// /^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD
		
			if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Contact"])) {
			$err = "yes";
		} else {
			$Contact = test_input($_POST["Contact"]);
			// check if contact only contains 8 numbers
			if (!preg_match("/^([0-9]){8}$/",$Contact)) {
				$err = "yes";
			}
		}
		
		if (empty($_POST["Birthdate"])) {
			$err = "yes";
		}
		else {
			$Birthdate = test_input($_POST["Birthdate"]);
		}
		
		if ($err == "no") {
			
			$query=$con1->prepare("update user set FirstName=?, LastName=?, Gender=?, Email=?, Contact=?, Birthdate=? where UserID=?");
			$query->bind_param('ssssiss', $FirstName, $LastName, $Gender, $Email, $Contact, $Birthdate, $_POST["UserID"]);//bind the parameters	
			if($query->execute()) {
				echo "<center>Record Updated!</center><br>";
			}
		}
		else {
			echo "Invalid parameters input!";
		}
	}
}
?>

<!-- Page content -->
<div class="w3-padding" style="max-width:1500px;">
<?php 
if (isset($_SESSION['2fa'])){
	echo "<center>Authenticated! You may proceed to click link.</center>";
}
?>
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
		echo "<td>".$FirstName."</td>";
		echo "<td>".$LastName."</td>";
		echo "<td>".$Gender."</td>";
		echo "<td>".$Email."</td>";
		echo "<td>".$Username."</td>";
		echo "<td>".$Contact."</td>";
		echo "<td>".$Birthdate."</td>";
		echo "<td>".$Role."</td>";
		if ($_SESSION['ManageProfile'] == "False") {
			echo "<form method='post' action='Member2fa.php'>";
			echo "<input type='hidden' id='pcsrf' name='pcsrf' value=". $_SESSION['Token'].">";
			echo "<td><input type='submit' name='submit' value='Update'/></td>";
		}
		else {
			echo "<td><a href='editMemberProfile.php?operation=updateUser&UserID=".$UserID."&FirstName=".$FirstName."&LastName=".$LastName."&Gender=".$Gender."&Email=".$Email."&Username=".$Username."&Contact=".$Contact."&Birthdate=".$Birthdate."&Role=".$Role."'>Update</a></td>";
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

<!-- Bouquets Section -->
	<div class="w3-container w3-padding-32" id="showcases">
		<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">Showcases</h2>
	</div>

	<div class="w3-row-padding">
		<div class="w3-col l3 m6 w3-margin-bottom">
			<div class="w3-display-container">
				<div class="w3-display-topleft w3-black w3-padding">A classic bouquet of roses
				</div>
					<img src="images/img_prod.jpg" alt="House" style="width:85%">
					<br />
					<p>The traditional Red Rose, a perfect gift for your valentines date</p>
			</div>
		</div>
    
	<div class="w3-col l3 m6 w3-margin-bottom">
		<div class="w3-display-container">
			<div class="w3-display-topleft w3-black w3-padding">Flowers that are of a nude shade
			</div>
				<img src="images/img_prod-2.jpg" alt="House" style="width:85%">
				<br />
				<p>A bouquet of roses that are varied in many different colors, perfect decoration to place on your dining table</p>
		</div>
	</div>
		
    <div class="w3-col l3 m6 w3-margin-bottom">
		<div class="w3-display-container">
			<div class="w3-display-topleft w3-black w3-padding">An assortment of Hibiscus
			</div>
				<img src="images/img_prod-13.jpg" alt="House" style="width:85%">
				<br />
				<p>These bunch of outstanding flowers are connected to long stems that are as fragile as glass.</p>
		</div>
	</div>
		
    <div class="w3-col l3 m6 w3-margin-bottom">
		<div class="w3-display-container">
			<div class="w3-display-topleft w3-black w3-padding">A bouquet of daisies
			</div>
				<img src="images/img_prod-14.jpg" alt="House" style="width:85%">
				<br />
				<p>Sparkling white, bright yellow amd baby pink.The combination of colors in this vase is asthetically pleasing to the eye</p>
		</div>
	</div>
	</div>

<!-- About Section -->
<div class="w3-container w3-padding-32" id="about">
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">About</h2>
	<p align=left>Botanist Floral was founded in 2016. It consist of <b>3</b> self-motivated and inspiring individuals that have burning passion for flowers. These professionals then search for a wide selection of cut roses which will have a strikingly beautiful fragance at their core. With a selection of the exclusive assortment of David Austin wedding roses and the Meilland Jardin and Parfum collection, as well as many varieties of other fragrant cut roses, they are able to offer a wide range of true roses.
    </p>
</div>

  <div class="w3-row-padding w3-grayscale">
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="images/indianceo.jpg" alt="John" style="width:100%">
      <h3>Verashnu</h3>
      <p class="w3-opacity">Founder & CEO</p>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="images/techsupport.jpg" alt="Jane" style="width:100%">
      <h3>Kumar Chambal</h3>
      <p class="w3-opacity">Tech Support</p>
    </div>
    <div class="w3-col l3 m6 w3-margin-bottom">
      <img src="images/florist1.jpg" alt="Mike" style="width:100%">
      <h3>Rosa</h3>
      <p class="w3-opacity">Florist</p>
    </div>
  </div>
  
 
<!-- End page content -->
</div>



<!-- Footer -->
<footer class="w3-center w3-black w3-padding-16">
   <p><a href="#home" class="w3-hover-text-green">Back To Top</a>
   <br/>
   Copyright &copy; Botanist Floral Pte Ltd </p>
</footer>

</body>
</html>
