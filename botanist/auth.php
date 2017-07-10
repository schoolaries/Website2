<html>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">

<body>	
<?php
include 'function.php';
require_once('dbconnect.php');

session_name('id');
session_start();
//This is the old Session ID
$oldSID = session_id();
//Creates a new Session ID to be used
session_regenerate_id();
//This is the new Session ID
$newSID = session_id();
//Write and stop session
session_commit();
//Opens old session
session_id($oldSID);
//Start old Session
session_start();
//Destroy session
session_destroy();
// Stop the Session
session_commit();
//Opens new session
session_id($newSID);
//Start new session
session_start();
//Ensures that the user browse in https

if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/auth.php");
	die();
}

$_SESSION ['LogRole'] = "Guest";

$myusername = $mypassword = $lastlogin = $attempts = $uid = $password = $salt = $lockd = "";
$timecheck = date('Y-m-d H:i:s');
$myusername2 = test_input ( $_POST["myusername"] );
$mypassword2 = test_input ( $_POST["mypassword"] );

if (!empty($myusername2)) { 
	$query = $con1->prepare ( "select UserID, Username, Password, Hash from user where Username=?" );
	$query->bind_param('s',$myusername2);
	$query->execute();
	$query->bind_result ($id, $u, $p, $h);
	while ( $query->fetch () ) {
		$uid = $id;
		$username = $u;
		$password = $p;
		$salt = $h;
	}
	if (!empty($password)){
		if(!empty($mypassword2)){
			$query = $con1->prepare ( "select attempts, LastLogin from login_attempts where UserID=?" );
			$query->bind_param('s',$uid);
			$query->bind_result ($att, $last);
			$query->execute();
			while ( $query->fetch ()) {
				$attempts = $att;
				$lastlogin = $last;
			}
			if ($timecheck < $lastlogin){
				echo "Lockout";
				$lockd = "yes";
			}
			//Prevent bruteforce
			else{
				if ($attempts == 3){
					if($timecheck >= $lastlogin && $lockd == "yes") {
						// reactivate the account
						$attempts = "0";
						$query = $con1->prepare( "update login_attempts set Attempts=? where UserID=?" );
						$query->bind_param('ss',$attempts, $uid);
						$query->execute();
						$lockd = "no";
					}
					else {
						echo "Account Locked for 5 mins";
						$lockd = "yes";
						$locktime = date('Y-m-d H:i:s', strtotime("+5 min"));
						$query = $con1->prepare ( "update login_attempts set LastLogin=? where UserID=?" );
						$query->bind_param('ss', $locktime, $uid);
						$query->execute();
					}
				}
				else{
					$time = date('Y-m-d H:i:s');
					$query = $con1->prepare ( "update login_attempts set LastLogin=?, Attempts= Attempts + 1, FailAttempt = FailAttempt + 1 where UserID=?" );
					$query->bind_param('ss', $time, $uid);
					$query->execute();
				}
				//Use function verifypw to authenticate user
				if (verifypw($mypassword2, $password, $salt)){
					$query = $con1->prepare ( "select * from user where Username=?" );
					$query->bind_param('s',$myusername2);
					$query->execute();
					$query->bind_result ( $ID, $FName, $LName, $Gender, $Email, $Username, $Password, $Hash, $Contact, $Birthdate, $Role);
					while ( $query->fetch () ) {
						//check id exists if yes login
						if (isset($ID)) { 
							$oldSID = session_id(); 
							session_regenerate_id(); 
							$newSID = session_id(); 
							session_destroy();
							session_commit(); 
							session_id($newSID);
							session_start();
							//variables for session
							$_SESSION ['LogRole'] = $Role;
							$_SESSION ['LogID'] = $ID;
							$_SESSION ['LogEmail'] = $Email;
							$_SESSION ['LogUser'] = $Username;
							$_SESSION ['LogFName'] = $FName;
							$_SESSION ['LogLName'] = $LName;
							$_SESSION['LAST_ACTIVITY'] = time();
							//store current IP into session variable
							$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR']; 
							//store current browser into session 
							$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT']; 
							$_SESSION['Token'] = hash('sha256',time() . $ID ,FALSE);
							if($Role=="Admin"){
								$_SESSION['ManageProduct'] = "False";
							}
							if($Role=="Member") {
								$_SESSION['ManageProfile'] = "False";
							}
							echo "LOGGEDIN";
						}
						//reset amount of attempts
						$attempts = "0";
						$reset = $con2->prepare ( "update login_attempts set Attempts=?, FailAttempt = FailAttempt - 1, LastLogin=? where UserID=?" );
						$reset->bind_param('sss', $attempts, $timecheck, $ID);
						$reset->execute();
					}
				}
				else {
					echo "<strong>Password is invalid</strong>";
				}
			}
		}
		else {
			echo "<strong>Invalid username</strong>";
		}
	}
}
//login and password empty
else {
	echo "<strong>Please enter username and password</strong>";
}
if ($_SESSION['LogRole'] == "Member") {
	// redirect user to member page if role is member
	header("location:navbarMember.php");
}
else if($_SESSION['LogRole'] == "Admin") {
	// redirect user to admin page if role is admin
	header("location:navbarAdmin.php");
}
?>
<br><br><br><br>
<form action="https://localhost/botanist/signedout.php">
    <input type="submit" value="Back to homepage" style="height:50px;width:200px"/>
</form>
</body>
</html>
