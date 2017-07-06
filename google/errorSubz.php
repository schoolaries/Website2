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
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/google/");
	die();
}

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
		$conn = mysqli_connect("localhost","root","","demos");
		
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
	<p class="w3-right" style="padding-left: 10px"> <a href="javascript:void(0)" class="w3-padding w3-btn w3-black" onclick="document.getElementById('login').style.display='block'">Login</a></p>
	<p class="w3-right"> <a href="javascript:void(0)" class="w3-padding w3-btn w3-black" onclick="document.getElementById('signup').style.display='block'">Sign Up</a></p>	  
    <p class="w3-right w3-xlarge" style="padding-right: 10px">
      <a href="#"><i onclick="document.getElementById('login').style.display='block'" class="fa fa-shopping-cart w3-margin-right"></i></a>
    </p>
  </header>
 <body>
<div>
<?php
		if(isset($_POST["return"])) {
			if($_POST["return"]=="yes") {
				header('Location: index.php');
			}
		}			
	?>
<h2> An Error Has Occured</h2>
<h2> Please return to home via the button</h2>
<form method=POST action="" name="return">
<p><button type="submit" class="w3-btn w3-red" value="yes" name="return">Return to Home</button></p>
</form>	
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
 </body>
 </html>