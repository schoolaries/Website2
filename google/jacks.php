<?php //recaptcha php
if (isset($_POST['submit']))
{
	$secret = '6Ld9WRIUAAAAAMkmt1rN6NEw2lI_jy0-cMVqOyzp';
	$response =$_POST['g-recaptcha-response'];
	$remoteip = $_SERVER['REMOTE_ADDR'];

	$url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
	$result = json_decode($url,TRUE);
	print_r($result);
	die();
	if ($result['success'] == 1)
	{
		echo $_POST['nameReg'];
	}
}

?>


<?php
include("config.php"); //connect to database

 //Ensures that the user browse in https
/*if(!isset($_SERVER['HTTP'])){
	header("Location: http://ec2-34-211-115-232.us-west-2.compute.amazonaws.com/google");
	die();
}*/

if(!empty($_SESSION['uid'])) // if session id is not empty, proceed to QR code
{
	header("Location: device_confirmations.php"); // jump to the location.
}

include('userClass.php'); // connect to functions in userClass
$userClass = new userClass();

require_once 'GoogleAuthenticator.php';
$ga = new GoogleAuthenticator();
$secret = $ga->createSecret();

$errorMsgReg='';
$errorMsgLogin='';
if (!empty($_POST['loginSubmit'])) // if login submit is not empyty.
{
	$usernameEmail=$_POST['usernameEmail']; //user input for email
	$password= hash('sha256', $_POST['password']); //user input for password 
	$salt = md5($usernameEmail);
	$spwd = "{$password}{$salt}";
	$pass = hash('sha256', $spwd);

	
	if(strlen(trim($usernameEmail))>1 && strlen(trim($pass))>1 )
	{
		$uid=$userClass->userLogin($usernameEmail,$pass,$secret);
		if($uid) 
		{
			$url=BASE_URL.'device_confirmations.php';
			header("Location: $url"); //link back to device_confirmation.
		}
		else
		{
			$errorMsgLogin="Please check login details.";
		}
	}
}

