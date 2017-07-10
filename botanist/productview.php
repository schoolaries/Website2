<?php
include_once("config.php");
include 'function.php';
require_once 'dbconnect.php';

session_name('id');
session_start();

//Ensures that the user browse in https
if(!isset($_SERVER['HTTPS'])){
	header("Location: https://localhost/botanist/productview.php");
	die();
}

$role = "Guest"; //Prevent Session Forging

$timeoutduration = 300; //Set timeout duration
if(isset($_SESSION['LAST_ACTIVITY'])) {
	if(time()- $_SESSION['LAST_ACTIVITY'] > $timeoutduration) { //check if session is destroyed
		session_unset(); //Destroy sessions
		session_destroy();
		header("location: homepage.php");
	}
}
$_SESSION['LAST_ACTIVITY'] = time();
?>
<html>
<head>
<title>Product Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="assets/css/paypalstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="assets/css/w3style.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">


</head>

<body>
<!-- Navbar (sit on top) -->
<div class="w3-top">
	<ul class="w3-navbar w3-white w3-wide w3-padding-8 w3-card-2">
		<li>
			<a href="https://localhost/botanist" class="w3-margin-left"><b>Botanist Floral</b></a>
		</li>
		
<!-- Float links to the right. Hide them on small screens -->
		<li class="w3-right w3-hide-small">
            <a class="launch-modal w3-left" href="#" data-modal-id="modal-login">Login</a>	
			<a href="register.php" class="w3-left">Register</a>
			<a href="https://localhost/botanist/productview.php" class="w3-left">Product</a>
			<a href="https://localhost/botanist/homepage.php#showcases" class="w3-left">Showcases</a>
			<a href="https://localhost/botanist/homepage.php#about" class="w3-left w3-margin-right">About</a>
		</li>
		<br />
		<marquee direction="left" speed="normal" behavior="loop" >Welcome to Botanist Floral! Feel free to look around at our amazing bouquets!</marquee> 
	</ul>
</div>	

<!-- MODAL -->
<form name="form1" method="post" action="auth.php">
<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
        		</button>	
				<h3 class="modal-title" id="modal-login-label">Login to our site</h3>
        		<p>Enter your username and password to log on:</p>	
        	</div>
        			
		<div class="modal-body">
			<form role="form" action="" method="post" class="login-form">
				<div class="form-group">
					<label class="sr-only" for="form-username">Username</label>
					<input type="text" name="myusername" placeholder="Username..." class="form-username form-control" id="myusername">
				</div>
	        
				<div class="form-group">
					<label class="sr-only" for="form-password">Password</label>
					<input type="password" name="mypassword" placeholder="Password..." class="form-password form-control" id="mypassword">
				</div>
					<button type="submit" id="submit" name="submit" class="btn">Sign in!</button>
			</form>
		</div>
		</div>
    </div>
</div>
</form>

<!-- Javascript -->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/scripts.js"></script>

<br><br><br>

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
$results = $con1->query("SELECT ProductCode, ProductName, ProductDesc, ProductImgName, Price FROM products ORDER BY ProductID ASC");

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
		</div>
		</div>
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
