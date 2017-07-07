<?php
class userClass
{
	/* User Login */
	public function userLogin($usernameEmail,$password,$secret)
	{

		$db = getDB();
		
		$stmt = $db->prepare("SELECT FROM users WHERE (uid=:uid)");
		$stmt->bindParam("uid", $uid, PDO::PARAM_STR);
		$stmt = $db->prepare("SELECT uid FROM users WHERE  (username=:usernameEmail or email=:usernameEmail) AND  password=:password");
		$stmt->bindParam("usernameEmail", $usernameEmail,PDO::PARAM_STR) ;
		$stmt->bindParam("password", $password,PDO::PARAM_STR) ;
		$stmt->execute();
		$count=$stmt->rowCount();
		$data=$stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		if($count)
		{
			$_SESSION['uid']=$data->uid;
			$_SESSION['google_auth_code']=$google_auth_code;
			return true;
		}
		else
		{
			return false;
		}
	}

	/* User Registration */
	public function userRegistration($username,$password,$email,$firstname,$lastname,$contactnum,$streetAddress,$unitNumber,$postal,$bday,$secret)
	{
		try{
			$db = getDB();
			$st = $db->prepare("SELECT uid FROM users WHERE username=:username OR email=:email");
			$st->bindParam("username", $username,PDO::PARAM_STR);
			$st->bindParam("email", $email,PDO::PARAM_STR);
			$st->execute();
			$count=$st->rowCount();
			if($count<1)
			{
				$stmt = $db->prepare("INSERT INTO users(username,password,email,firstname,lastname,contactnum,streetAddress,unitNumber,postal,birthday,google_auth_code) VALUES (:username,:password,:email,:firstname,:lastname,:contactnum,:streetAddress,:unitNumber,:postal,:birthday,:google_auth_code)");
				$stmt->bindParam("username", $username,PDO::PARAM_STR) ;
				$password= hash('sha256', $password);
				$salt = md5($username);
				$spwd = "{$password}{$salt}";
				$pass = hash('sha256', $spwd);
				$stmt->bindParam("password", $pass,PDO::PARAM_STR) ;
				$stmt->bindParam("email", $email,PDO::PARAM_STR) ;
				$stmt->bindParam("firstname", $firstname,PDO::PARAM_STR) ;
				$stmt->bindParam("lastname", $lastname,PDO::PARAM_STR) ;
				$stmt->bindParam("contactnum", $contactnum,PDO::PARAM_STR) ;
				$stmt->bindParam("streetAddress", $streetAddress,PDO::PARAM_STR) ;
				$stmt->bindParam("unitNumber", $unitNumber,PDO::PARAM_STR);
				$stmt->bindParam("postal", $postal,PDO::PARAM_STR);
				$stmt->bindParam("birthday", $bday,PDO::PARAM_STR);
				$stmt->bindParam("google_auth_code", $secret,PDO::PARAM_STR) ;
				$stmt->execute();
				$uid=$db->lastInsertId();
				$db = null;
				$_SESSION['uid']=$uid;
				return true;

			}
			else
			{
				$db = null;
				return false;
			}

			 
		}
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	 
	/* User Details */
	public function userDetails($uid)
	{
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT email,username,firstname,lastname,contactnum,streetAddress,unitNumber,postal,google_auth_code FROM users WHERE uid=:uid");
			$stmt->bindParam("uid", $uid,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		}
		catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

	}
	


}
?>