<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);

include('session.php');
$userDetails=$userClass->userDetails($session_uid);

 //Ensures that the user browse in https
if(!isset($_SERVER['HTTP'])){
	header("Location: http://ec2-34-211-115-232.us-west-2.compute.amazonaws.com/google/");
	die();
}
?>
<?php

$conn = mysqli_connect("localhost","root","P@ssw0rd!","demos");

$storedid = $_SESSION['uid'];
if(isset($_POST["updateAcc"])) {
	if($_POST["updateAcc"]=="yes") {
		$email = $_POST["emailReg"];
		$contactnum = $_POST["contactnum"];
		$streetAddress = $_POST["streetAddress"];
		$postal = $_POST["postal"];
		$unitNumber = $_POST["unitNumber"];
		$email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
		$contact_check = preg_match('/^[.*\d]{8}$/', $contactnum);
		$postal_check = preg_match('/^[.*\d]{6}$/', $postal );
		
		if($email_check && $contact_check && $postal_check) //check condition to fuifill preg_match
		{
			$query=$conn->prepare('update users set email=?,contactnum=?,streetAddress=?,postal=?,unitNumber=? WHERE uid=?');
			$query->bind_param('sssssi',$email,$contactnum,$streetAddress,$postal,$unitNumber,$storedid);
			if($query->execute()){
				header('Location: account.php');
			}
		}
		else {
			header('Location: errorSub.php');
			die;
		}
	}
}

if(isset($_POST["sub"])) {
	if($_POST["sub"]=="yes") {
	$email=$_POST['emailSub'];
	$email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
	
	
	if($email_check) //check condition to fuifill preg_match
	{
		$conn = mysqli_connect("localhost","root","P@ssw0rd!","demos");
		
		$query = $conn->prepare("INSERT INTO newsletter (Email) VALUES (?)");
		$query->bind_param('s',$email);
		if($query->execute()) {
			header('Location: '.$_SERVER['PHP_SELF']);
			die;
		}
		else {
			header('Location: errorSub.php');
			die;
		}
		
	}
	else
	{
		$errorMsgReg="Enter valid details.";
	}
}
}
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
	<p class="w3-right" style="padding-left:10px"><a href="<?php echo BASE_URL; ?>logout.php">Logout</a></p>
	<p class="w3-right" >Welcome <?php echo $userDetails->firstname; ?> !</p>
    <p class="w3-right" style="padding-right:10px">
      <a href="view_cart.php"><i class="fa fa-shopping-cart" style="padding: 5px"></i></a>	
	  <a href="account.php"><i class="fa fa-bars"style="padding: 5px"></i></a>
    </p>
  </header>
  <div>
  <h1>Account Settings</h1>
  </div>
  <div>
  <h4>User Details </h4>
  </div>
  <div>
	<table border=0>
		<tr>
			<td>
				<label><b>First Name: </b></label>
			</td>
			<td>
				<?php echo $userDetails->firstname; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Last Name: </b></label>
			</td>
			<td>
				<?php echo $userDetails->lastname; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Email: </b></label>
			</td>
			<td>
				<?php echo $userDetails->email; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Contact Number: </b></label>
			</td>
			<td>
				<?php echo $userDetails->contactnum; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Street Address: </b></label>
			</td>
			<td>
				<?php echo $userDetails->streetAddress; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Unit Number: </b></label>
			</td>
			<td>
				<?php echo $userDetails->unitNumber; ?>
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Postal Code </b></label>
			</td>
			<td>
				<?php echo $userDetails->postal; ?>
			</td>
		</tr>
	</table>
  </div>
  <div>
  <br>
  <button type="button" class="w3-btn w3-red" onclick="document.getElementById('updateAcc').style.display='block'">Update</button>
  </div>
  <!-- update modal -->
  <div id="updateAcc" class="w3-modal">
	<div class="w3-modal-content w3-padding-jumbo">
		<div class="w3-white w3-center">
			<i onclick="document.getElementById('updateAcc').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey"></i>
			<h4> Account Details</h4>
			<form method=POST action="" value="updateAcc">
				<table border=0>
					<tr>
						<td>
							<label><b>Email: </b></label>
						</td>
						<td>
							<input type="email" placeholder="Email" name="emailReg" value="<?php echo $userDetails->email;?>">
						</td>
					</tr>
					<tr>
						<td>
							<label><b>Contact Number: </b></label>
						</td>
						<td>
							<input type="text" placeholder="Contact Number" name="contactnum" value="<?php echo $userDetails->contactnum;?>">
						</td>
					</tr>
					<tr>
						<td>
							<label><b>Street Address: </b></label>
						</td>
						<td>
							<input type="text" placeholder="Street Address" name="streetAddress" value="<?php echo $userDetails->streetAddress;?>">
						</td>
					</tr>
					<tr>
						<td>
							<label><b>Unit Number: </b></label>
						</td>
						<td>
							<input type="text" placeholder="Unit Number" name="unitNumber" value="<?php echo $userDetails->unitNumber;?>">
						</td>
					</tr>
					<tr>
						<td>
							<label><b>Postal Code: </b></label>
						</td>
						<td>
							<input type="text" placeholder="Postal Code" name="postal" value="<?php echo $userDetails->postal;?>">
						</td>
					</tr>
				</table>
				<button type="submit" class="w3-btn w3-red" align="bottom" value="yes" name="updateAcc">Update</button>
			</form>
		</div>
	</div>
