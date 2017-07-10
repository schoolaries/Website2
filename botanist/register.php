<!DOCTYPE html>
<html>
<head>
	<script src="https://www.google.com/recaptcha/api.js" async defer>
	</script>
	<title>Register</title>
</head>
<style>
	.error {color: #FF0000;}
	form {
    	color: #00008B;
	}
</style>

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
	header("Location: https://localhost/botanist/register.php");
	die();
}

require_once('dbconnect.php');

session_name('id');
session_start ();

$role = "Guest"; //Prevent Session Forging
echo "<br><br><br>";

$timeoutduration = 300; //Set timeout duration
if(isset($_SESSION['LAST_ACTIVITY'])) {
	if(time()- $_SESSION['LAST_ACTIVITY'] > $timeoutduration) { //check if session is destroyed
		session_unset(); //Destroy sessions
		session_destroy();
		header("location: https://localhost/botanist/homepage.php");
	}
}
$_SESSION['LAST_ACTIVITY'] = time();

// define variables and set to empty values
$username = $email = $contact = $pwd = $gender = $Fname = $Lname = $pwd2 = $birthday = $Cardtype = $CardNum = $Address = $postal = $Expiry = $CVNo = $Bank = $captcha = "";
$usernameErr = $emailErr = $contactErr = $pwdErr = $genderErr = $FnameErr = $LnameErr = $pwd2Err = $birthdayErr = $CardtypeErr = $CardNumErr = $AddressErr = $postalErr = $ExpiryErr = $CVNoErr = $BankErr = $captchaErr = "";