if (!empty($_POST['signupSubmit']))
{

	$username=$_POST['usernameReg'];
	$email=$_POST['emailReg'];
	$password=$_POST['passwordReg'];
	$password2=$_POST['passwordcfm'];
	$firstname=$_POST['fname'];
	$lastname=$_POST['lname'];
	$contactnum=$_POST['contactnum'];
	$streetAddress=$_POST['streetAddress'];
	$unitNumber=$_POST['unitNumber'];
	$postal=$_POST['postal'];
	$bday=$_POST['bday'];
	$username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
	$email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
	$password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);
	$password_check2 = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password2);
	$contact_check = preg_match('/^[.*\d]{8}$/', $contactnum);
    $postal_check = preg_match('/^[.*\d]{6}$/', $postal );
	$bday_check = preg_match('/^[0-9]{4}-([1-9]|0[1-9]|1[0-2])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/', $bday);
	$name = "{$firstname}{$lastname}";
	/**Birthday
	if (! preg_match ( "/^[0-9]{4}-([1-9]|0[1-9]|1[0-2])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/", $birthday )) {
			echo "<br>Error! Birthdate format may be incorrect, use Year-Month-Day";
			$error = "True";
	} else {
			$timeinseconds = strtotime ( $bday );
		
			// echo $timeinseconds;
			$endtime = time () - $timeinseconds;
			// echo "<br> Endtime in seconds . $endtime ";
			$finaltime = ($endtime / (60 * 60 * 24)) / 365;
			// echo "<br> Final time: " . $finaltime;
			if ($finaltime < 18) {
				echo "<br>You are underaged!";
			}
	} */
	if(isset($_POST['g-recaptcha']))
	{
		$captcha=$_POST['g-recaptcha'];
	}
	if (!empty($captcha))
	{
		echo '<h2> Please check the captcha form</h2>';
		exit();
	}
	
	if($username_check && $email_check && $password_check && $password_check2 && $contact_check && $postal_check && $bday_check && strlen(trim($name))>0) //check condition to fuifill preg_match
	{
		if($password == $password2)
		{
			$uid=$userClass->userRegistration($username,$password,$email,$firstname,$lastname,$contactnum,$streetAddress,$unitNumber,$postal,$bday,$secret); //check with database uid is unique
			if($uid)
			{
				$url=BASE_URL.'device_confirmations.php';
				header("Location: $url");
			}
			else
			{
				$errorMsgReg="Username or Email already exits.";
			}
		}
		else
		{
			$errorMsgReg="Please retype your password.";
		}
	}
	else
	{
		$errorMsgReg="Enter valid details.";
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
			header('Location: errorSubz.php');
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
<title>Jack Daniels</title>
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
	<a href="index.php">Home</a>
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
			<p><a href="index.php#New Arrivals" class="w3-btn w3-padding-large w3-large" style="opacity:1">SHOP NOW</a></p>
		</div>
    </div>
  </div>

  <div class="w3-container w3-text-grey" id="jack">
  </div>

<h1 align="left">Jack Daniels </h1>
<?php $current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>
<!-- View Cart Box Start -->
<div>
<?php
if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
	echo '<div class="cart-view-table-front" id="view-cart">';
	echo '<h3>Your Shopping Cart</h3>';
	echo '<form method="post" action="cart_update.php">';
	echo '<table width="100%"  cellpadding="6" cellspacing="0">';
	echo '<tbody>';

	$total =0;
	$b = 0;
	foreach ($_SESSION["cart_products"] as $cart_itm)
	{
		$product_name = $cart_itm["product_name"];
		$product_qty = $cart_itm["product_qty"];
		$product_price = $cart_itm["product_price"];
		$product_code = $cart_itm["product_code"];
		$bg_color = ($b++%2==1) ? 'odd' : 'even'; //zebra stripe
		echo '<tr class="'.$bg_color.'">';
		echo '<td>Qty <input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
		echo '<td>'.$product_name.'</td>';
		echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /> Remove</td>';
		echo '</tr>';
		$subtotal = ($product_price * $product_qty);
		$total = ($total + $subtotal);
	}
	echo '<td colspan="2">';
	echo '<button type="submit" class="w3-btn w3-red" style="font-family: Arial; font-size:14">Update</button>';
	echo '</td>';
	echo '<td colspan="2">';
	echo '<a href="view_cart.php" class="w3-btn w3-red" style="font-family: Arial; font-size:14; padding: 7px">Checkout</a>';
	echo '</td>';
	echo '</tbody>';
	echo '</table>';
	
	$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
	echo '</form>';
	echo '</div>';

}
?>
</div>
<!-- View Cart Box End -->
<div>
<!-- Products List Start -->
<?php
$results = $mysqli->query("SELECT product_code, product_name, product_desc, product_img_name, price FROM products WHERE product_name LIKE '%jack daniel%' ORDER BY id ASC");
if($results){ 
$products_item = '<ul class="products">';
//fetch results set as object and output HTML
while($obj = $results->fetch_object())
{
$products_item .= <<<EOT
	<li class="product">
	<div class="product-content"><h3>{$obj->product_name}</h3>
	<div class="w3-center" style="margin-left: -30px"><img src="images/{$obj->product_img_name}"></div>
	<div class="product-info" align="center" style="padding-top:10px">
	Price {$currency}{$obj->price} 
	<fieldset>
	<p align="center"><a href="javascript:void(0)" class="w3-padding w3-btn w3-black" onclick="document.getElementById('{$obj->product_code}').style.display='block'">Add to Cart</a></p>	 
	
	</fieldset>
	<input type="hidden" name="product_code" value="{$obj->product_code}" />
	<input type="hidden" name="type" value="add" />
	<input type="hidden" name="return_url" value="{$current_url}" />
	
	</form>
	</li>
	
	<!-- Modal -->
	<div class="w3-modal" id='{$obj->product_code}'>
		<div class="w3-modal-content w3-padding-jumbo">
			<div class="w3-center">
			<i onclick="document.getElementById('{$obj->product_code}').style.display='none'" style="color:white;" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey w3-margin"></i>
			<div style="background-color: black; color:white;">
			<h2> {$obj->product_name} </h2>
			</div>
			<form method="post" action="cart_update.php">
			<table border= 0>
				<tr>
					<td rowspan="2">
						<div class="w3-left"><img src="images/{$obj->product_img_name}"></div>
					</td>
					<td>
						<div><p> {$obj->product_desc} </p></div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="product-info">
						Price {$currency}{$obj->price} 
						<label style="padding-left:20px; padding-right:20px">
						<span>Quantity</span>
						<input type="text" size="2" maxlength="2" name="product_qty" value="1" />
						</label>
						<input type="hidden" name="product_code" value="{$obj->product_code}" />
						<input type="hidden" name="type" value="add" />
						<input type="hidden" name="return_url" value="{$current_url}" />
						<button type="submit" class="w3-black">Add</button>
						</div>
					</td>
				</tr>
			</table>
			</form>
			</div>
		</div>
	</div>
EOT;
}
$products_item .= '</ul>';
echo $products_item;
}
?>    

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
<!-- Login Modal -->
<div id="login" class="w3-modal">
	<div class="w3-modal-content w3-padding-jumbo">
		<div class="w3-white w3-center">
		<i onclick="document.getElementById('login').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey"></i>
		<h2 align="center"> Welcome to Liquorholic! </h2>
		<form method=POST action="" name="login">
			<table border=0 align="center">
				<tr>
					<td style="padding: 10px">
						<label><b>Username/Email: </b></label>
					</td>
					<td>
						<input type="text" placeholder="Username/Email" name="usernameEmail" required>
					</td>
					<td style="padding: 10px">
						<a href="#">Forgot Your Username?</a>
					</td>
				</tr>
				<tr>
					<td style="padding: 10px">
						<label><b>Password: </b></label>
					</td>
					<td>
						<input type="password" placeholder="Password" name="password" required>
					</td>
					<td style="padding: 10px">
						<a href="#">Forgot Your Password?</a>
					</td>
				</tr>
			</table>
			<div class="errorMsg"><?php echo $errorMsgLogin; ?></div>
			<div>
					<button type="submit" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="Login" name="loginSubmit">Login</button>
					<button type="button" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="close" onclick="document.getElementById('login').style.display='none'">Close</button>
			</div>
		</form>
		</div>
	</div>
