<!DOCTYPE html>
<html>
<title>Welcome to Liquorholic</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="sheet1.css">
<link rel="stylesheet" href="sheet2.css">
<link rel="stylesheet" href="sheet3.css">
<link rel="stylesheet" href="fontawesome/css/font-awesome.css">
<link rel="stylesheet" href="fontawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="shopping.css">
<style>
.w3-sidenav a {font-family: "Roboto", sans-serif}
body,h1,h2,h3,h4,h5,h6,.w3-wide {font-family: "Montserrat", sans-serif;}
</style>
<body class="w3-content" style="max-width:1200px">

<!-- Sidenav/menu -->
<nav class="w3-sidenav w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidenav">
  <div class="w3-container w3-padding-16">
    <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-closebtn w3-hover-text-red"></i>
    <h3 class="w3-wide"><b>Liquorholic</b></h3>
  </div>
  <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
	<a href="home.php">Home</a>
    <a href="#" onclick="document.getElementById('about').style.display='block'">About Us</a>
    <div class="w3-accordion">
      <a onclick="myAccFunc()" href="javascript:void(0)" class="w3-text-black" id="myBtn">
        LiquorStore <i class="fa fa-caret-down"></i>
      </a>
      <div id="demoAcc" class="w3-accordion-content w3-padding-large w3-medium">
        <a href="vodkas.php#vodka">Vodka</a>
        <a href="chivass.php#chivas">Chivas</a>
        <a href="jacks.php#jack">Jack Daniels</a>
        <a href="blantons.php#blanton">Blanton's</a>
		<a href="glenlivets.php#glenlivet">Glenlivet</a>
      </div>
    </div>
    <a href="#" onclick="document.getElementById('liquor').style.display='block'">All About Liquor</a>
  </div>
  <a href="#footer" class="w3-padding">Contact</a> 
  <a href="javascript:void(0)" class="w3-padding" onclick="document.getElementById('newsletter').style.display='block'">Newsletter</a> 
  <a href="#footer"  class="w3-padding">Subscribe</a>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-black w3-xlarge w3-padding-24">
  <span class="w3-left w3-wide">Liquorholic</span>
  <a href="javascript:void(0)" class="w3-right w3-opennav" onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>

<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:83px"></div>
  
  <!-- Top header -->
  <header class="w3-container w3-large">
    <h2 class="w3-left">Welcome to Liquorholic</h2>
	<p class="w3-right" style="padding-left: 10px"> <a href="javascript:void(0)" class="w3-padding w3-btn w3-black" onclick="document.getElementById('login').style.display='block'">Login</a> </p>
	<p class="w3-right"> <a href="javascript:void(0)" class="w3-padding w3-btn w3-black" onclick="document.getElementById('signup').style.display='block'">Sign Up</a></p>	  
    <p class="w3-right w3-xlarge" style="padding-right: 10px">
      <a href="#"><i onclick="document.getElementById('login').style.display='block'" class="fa fa-shopping-cart w3-margin-right"></i></a>
    </p>
  </header>

  <!-- Image header -->
  <div class="w3-display-container w3-container">
    <img src="https://bestlocalspirits.files.wordpress.com/2015/05/plpromo-liquor-bottles-8662cb212c980cde.jpg" alt="Liquor" style="width:100%">
    <div class="w3-display-topleft w3-padding-xxlarge w3-text-black" style="background-color:white; opacity: 0.7">
		<div style="opacity: 1">
			<h1 class="w3-jumbo w3-hide-small" style="opacity: 1">New arrivals</h1>
			<h1 class="w3-hide-large w3-hide-medium" style="opacity:1">New arrivals</h1>
			<h1 class="w3-hide-small" style="opacity:1">COLLECTION 2016</h1>
			<p><a href="#New Arrivals" class="w3-btn w3-padding-large w3-large" style="opacity:1">SHOP NOW</a></p>
		</div>
    </div>

<h1 align="center" id="New Arrivals">New Arrivals </h1>
<?php $current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>
<!-- View Cart Box Start -->
<div>
	
<!-- Products List End -->
</div>
  <!-- Subscribe section -->
  <div class="w3-container w3-black w3-padding-32">
    <h1>Subscribe</h1>
    <p>To get special offers and VIP treatment:</p>
	<form method=POST action="" name="sub">
    <p><input class="w3-input w3-border" type="email" name="emailSub" placeholder="Enter e-mail" style="width:100%"></p>
    <button type="submit" class="w3-btn w3-padding w3-red w3-margin-bottom" value="yes" name="sub">Subscribe</button>
	</form>
  </div>
  
   <!-- Footer -->
  <footer class="w3-padding-64 w3-light-grey w3-small w3-center" id="footer">
    <div class="w3-row-padding">
      <div class="w3-col s4">
        <h4>Contact</h4>
        <p>Questions? Go ahead.</p>
        <form action="index.php" method="post">
          <p><input class="w3-input w3-border" type="text" placeholder="Name" name="Name" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Email" name="Email" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Subject" name="Subject" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Message" name="Message" required></p>
          <button type="submit" name="submit1" class="w3-btn-block w3-padding w3-black">Send</button>
        </form>