//Error
$err = "no";
if (empty ($_POST ['Register'])) {
	$err = "yes";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	//check username
	if (empty($_POST["username"])) {
		$usernameErr = "Error! Username is required";
		$err = "yes";
	} else {
		$username = test_input($_POST["username"]);
		// check if username does not have whitespace
		if (!preg_match("/^[^\s]*$/",$username)) {
			$usernameErr = "Error! Username contains whitespace";
			$err = "yes";
		}
	}
	
	if (empty($_POST["pwd"])) {
		$pwdErr = "Error! Password is required";
		$err = "yes";
	} else {
		$pwd = test_input($_POST["pwd"]);
		// check if password contains at least 1 uppercase, lowercase letter, number & minimum 8 char length
		if (!preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",$pwd)) {
			$pwdErr = "Error! Ensure that password has at least 1 uppercase,lowercase letter and number";
			$err = "yes";
		}
	}
	
	if (empty($_POST["pwd2"])) {
		$pwd2Err = "Error! Please confirm password";
		$err = "yes";
	}
	else {
		$pwd2 = test_input ( $_POST ["pwd2"] );
		if ($pwd != $pwd2) {
			$pwd2Err = "Error! Passwords do not match";
			$err = "yes";
		}
	}
	//Check Fname
	if (empty($_POST["Fname"])) {
		$FnameErr = "Error! First name is required";
		$err = "yes";
	} else {
		$Fname = test_input($_POST["Fname"]);
		// check if Fname only contains letters
		if (!preg_match("/^[a-zA-Z]*$/",$Fname)) {
			$FnameErr = "Error! First name should only contain letters";
			$err = "yes";
		}
	}
	
	//Check Lname
	if (empty($_POST["Lname"])) {
		$LnameErr = "Error! Last name is required";
		$err = "yes";
	} else {
		$Lname = test_input($_POST["Lname"]);
		// check if Lname only contains letters
		if (!preg_match("/^[a-zA-Z]*$/",$Lname)) {
			$LnameErr = "Error! Last name should only contain letters";
			$err = "yes";
		}
	}

	//Check email
	if (empty($_POST["email"])) {
		$emailErr = "Error! Email is required";
		$err = "yes";
	} else {
		$email = test_input($_POST["email"]);
		// check if e-mail address is valid, regex expression below is the same one used in filter_var() based on a regex by Michael Rushton
		// /^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Error! Invalid email entered";
			$err = "yes";
		}
	}

	//check contact
	if (empty($_POST["contact"])) {
		$contactErr = "Error! Contact number is required";
		$err = "yes";
	} else {
		$contact = test_input($_POST["contact"]);
		// check if contact only contains 8 numbers
		if (!preg_match("/^([0-9]){8}$/",$contact)) {
			$contactErr = "Error! Please enter valid contact number";
			$err = "yes";
		}
	}

	//check gender
	if (empty($_POST["gender"])) {
		$genderErr = "Error! Gender is required";
		$err = "yes";
	} 
	else {
		$gender = test_input($_POST["gender"]);
	}
	
	//check birthday
	if (empty($_POST["birthday"])) {
		$birthdayErr = "Error! Birthday is required";
		$err = "yes";
	}
	else {
		$birthday = test_input($_POST["birthday"]);
	}
	
	//check Cardtype
	if (empty($_POST["Cardtype"])) {
		$CardtypeErr = "Error! Card type is required";
		$err = "yes";
	}
	else {
		$Cardtype = test_input($_POST["Cardtype"]);
	}
	
	//check CardNum
	if (empty($_POST["CardNum"])) {
		$CardNumErr = "Error! Card Number is required";
		$err = "yes";
	}
	else {
		$CardNum = test_input($_POST["CardNum"]);
		// check VISA valid number
		// Start with a 4, 16-13 digits
		//if (!preg_match("/^([0-9]){6}$/",$postal))
		if ($Cardtype == "Visa") {
			if (!preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/",$CardNum)) {
				$CardNumErr = "Error! Please enter valid VISA Card number";
				$err = "yes";
			}
		}
		// check mastercard valid number
		// Start with 51-55 or 2221-2720
		else {
			if (!preg_match("/^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$/",$CardNum)) {
				$CardNumErr = "Error! Please enter valid MasterCard number";
				$err = "yes";
			}
		}
	}
	
	//check Address
	if (empty($_POST["Address"])) {
		$AddressErr = "Error! Address is required";
		$err = "yes";
	}
	else {
		$Address = test_input($_POST["Address"]);
	}
	
	//check postal
	if (empty($_POST["postal"])) {
		$postalErr = "Error! Postal Code is required";
		$err = "yes";
	} else {
		$postal = test_input($_POST["postal"]);
		// check if contact only contains 8 numbers
		if (!preg_match("/^([0-9]){6}$/",$postal)) {
			$postalErr = "Error! Please enter valid Postal Code";
			$err = "yes";
		}
	}
	
	//check Expiry
	if (empty($_POST["Expiry"])) {
		$ExpiryErr = "Error! Expiry date is required";
		$err = "yes";
	}
	else {
		$Expiry = test_input($_POST["Expiry"]);
	}
	
	//check CVNo
	if (empty($_POST["CVNo"])) {
		$CVNoErr = "Error! CVNo Number is required";
		$err = "yes";
	} else {
		$CVNo = test_input($_POST["CVNo"]);
		// check if CVNo only contains 3 numbers
		if (!preg_match("/^([0-9]){3}$/",$CVNo)) {
			$CVNoErr = "Error! Please enter valid CV Number";
			$err = "yes";
		}
	}
	
	//check Bank
	if (empty($_POST["Bank"])) {
		$BankErr = "Error! Bank is required";
		$err = "yes";
	}
	else {
		$Bank = test_input($_POST["Bank"]);
	}
	
	if (empty($_POST ['g-recaptcha-response'])) {
		$captchaErr = "Error! Captcha is not checked";
		$err = "yes";
	}
	else {
		$captcha = test_input($_POST["Bank"]);
	}
}

//Uses the current User ID to find his Salt
$query = $con1->prepare ( "select * from user" ); 
$query->execute ();
$query->bind_result ( $d1, $d2, $d3, $d4, $Email, $Username, $d7, $d8, $d9, $d10, $d11);

//Check if email and User ID already in use
while ( $query->fetch () ) {
	if($email == $Email){
		$emailErr = "Error! Email already exists";
		$err = "yes";
	}
	if($username == $Username){
		$usernameErr = "Error! Username already exists";
		$err = "yes";
	}
}

