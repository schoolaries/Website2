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
//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){ 
	header("Location: https://localhost/botanist/homepage.php");
	die();
}

require_once('dbconnect.php');

session_name('id');
session_start ();

$role = "Guest"; //Prevent Session Forging

$timeoutduration = 300; //Set timeout duration
if(isset($_SESSION['LAST_ACTIVITY'])) {
	if(time()- $_SESSION['LAST_ACTIVITY'] > $timeoutduration) { //check if session is destroyed
		session_unset(); //Destroy sessions
		session_destroy();
		header("location: homepage.php");
	}
}
$_SESSION['LAST_ACTIVITY'] = time();
?>

<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="#home" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">
            <a class="launch-modal w3-left" href="#" data-modal-id="modal-login">Login</a>	
			<a href="register.php" class="w3-left">Register</a>
			<a href="https://localhost/botanist/productview.php" class="w3-left">Product</a>
			<a href="#showcases" class="w3-left">Showcases</a>
			<a href="#about" class="w3-left w3-margin-right">About</a>
		</li>
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome to Botanist Floral! Feel free to look around at our amazing bouquets!</marquee> 
	</ul>
</div>	

<!-- MODAL -->
<form name="form1" method="post" action="auth.php">
<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
        		</button>	
				<h3 class="modal-title" id="modal-login-label">Login to our site</h3>
        		<p>Enter your username and password to log on:</p>	
        	</div>
        			
		<div class="modal-body">
			<form role="form" action="" method="post" class="login-form">
				<div class="form-group">
					<label class="sr-only" for="form-username">Username</label>
					<input type="text" name="myusername" placeholder="Username..." class="form-username form-control" id="myusername">
				</div>
	        
				<div class="form-group">
					<label class="sr-only" for="form-password">Password</label>
					<input type="password" name="mypassword" placeholder="Password..." class="form-password form-control" id="mypassword">
				</div>
					<button type="submit" id="submit" name="submit" class="btn">Sign in!</button>
			</form>
		</div>
		</div>
    </div>
</div>
</form>

<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/scripts.js"></script>
        
<!--[if lt IE 10]>
<script src="assets/js/placeholder.js"></script>
<![endif]-->

<!-- Header -->
<header class="w3-content w3-wide" style="max-width:1500px;" id="home">
	<img src="images/b_header.jpg" width="1500" height="710">
	<div class="w3-display-middle w3-margin-top w3-center">
		<h1 class="w3-xxlarge w3-text-white"><span class="w3-padding w3-black w3-opacity-min"><b>Botanist</b></span> <span class="w3-hide-small w3-text-light-grey">Floral</span></h1>
		</div>
</header>

<!-- Page content -->
<div class="w3-padding" style="max-width:1500px;">

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
      <h3>Verashnu Selveraj</h3>
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
  
  <div class="w3-container w3-padding-32" id="Contact">
	<h2 class="w3-border-bottom w3-border-light-grey w3-padding-12">About</h2>
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
