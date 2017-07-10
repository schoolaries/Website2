<?php
include_once("config.php");
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/product.php");
	die();
}

$err = "false";
$autherror = "false";
$nnerror = "False";
//Prevent Session Forging
$role = "Guest";
$SessionError = "";

//Role Check
if(isset($_SESSION['LogID'])){
	if(isset($_SESSION['Token'])){
		$currID = test_input($_SESSION['LogID']);
		$query = $con1->prepare ( "select role from user where UserID=?" );
		$query->bind_param('s',$currID);
		$query->execute ();
		$query->bind_result ($Role);
		while ( $query->fetch () ) {
			$role = $Role;
		}
	}
}

if($role != "Member") {
	$nnerror = "True";
}
//Check if user is logged in
if(isset($_SESSION['Token'])){
	if(!preventHijacking()) {
		$SessionError = "Hijack";
	}
}

if($SessionError == "Hijack"){
	//Terminates the compromised session ID
	session_destroy();
	session_commit();
	//Generate new Session ID
	session_start();
	//store current IP into session variable
	$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
	//store current browser into session variable
	$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
	//Redirect the user
	header("Location: https://localhost/botanist/signedout.php");
}

//Check if logged in
if ($nnerror == "True") {
	$autherror = "true";
}

//Set timeout duration
$timeoutduration = 300;
if(isset($_SESSION['LAST_ACTIVITY'])){
	//check if session is destroyed
	if(time() - $_SESSION['LAST_ACTIVITY'] > $timeoutduration){
		//Destroy sessions
		session_unset();
		session_destroy();
		echo("<meta http-equiv='refresh' content='0'>");
		$autherror = "true";
	}
	else{
		$_SESSION['LAST_ACTIVITY'] = time();
	}
}
else{
	$_SESSION['LAST_ACTIVITY'] = time();
}

if ($autherror == "true") {
	$err = "true";
}

if ($err == "true") {
	header("Location: https://localhost/botanist/signedout.php");
}

//current URL of the Page. cartUpdate.php redirects back to this URL
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<html>
<head>
<title>Product Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="assets/css/paypalstyle.css" rel="stylesheet" type="text/css">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="assets/css/w3style.css">

</head>

<body>
<br><br><br>

<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="navbarMember.php" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">
			<a href="product.php" class="w3-left">Products</a>
			<?php 
			if(isset($_SESSION["cart_products"])) {?>
			<a href="viewCart.php" class="w3-left">Shopping Cart</a>
			<?php 
			}
			?>
			<a href="navbarMember.php#editProfile" class="w3-left">Edit Profile</a>
			<a href="navbarMember.php#showcases" class="w3-left">Showcases</a>
			<a href="navbarMember.php#about" class="w3-left">About</a>
			<a href="contactus.php" class="w3-left">Contact us</a>
			<a href="signedout.php" class="w3-left w3-margin-right">Logout</a>
		</li>
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome back <?php echo $_SESSION['LogFName']." ".$_SESSION['LogLName'];?>! This is the member panel of Botanist Floral!</marquee> 
	</ul>
</div>	


<h1 align="center">Products </h1>

<!-- View Cart Box Start -->
<?php
if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
	echo '<div class="cart-view-table-front" id="view-cart">';
	echo '<h3>Your Shopping Cart</h3>';
	echo '<form method="post" action="cartUpdate.php">';
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
		echo '<td>Qty <input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
		echo '<td>'.$product_name.'</td>';
		echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /> Remove</td>';
		echo '</tr>';
		$subtotal = ($product_price * $product_qty);
		$total = ($total + $subtotal);
	}
	
	echo '<td colspan="4">';
	echo '<button type="submit">Update</button><a href="viewCart.php" class="button">Checkout</a>';
	echo '</td>';
	echo '</tbody>';
	echo '</table>';
	
	$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
	echo '</form>';
	echo '</div>';
}
?>

<!-- View Cart Box End -->


<!-- Products List Start -->
<?php
$results = $mysqli->query("SELECT ProductCode, ProductName, ProductDesc, ProductImgName, Price FROM products ORDER BY ProductID ASC");

if($results)
{ 
	$products_item = '<ul class="products">';
	
	//fetch results set as object and output HTML
	while($obj = $results->fetch_object())
	{
		$products_item .= <<<EOT
		<li class="product">
		<form method="post" action="cartUpdate.php">
		<div class="product-content"><h3>{$obj->ProductName}</h3>
		<div class="product-thumb"><img src="images/{$obj->ProductImgName}"></div>
		<div class="product-desc">{$obj->ProductDesc}</div>
		<div class="product-info">
		Price {$currency}{$obj->Price} 
	
		<fieldset>
	
		<label>
			<span>Quantity</span>
			<input type="text" size="2" maxlength="2" name="product_qty" value="1" />
		</label>
	
		</fieldset>
		
		<input type="hidden" name="product_code" value="{$obj->ProductCode}" />
		<input type="hidden" name="type" value="add" />
		<input type="hidden" name="return_url" value="{$current_url}" />
		<div align="center"><button type="submit" class="add_to_cart">Add</button></div>
		</div>
		</div>
		</form>
		</li>
EOT;
	}
	
	$products_item .= '</ul>';
	echo $products_item;
}
?>    
<!-- Products List End -->
</body>
</html>