</div>
  
  <!-- delete account -->
  <div align="center">
	<form method=POST action="" value="deleteconfirm">
		<button type="button" class="w3-btn w3-padding-large w3-red w3-margin-bottom" align="center" onclick="document.getElementById('deleteAcc').style.display='block'">Delete Account</button>
	</form>
   </div>
   <!-- Delete Modal -->
   <div id="deleteAcc" class="w3-modal">
	<div class="w3-modal-content w3-padding-jumbo">
		<div class="w3-white w3-center">
			<i onclick="document.getElementById('deleteAcc').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey"></i>
		<h2 align="center"> Account Deletion </h2>
		<form method=POST action="delete.php" name="deleteAcc">
			<table border=0 align="center">
				<tr>
					<td style="padding: 10px">
						<label><b>Username/Email: </b></label>
					</td>
					<td>
						<input type="text" placeholder="Username/Email" name="usernameEmail" required>
					</td>
				</tr>
				<tr>
					<td style="padding: 10px">
						<label><b>Password: </b></label>
					</td>
					<td>
						<input type="password" placeholder="Password" name="password" required>
					</td>
				</tr>
			</table>
			<div>
					<button type="submit" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="yes" name="deleteAcc">Confirm</button>
					<button type="button" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="close" onclick="document.getElementById('deleteAcc').style.display='none'">Close</button>
			</div>
		</form>
		</div>
	</div>
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
        <form action="form.asp" target="_blank">
          <p><input class="w3-input w3-border" type="text" placeholder="Name" name="Name" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Email" name="Email" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Subject" name="Subject" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Message" name="Message" required></p>
          <button type="submit" class="w3-btn-block w3-padding w3-black">Send</button>
        </form>
      </div>

      <div class="w3-col s4">
        <h4>About</h4>
      <p><a href="#" onclick="document.getElementById('about').style.display='block'">About us</a></p>
      </div>

      <div class="w3-col s4 w3-justify">
        <h4>Store</h4>
        <p><i class="fa fa-fw fa-map-marker"></i> Company Name</p>
        <p><i class="fa fa-fw fa-phone"></i> 0044123123</p>
        <p><i class="fa fa-fw fa-envelope"></i> ex@mail.com</p>
        <h4>We accept</h4>
        <p><i class="fa fa-fw fa-cc-amex"></i> Amex</p>
        <p><i class="fa fa-fw fa-credit-card"></i> Credit Card</p>
        <br>
        <i class="fa fa-facebook-official w3-xlarge w3-hover-text-indigo"></i>
        <i class="fa fa-instagram w3-xlarge w3-hover-text-purple"></i>
        <i class="fa fa-twitter w3-xlarge w3-hover-text-light-blue"></i>
        <i class="fa fa-pinterest w3-xlarge w3-hover-text-red"></i>
        <i class="fa fa-flickr w3-xlarge w3-hover-text-blue"></i>
      </div>
    </div>
  </footer>

  <!-- End page content -->
</div>

<!-- Newsletter Modal -->
<div id="newsletter" class="w3-modal">
  <div class="w3-modal-content w3-animate-zoom w3-padding-jumbo">
    <div class="w3-container w3-white w3-center">
	  <form method="post" action="" name="sub">
      <i onclick="document.getElementById('newsletter').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey w3-margin"></i>
      <h2 class="w3-wide">NEWSLETTER</h2>
      <p>Join our mailing list to receive updates on new arrivals and special offers.</p>
      <p><input class="w3-input w3-border" type="email" placeholder="Enter e-mail" name="emailSub"></p>
      <button type="submit" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="yes" name="sub">Subscribe</button>
	  </form>
    </div>
  </div>
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
<script>
// Accordion 
function myAccFunc() {
    var x = document.getElementById("demoAcc");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

// Click on the "Jeans" link on page load to open the accordion for demo purposes
document.getElementById("myBtn").click();


// Script to open and close sidenav
function w3_open() {
    document.getElementById("mySidenav").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
    document.getElementById("mySidenav").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

var deleteAcc = document.getElementById('deleteAcc');

window.onclick = function(event) {
	if (event.target == deleteAcc) {
		deleteAcc.style.display ="none";
	}
}

function myFunction()
{
alert("I am an alert box!"); // this is the message in ""
}
</script>

</body>
</html>