</div>

<!--Sign Up Modal-->
<div id="signup" class="w3-modal">
	<div class="w3-modal-content w3-padding-jumbo">
		<div class="w3-white w3-center">
			<i onclick="document.getElementById('signup').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey"></i>
				<h2 align="center">Registration</h2>
					<form method="post" action="" name="signup">
						<table border=0 align="center">
							<tr>
								<td>
									<label><b>First Name: </b></label>
								</td>
								<td>
									<input type="text" name="fname" autocomplete="off" required/>
								</td>
								<td>
									<label><b>Last Name: </b></label>
								</td>
								<td>
									<input type="text" name="lname" autocomplete="off" required />
								</td>
							</tr>
							<tr>
								<td>
									<label><b>Username: </b></label>
								</td>
								<td>
									<input type="text" name="usernameReg" autocomplete="off"  required/>
								</td>
								<td>
									<label><b>Password: </b></label>
								</td>
								<td>
									<input type="password" name="passwordReg" autocomplete="off" required/>
								</td>
								<tr>
								<td>
									<label><b>Confirm Password: </b><label>
								</td>
								<td>
									<input type="password" name="passwordcfm" autocomplete="off" required>
								</td>
								</tr>
							</tr>
							<tr>
								<td>
									<label><b>Email: </b></label>
								</td>
								<td>
									<input type="email" name="emailReg" autocomplete="off" required/>
								</td>
								<td>
									<label><b>Contact Number: <b></label>
								</td>
								<td>
									<input type="text" name="contactnum" autocomplete="off" required/>
								</td>
							</tr>
							<tr>
								<td>
									<label><b>Street Address: </b></label>
								</td>
								<td colspan="3" align="left">
									<input type="text" name="streetAddress" style="width:520px" autocomplete="off" required/>
								</td>
							</tr>
							<tr>
								<td>
									<label><b>Unit Number: </b></label>
								</td>
								<td>
									<input type="text" name="unitNumber" autocomplete="off" required/>
								</td>
								<td>
									<label><b>Postal Code: </b></label>
								</td>
								<td>
									<input type="text" name="postal" autocomplete="off" required/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="bday"><b>Birthdate: </b></label>
								</td>
								<td>
									<input type=date id="bday" name="bday" placeholder="Year-Month-Day" required>
								</td>
								
							</tr>
						</table>
						<div align="center">
							<div class="errorMsg"><?php echo $errorMsgReg; ?></div>
							<div  style="padding: 10px"class="g-recaptcha" data-sitekey="6Ld9WRIUAAAAADL4qBeOKvZNuE1WSdyDtINeAmIC"></div>
						</div>
						<script src="https://www.google.com/recaptcha/api.js" async defer></script>
						<button type="submit" class="w3-btn w3-red w3-padding-large w3-margin-bottom" name="signupSubmit" value="Signup">Sign Up </button>
						<button type="button" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="close" onclick="document.getElementById('signup').style.display='none'">Close</button>
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

var login = document.getElementById('login');

window.onclick = function(event) {
	if (event.target == login) {
		login.style.display ="none";
	}
}

var signup = document.getElementById('signup');

window.onclick = function(event) {
	if (event.target == signup) {
		signup.style.display ="none";
	}
}
</script>

</body>
</html>