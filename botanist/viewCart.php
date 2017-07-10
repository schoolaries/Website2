<?php
include_once("config.php");
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/viewCart.php");
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
?>
<html>
<head>
<title>Shopping Cart</title>
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
			<a href="viewCart.php" class="w3-left">Shopping Cart</a>
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


<h1 align="center">My Shopping Cart</h1>
<div class="cart-view-table-back">

<form method="post" action="cartUpdate.php">
<table width="100%"  cellpadding="6" cellspacing="0">
	<thead>
		<tr>
			<th>Quantity</th>
			<th>Name</th>
			<th>Price</th>
			<th>Total</th>
			<th>Remove</th>
		</tr>
	</thead>
		
	<tbody>
	<?php
	if(isset($_SESSION["cart_products"])) //check session var
	{
		$total = 0; //set initial total value
		$b = 0; //var for zebra stripe table 
		
		foreach ($_SESSION["cart_products"] as $cart_itm)
		{
			//set variables to use in content below
			$product_name = $cart_itm["product_name"];
			$product_qty = $cart_itm["product_qty"];
			$product_price = $cart_itm["product_price"];
			$product_code = $cart_itm["product_code"];
			$subtotal = ($product_price * $product_qty); //calculate Price x Qty
			
			echo '<td><input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
			echo '<td>'.$product_name.'</td>';
			echo '<td>'.$currency.$product_price.'</td>';
			echo '<td>'.$currency.$subtotal.'</td>';
			echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /></td>';
			echo '</tr>';
				
			$total = ($total + $subtotal); //add subtotal to total var
		}
		
		$grand_total = $total + $shipping_cost; //grand total including shipping cost
		$_SESSION['Cost'] = $grand_total;
		
		foreach($taxes as $key => $value)
		{ 
			//list and calculate all taxes in array
			$tax_amount     = round($total * ($value / 100));
			$tax_item[$key] = $tax_amount;
			$grand_total    = $grand_total + $tax_amount;  //add tax val to grand total
		}
		
		$list_tax="";
		
		foreach($tax_item as $key => $value)
		{ 
			//List all taxes
			$list_tax .= $key. ' : '. $currency. sprintf("%01.2f", $value).'<br />';
		}
		
		$shipping_cost = ($shipping_cost)?'Shipping Cost : '.$currency. sprintf("%01.2f", $shipping_cost).'<br />':'';
	}
	?>
	
	<tr>
		<td colspan="5"><span style="float:right;text-align: right;"><?php echo $shipping_cost. $list_tax; ?>Amount Payable : <?php echo sprintf("%01.2f", $grand_total);?></span></td>
	</tr>
	
    <tr>
		<td colspan="5"><a href="product.php" class="button">Add More Items</a><button type="submit">Update</button><a href="paypal-express-checkout" ><img src="images/paypal1.png" width="179" height="36"></a></td>
	</tr>
	</tbody>
</table>

<input type="hidden" name="return_url" value="<?php 
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
echo $current_url; ?>" />
</form>
</div>

</body>
</html>