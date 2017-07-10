<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);

include('session.php');
$userDetails=$userClass->userDetails($session_uid);

?>
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
        <a href="vodka.php#vodka">Vodka</a>
        <a href="chivas.php#chivas">Chivas</a>
        <a href="jack.php#jack">Jack Daniels</a>
        <a href="blanton.php#blanton">Blanton's</a>
		<a href="glenlivet.php#glenlivet">Glenlivet</a>
      </div>
    </div>
    <a href="#" onclick="document.getElementById('liquor').style.display='block'">All About Liquor</a>
  </div>
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
	<p class="w3-right" style="padding-left:10px"><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></p>
	<p class="w3-right" >Welcome <?php echo $userDetails->firstname; ?> !</p>
    <p class="w3-right" style="padding-right:10px">
      <i class="fa fa-shopping-cart" style="padding: 5px"></i>
	  <a href="account.php"><i class="fa fa-bars"style="padding: 5px"></i></a>
    </p>
  </header>
 <body>
<div>
<?php
		if(isset($_POST["return"])) {
			if($_POST["return"]=="yes") {
				header('Location: home.php');
			}
		}			
	?>
<h2> An Error Has Occured</h2>
<h2> Please return to home via the button</h2>
<form method=POST action="" name="return">
<p><button type="submit" class="w3-btn w3-red" value="yes" name="return">Return to Home</button></p>
</form>	
</div>
<!-- About Us Modal -->
<div id="about" class="w3-modal">
	<div class="w3-modal-content w3-animate-zoom w3-padding-jumbo" style="font-family: Arial; font-size:14">
		<div class="w3-container w3-white w3-center">
			<i onclick="document.getElementById('about').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text"></i>
			<h2 class="w3-wide">ABOUT US</h2>
			<p> A small little start up company selling assorted brands of alcohol products.Founded in a small little house along Tampines, we strive to give the best quality products a store could offer.</p>
			<p> All of us, from liquorholic, hopes that the customers of our shop, will have a pleasureful and joyful shopping experience. </p>
			<button type="button" class="w3-btn w3-red w3-margin-bottom" onclick="document.getElementById('about').style.display='none'">Got It!</button>
		</div>
	</div>
</div>
<!-- About Liquor Modal -->
<div id="liquor" class="w3-modal">
	<div class="w3-modal-content w3-animate-zoom w3-padding-jumbo" style="font-family: Arial; font-size:14">
		<div class="w3-container w3-white w3-center">
			<i onclick="document.getElementById('liquor').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text"></i>
			<h2 class="w3-wide">ABOUT LIQUOR</h2>
			<p> A distilled beverage, spirit, liquor, hard liquor or hard alcohol is an alcoholic beverage produced by distillation of a mixture produced from alcoholic fermentation. This process purifies it and removes diluting components like water, for the purpose of increasing its proportion of alcohol content (commonly expressed as alcohol by volume, ABV).As distilled beverages contain more alcohol, they are considered "harder" – in North America, the term hard liquor is used to distinguish distilled beverages from undistilled ones. </p>
			<button type="button" class="w3-btn w3-red w3-margin-bottom" onclick="document.getElementById('liquor').style.display='none'">Got It!</button>
		</div>
	</div>
</div>
 </body>
 </html>