// there are no errors
if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	if ($err == "no") { 
		// unique UID generator
		$UserID = uniqid ( '' ); 
		$UserID2 = $UserID;
		// Adds as User role
		$Role = 'Member'; 
		
		//HASHING FOR PASSWORD
		// generate a random salt value
		$salt = hash('sha256', uniqid(mt_rand(), true), $UserID);
		// concatenate salt and password
		$storedHashpwd = $salt.$pwd;
		// hash the concatenated salt and password
		for ($i=0; $i<50000; $i++)
		{
			$storedHashpwd=hash('sha256', $storedHashpwd);
		}
		// base64 encode the salt
		$base64saltpwd = base64_encode($salt);
		
		$query = $con1->prepare ( "INSERT INTO `user` (`UserID`, `FirstName`, `LastName`, `Gender`, `Email`, `Username`, `Password`, `Hash`, `Contact`, `Birthdate`, `Role`) VALUES (?,?,?,?,?,?,?,?,?,?,?)" );
		$query->bind_param ( 'sssssssssss', $UserID, $Fname, $Lname, $gender, $email, $username, $storedHashpwd, $base64saltpwd, $contact, $birthday, $Role);
	
	if ($query->execute ()) {
		echo "<br><br>Account successfully created.";
		$att = $fatt = 0;
		$time = "0000-00-00 00:00:00";
		//Add entry to login_attempts table in DB
		$query = $con2->prepare ("INSERT INTO `login_attempts`(`UserID`, `Attempts`, `LastLogin`, `FailAttempt`) VALUES (?,0,0,0)");
		$query->bind_param('s' ,$UserID2);
		$query->execute();
		if ($_SERVER ["REQUEST_METHOD"] == "POST") {
			
			//HASHING FOR CARD NUMBER
			// generate a random salt value
			$salt = hash('sha256', uniqid(mt_rand(), true), $UserID);
			// concatenate salt and password
			$storedHashcnum = $salt.$CardNum;
			// hash the concatenated salt and password
			for ($i=0; $i<50000; $i++)
			{
				$storedHashcnum=hash('sha256', $storedHashcnum);
			}
			// base64 encode the salt
			$base64saltcnum = base64_encode($salt);
			
			//HASHING FOR CV NUMBER
			// generate a random salt value
			$salt = hash('sha256', uniqid(mt_rand(), true), $UserID);
			// concatenate salt and password
			$storedHashcv = $salt.$CVNo;
			// hash the concatenated salt and password
			for ($i=0; $i<50000; $i++)
			{
				$storedHashcv=hash('sha256', $storedHashcv);
			}
			// base64 encode the salt
			$base64saltcv = base64_encode($salt);
			
			$lastFour = substr($CardNum, -4);
			$query = $con1->prepare ( "INSERT INTO `creditcard` (`CreditCardNo`, `CnumHash`, `Lastfour`, `UserID`, `CreditCardType`, `Billing_address`, `Postal`, `CardExpiryDate`, `CVNo`, `CVHash`, `IssuingBank`) VALUES (?,?,?,?,?,?,?,?,?,?,?)" );
			$query->bind_param ( 'sssssssssss', $storedHashcnum, $base64saltcnum, $lastFour, $UserID, $Cardtype, $Address, $postal, $Expiry, $storedHashcv, $base64saltcv, $Bank );
			if ($query->execute ()) {
				echo "<br>Card added";
			} 
			else {
				echo "<br>Error encountered during Credit card creation phase! Add your credit card in account page";
			}
		}
			else {
				echo "Fail Account add <br>";
			}
		}
	}
}

?>

<body>

	<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="https://localhost/botanist/homepage.php" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
		<!-- Float links to the right. Hide them on small screens -->
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome to Botanist Floral! Feel free to look around at our amazing bouquets!</marquee> 
	</ul>
</div>	

