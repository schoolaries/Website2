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
if(isset($_POST["updateFeedback"]))
{
	if($_POST["updateFeedback"]=="yes")
	{
$email = test_input ( $_POST ["email"] );
$enquiry = test_input ( $_POST ["comment"] );
			if (!empty ( $enquiry )) {
				if (!empty ($email)) {
				$query = $con1->prepare ( "INSERT INTO `suggestion` (`Email`, `Comment`) VALUES (?,?)" );
				$query->bind_param ( 'ss', $email, $enquiry ); // bind the parameters
				if ($query->execute ()) { // execute query
					//echo "Thank you for your feedback!";
					echo "<br><div class = 'alert alert-info alert-dismissable'>";
					echo "<button type = 'button' class = 'close' data-dismiss = 'alert' aria-hidden = 'true'>";
					echo "&times;";
					echo "</button>";
					echo "<strong>Thank you for your feedback!</strong></div>";
				}
				echo "<strong>Thank you for your feedback!</strong></div>";
			}
			}
	}
}
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
			<a href="viewCart.php" class="w3-left">Shopping Cart</a>
			<a href="navbarMember.php#editProfile" class="w3-left">Edit Profile</a>
			<a href="navbarMember.php#showcases" class="w3-left">Showcases</a>
			<a href="navbarMember.php#about" class="w3-left">About</a>
			<a href="contactus.php" class="w3-left">Contact us</a>
			<a href="signedout.php" class="w3-left w3-margin-right">Logout</a>
		</li>
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome back <?php echo $_SESSION['LogFName']." ".$_SESSION['LogLName'];?>! </marquee> 
	</ul>
</div>

<br>
<!-- Page content -->
<div class="w3-padding" style="max-width:1500px;">

<!-- Contact Us Section -->
<div class="w3-container w3-padding-32" id="Contact">
	<h3 class="w3-border-bottom w3-border-light-grey w3-padding-12">Contact Us</h3>
	<p align=left>
	Email: botanistadmin@localhost
	</p>
	<p>
	Location: 100 Bukit Batok Road #888
	</p>
	<p>
	Hotline: +65 87753632 
    </p>
	<p>Hotline available only on Mondays to Fridays 10am-6pm.</p>
</div>

<div class="container" align="center">
			<h2> 
				<b><font color="#660000" size="20">LEAVE A FEEDBACK</font></b>
			</h2>
		</div>
	
		<br>
	
		<div class="bg-sample2">
			<br>
	
			<div class="container-fluid">
				<form class="form-horizontal" role="form" method="post"
					action="contactus.php">
					<div class="form-group">
						<label class="control-label col-sm-4" for="">Email:</label>
	   			<?php
							if (isset ( $_SESSION ['LogEmail'] )) {
					?>
	   			<div class="col-sm-5">
							<input type="email" class="form-control" id="email" name="email"
								value="<?php echo $_SESSION['LogEmail']?>">
						</div>
	   			<?php
							} else {
								?>
	   			<div class="col-sm-5">
							<input type="email" class="form-control" id="email" name="email"
								placeholder="Enter your email!" required>
						</div>
	   			<?php
							}
							?>
	 		</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="comment">Feedback:</label>
						<div class="col-sm-5">
							<textarea class="form-control" rows="5" id="comment"
								name="comment" placeholder="Enter your Feedback" required></textarea>
	
						</div>
						<br />
					<div class="form-group">
						<div class="col-sm-offset-5 col-sm-7">
							<!--<button type="submit" name="comment-submit" value="Enquired"
								class="btn btn-default btn-sharp"><font color="#660000">Send</font></button>-->
						</div> &nbsp;
						<input type="hidden" name="updateFeedback" value="yes" />
						<button type="submit" name="comment-submit" value="Enquired"
								class="btn-default btn-sharp"><font color="#660000">Send</font></button>
					</div>
				</form>
			</div>
		</div>
		
		<br>
		<br>
<!-- Footer -->
<footer class="w3-center w3-black w3-padding-16">
   <p><a href="#home" class="w3-hover-text-green">Back To Top</a>
   <br/>
   Copyright &copy; Botanist Floral Pte Ltd </p>
</footer>

</body>
</html>
						

