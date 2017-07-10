<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);

include('session.php');
$userDetails=$userClass->userDetails($session_uid);

//Ensures that the user browse in https
/*if(!isset($_SERVER['HTTP'])){
	header("Location: http://ec2-34-211-115-232.us-west-2.compute.amazonaws.com/google");
	die();
}*/
if ($_SESSION['uid']!="3000")
{
	header("Location: http://ec2-34-211-115-232.us-west-2.compute.amazonaws.com/google/home.php");
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
	<a href="viewaccount.php">Home</a>
	<a href="viewusers.php">Users</a>
    <a href="products.php">Products</a>
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
    </p>
  </header>
  <div>
  <h1>Product Update</h1>
  </div>
<form method="post" action="updatepro.php">
<table align="center" border="0">
	<tr>
		<td>
			<label><b>Product Code: </b></label> 
		</td>
		<td>
			<input type="text" name="product_code" value="<?php echo $_GET['product_code']; ?>"/>
		</td>
	</tr>
	<tr>
		<td>
			<label><b> Product Name: </b></label>
		</td>
		<td>
			<input type="text" name="product_name" value="<?php echo $_GET['product_name']; ?>"/>
		</td>
	</tr>
	<tr>
		<td> 
			<label><b>Prouct Description: </b></label> 
		</td>
		<td>
			<input type="text" name="product_desc" value="<?php echo $_GET['product_desc']; ?>"/>
		</td>
	</tr>
		<td>
			<label><b>Product Image: </b></label>
		</td>
		<td>
			<input type="file" name="product_img_name" value="<?php echo $_GET['product_img_name']; ?>"/>
		</td>
	</tr>
		<td>
			<label><b>Price: </b></label>
		</td>
		<td>
			<input type="text" name="price" value="<?php echo $_GET['price']; ?>"/>
		</td>
	</tr>
	<tr>
		<td align="right" colspan="2">
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
		<input type="hidden" name="update" value="yes" />
		<input class="w3-btn w3-red" type="submit" value="update Record"/>
		</td>	
	</tr>
</table>
</form>
  <div>
  <?php
$conn=mysqli_connect("localhost","root","P@ssw0rd!","demos");
if(isset($_POST["user"])) {
	if($_POST["user"]=="yes") {
		$username = $_POST['uname'];
		$query=$conn->prepare("select * from users WHERE username=?");
		$query->bind_param('s',$username);
		$query->execute();
			$query->bind_result($uid, $username,$email,$password,$firstname,$lastname,$contactnum,$streetAddress,$unitNumber,$postal,$birthday,$profile_pic,$google_auth_code );
			echo "<table align='center' border='1' style='border: 1px solid black; border-collapse: collapse'>";
			echo "<tr>";
			echo "<td>UID</td>";
			echo "<td>username</td>";
			echo "<td>email</td>";
			echo "<td>firstname</td>";
			echo "<td>lastname</td>";
			echo "<td>contactnum</td>";
			echo "<td>streetAddress</td>";
			echo "<td>unitNumber</td>";
			echo "<td>postal</td>";
			echo "<td>birthday</td>";
			echo "<td>profile_pic</td>";
			echo "<td>function</td>";
			echo "</tr>";
			while($query->fetch())
			{
				echo "<tr>";
				echo "<td>".$uid."</td>";
				echo "<td>".$username."</td>";
				echo "<td>".$email."</td>";
				echo "<td>".$firstname."</td>";
				echo "<td>".$lastname."</td>";
				echo "<td>".$contactnum."</td>";
				echo "<td>".$streetAddress."</td>";
				echo "<td>".$unitNumber."</td>";
				echo "<td>".$postal."</td>";
				echo "<td>".$birthday."</td>";
				echo "<td>".$profile_pic."</td>";
				echo "<td><a href='delete2.php?operation=delete&uid=".$uid."'>delete</a></td>";
				echo "</tr>";	
	
			}
		}
	}	
echo "</table>";
?>
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
</script>

</body>
</html>
