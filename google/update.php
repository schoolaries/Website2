<?php
session_start();

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
			echo "INVALID DETAILS";
		}
	}
}

?>