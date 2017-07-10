<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);

include('session.php');
$userDetails=$userClass->userDetails($session_uid);

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/google/");
	die();
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
    <a href="#">Products</a>
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
      <i class="fa fa-search"></i>	
	  <a href="#"><i class="fa fa-bars"style="padding: 5px"></i></a>
    </p>
  </header>
  <div>
  <h1>Administrator Settings</h1>
  </div>
  <h3 style="padding-top: 20px"> Add A Product </h3>
  <div>
  <form method="post" action="insertpro.php">
	<table  border="0" style="padding:20px">
		<tr>
			<td>
				<label><b>Product Code: </b></label>
			</td>
			<td>
				<input type="text" name="product_code" />
			</td>
		<tr>
			<td>
				<label><b>Product Name: </b></label>
			</td>
			<td>
				<input type="text" name="product_name" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Product Description: </b></label>
			</td>
			<td>
				<input type="text" name="product_desc" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Product Image: </b></label>
			</td>
			<td>
				<input type="file" name="product_img_name" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Price: </b></label>
			</td>
			<td>
				<input type="text" name="price" />
			</td>
		</tr>
		<tr>
		<td colspan="2" align="center">
		<input class="w3-btn w3-red"type="submit" value="Add Product"/>
</td>
</tr>
</table>
</form>
  
  <?php
$connect=mysqli_connect("localhost","root","","demos");
$query=$connect->prepare("select * from products");
$query->execute();
$query->bind_result($id,$product_code,$product_name,$product_desc,$product_img_name,$price );
echo "<table align='center' border='1' style='border-collapse: collapse; padding:20px'>";
echo "<tr>";
echo "<td>Id</td>";
echo "<td>Product Code/td>";
echo "<td>Product Name</td>";
echo "<td>Product Description</td>";
echo "<td width='100'>Product Image File</td>";
echo "<td>Price</td>";
echo "<td colspan='2'>Function</td>";
echo "</tr>";
while($query->fetch())
{
	echo "<tr>";
	echo "<td width='50' align='center'>".$id."</td>";
	echo "<td>".$product_code."</td>";
	echo "<td>".$product_name."</td>";
	echo "<td>".$product_desc."</td>";
	echo "<td>".$product_img_name."</td>";
	echo "<td>".$price."</td>";
	echo "<td><a href='editpro.php?operation=edit&id=".$id."&product_code=".$product_code."&product_name=".$product_name."&product_desc=".$product_desc."&product_img_name=".$product_img_name."&price=".$price."'>edit</a></td>";
	echo "<td><a href='delepro.php?operation=delete&id=".$id."'>delete</a></td>";
	echo "</tr>";	
	
}
echo "</table>";
?>

</body>
</html>