<!-- Javascript -->
	
	<script type="text/javascript">
	//js function to lock submit button until all forms filled
	function checkform() {
	    var f = document.forms["registerform"].elements;
	    var cansubmit = true;

	    for (var i = 0; i < f.length; i++) {
		    if (i == 19) {
				//grecaptcha: client side verification of recaptcha
				var response = grecaptcha.getResponse();
				if (response.length == 0) {
		            cansubmit = false;
				}
		    }
		    else {
		        if (f[i].value.length == 0) {
		            cansubmit = false;
		        }
		    }
	    }
	    document.getElementById('submit').disabled = !cansubmit;
	}
	
	</script>
	
	<form name="registerform" action="register.php" method="post">
		<h2>User Information</h2>
		<p><span class="error">* required field.</span></p>
		
		<!-- Username -->
		Username: <input type="text" name="username" value="<?php echo $username;?>" id="username" onkeyup="checkform()" />
		<span class="error">* <?php echo $usernameErr;?></span>
		<br><br>
		
		<!-- First Name -->
		First Name: <input type="text" name="Fname" value="<?php echo $Fname;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $FnameErr;?></span>
		<br><br>
		
		<!-- Last Name -->
		Last Name: <input type="text" name="Lname" value="<?php echo $Lname;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $LnameErr;?></span>
		<br><br>
		
		<!-- Password -->
		(Minimum 8 characters, at least 1 number, uppercase and lowercase letter)
		<br>
		Password: <input type="password" name="pwd" value="<?php echo $pwd;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $pwdErr;?></span>
		<br><br>
		
		<!-- Confirm pw -->
		Confirm Password: <input type="password" name="pwd2" value="<?php echo $pwd2;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $pwd2Err;?></span>
		<br><br>
		
		<!-- Email -->
		Email: <input type="text" name="email" value="<?php echo $email;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $emailErr;?></span>
		<br><br>
		
		<!-- Contact Number -->
		Contact Number: <input type="text" name="contact" value="<?php echo $contact;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $contactErr;?></span>
		<br><br>
		
		<!-- Gender -->
		Gender: 
		<input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female" onclick="checkform()" />Female
		<input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male" onclick="checkform()" />Male
		<span class="error">* <?php echo $genderErr;?></span>
		<br><br>
		
		<!-- Birthday -->
		Birthday:
  		<input type="date" name="birthday" value="<?php echo $birthday;?>" onkeyup="checkform()" onclick="checkform()"/>
  		<span class="error">* <?php echo $birthdayErr;?></span>
		<br><br>
		
		<br><br>
		<h2>Credit Card details</h2>
		
		<!-- Card Type -->
		Card Type: 
		<input type="radio" name="Cardtype" <?php if (isset($Cardtype) && $Cardtype=="Mastercard") echo "checked";?> value="Mastercard" onclick="checkform()" />Mastercard
		<input type="radio" name="Cardtype" <?php if (isset($Cardtype) && $Cardtype=="Visa") echo "checked";?> value="Visa" onclick="checkform()" />Visa
		<span class="error">* <?php echo $CardtypeErr;?></span>
		<br><br>
		
		<!-- Card Num -->
		Credit Card Number: <input type="text" name="CardNum" value="<?php echo $CardNum;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $CardNumErr;?></span>
		<br><br>
		
		<!-- Address -->
		Billing Address: <textarea name="Address" rows="5" cols="40" onkeyup="checkform()" ><?php echo $Address;?></textarea>
		<span class="error">* <?php echo $AddressErr;?></span>
  		<br><br>
  		
  		<!-- Postal Code -->
  		Postal Code: <input type="text" name="postal" value="<?php echo $postal;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $postalErr;?></span>
		<br><br>
		
		<!-- Expiry -->
		Expiry:
  		<input type="date" name="Expiry" value="<?php echo $Expiry;?>" onkeyup="checkform()" onclick="checkform()"/>
  		<span class="error">* <?php echo $ExpiryErr;?></span>
  		<br><br>
  		
  		<!-- CVNo -->
  		3 Digit CV Number: <input type="text" name="CVNo" value="<?php echo $CVNo;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $CVNoErr;?></span>
		<br><br>
		
		<!-- Bank -->
		Issuing Bank: <input type="text" name="Bank" value="<?php echo $Bank;?>" onkeyup="checkform()" />
		<span class="error">* <?php echo $BankErr;?></span>
		<br><br>
		
		<div class="g-recaptcha" data-sitekey="6Le3YQ0UAAAAAMDVg9oL2J28wUtKsODS4syRkKBt" data-callback="checkform" ></div>
		<span class="error"><?php echo $captchaErr;?></span>
		<br><br>
		 
		 
		<input id="submit" type="submit" name="Register" value="Register" disabled="disabled"/> 
		<br><br>
	</form>
</body>
</html>