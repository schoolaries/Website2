<?php
include('config.php');
include('userClass.php');
$userClass = new userClass();
$userDetails=$userClass->userDetails($_SESSION['uid']);

include('session.php');
$userDetails=$userClass->userDetails($session_uid);

if(isset($_POST["sub"])) {
	if($_POST["sub"]=="yes") {
	$email=$_POST['emailSub'];
	$email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
	
	
	if($email_check) //check condition to fuifill preg_match
	{
		$conn = mysqli_connect("localhost","root","P@ssw0rd!","demos");
		
		$query = $conn->prepare("INSERT INTO newsletter (Email) VALUES (?)");
		$query->bind_param('s',$email);
		if($query->execute()) {
			header('Location: '.$_SERVER['PHP_SELF']);
			die;
		}
		else {
			header('Location: errorSub.php');
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
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart</title>
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
	<a href="home.php">Home<a>
    <a href="#">About Us</a>
    <div class="w3-accordion">
      <a onclick="myAccFunc()" href="javascript:void(0)" class="w3-text-black" id="myBtn">
        LiquorStore <i class="fa fa-caret-down"></i>
      </a>
      <div id="demoAcc" class="w3-accordion-content w3-padding-large w3-medium">
        <a href="vodka.php#vodka">Vodka</a>
        <a href="chivas.php#chivas">Chivas</a>
        <a href="jack.php#jack">Jack Daniels</a>
        <a href="blanton.php#blanton">Blanton's</a>
		<a href="glenlivet.php#glenlivet">Glenlivet</a>
      </div>
    </div>
    <a href="#">All About Liquor</a>
  </div>
  <a href="#footer" class="w3-padding">Contact</a> 
  <a href="javascript:void(0)" class="w3-padding" onclick="document.getElementById('newsletter').style.display='block'">Newsletter</a> 
  <a href="#footer"  class="w3-padding">Subscribe</a>
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
      <a href="view_cart.php"><i class="fa fa-shopping-cart" style="padding: 5px"></i></a>
	  <a href="account.php"><i class="fa fa-bars"style="padding: 5px"></i></a>
    </p>
  </header>

  <!-- Image header -->
  <div class="w3-display-container w3-container">
    <img src="https://bestlocalspirits.files.wordpress.com/2015/05/plpromo-liquor-bottles-8662cb212c980cde.jpg" alt="Liquor" style="width:100%">
    <div class="w3-display-topleft w3-padding-xxlarge w3-text-black" style="background-color:white; opacity: 0.7">
		<div style="opacity: 1">
			<h1 class="w3-jumbo w3-hide-small" style="opacity: 1">New arrivals</h1>
			<h1 class="w3-hide-large w3-hide-medium" style="opacity:1">New arrivals</h1>
			<h1 class="w3-hide-small" style="opacity:1">COLLECTION 2016</h1>
			<p><a href="home.php#New Arrivals" class="w3-btn w3-padding-large w3-large" style="opacity:1">SHOP NOW</a></p>
		</div>
    </div>
  </div>
<h1 align="center">Shopping Cart</h1>
<div align="center">
<form method="post" action="cart_update.php">
<table width="100%"  cellpadding="6" cellspacing="0" style="max-width: 700px; background-color: #FFFFFF;box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.12);border: 1px solid #E4E4E4;">
    <thead>
    	<tr><th>Quantity</th><th>Name</th><th>Price</th><th>Total</th><th>Remove</th></tr>
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
			
		   	$bg_color = ($b++%2==1) ? 'odd' : 'even'; //class for zebra stripe 
		    echo '<tr class="'.$bg_color.'" align="center">';
			echo '<td><input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
			echo '<td>'.$product_name.'</td>';
			echo '<td>'.$currency.$product_price.'</td>';
			echo '<td>'.$currency.$subtotal.'</td>';
			echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /></td>';
            echo '</tr>';
			$total = ($total + $subtotal); //add subtotal to total var
        }
		
		$grand_total = $total + $shipping_cost; //grand total including shipping cost
		foreach($taxes as $key => $value){ //list and calculate all taxes in array
				$tax_amount     = round($total * ($value / 100));
				$tax_item[$key] = $tax_amount;
				$grand_total    = $grand_total + $tax_amount;  //add tax val to grand total
		}
		
		$list_tax       = '';
		foreach($tax_item as $key => $value){ //List all taxes
			$list_tax .= $key. ' : '. $currency. sprintf("%01.2f", $value).'<br />';
		}
		$shipping_cost = ($shipping_cost)?'Shipping Cost : '.$currency. sprintf("%01.2f", $shipping_cost).'<br />':'';
	}
	else {
		$shipping_cost = 0;
		$list_tax = null;
		$grand_total = null;
	}
	echo '<tr>';
	echo '<td colspan="5" align="right" style="padding-bottom: 20px">';
	echo '<a href="home.php" class="w3-btn w3-red" style="font-family: Arial; font-size: 14">Continue Shopping</a>';
	echo '<button type="submit" class="w3-btn w3-red" style="font-family: Arial; margin-left: 20px; padding:10px; font-size: 14">Update</button>';
	echo '</td>';
	echo '</tbody>';
	echo '</table>';
 echo '<div>';
if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
	
}
else {
	$shipping_cost = null;
	$list_tax = null;
	$grand_total = null;
}
?>
<div class="cart-view-table-front" id="view-total">
	<h3>Grand Total</h3>
	<form method="post" action="cart_update.php">
	<table width="100%"  cellpadding="6" cellspacing="0">
	<tbody>
	<tr>
    <td>
    <span style="font-size: 16px; font-family: Arial;">
    <b><?php echo $shipping_cost. $list_tax; ?>Amount Payable : $<?php echo sprintf("%01.2f", $grand_total);?></b>
    </span>
    </td>
    </tr>
	<tr>
    <td colspan="5">
	<a href="paypal-express-checkout" ><img src="https://news.androidout.com/wp-content/uploads/sites/3/sites/3/2014/05/paypal.png" width="180" height="80"></a>
	</td>
	</tr>
	</tbody>
	</table>
	</form>
	<input type="hidden" name="return_url" value="<?php 
	$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo $current_url; ?>" />
	</form>
</div>
<br>
  <!-- Subscribe section -->
  <div class="w3-container w3-black w3-padding-32">
    <h1>Subscribe</h1>
    <p>To get special offers and VIP treatment:</p>
	<form method=POST action="" name="sub">
    <p><input class="w3-input w3-border" type="email" name="emailSub" placeholder="Enter e-mail" style="width:100%"></p>
    <button type="submit" class="w3-btn w3-padding w3-red w3-margin-bottom" value="yes" name="sub">Subscribe</button>
	</form>
  </div>
  
  <!-- Footer -->
  <footer class="w3-padding-64 w3-light-grey w3-small w3-center" id="footer">
    <div class="w3-row-padding">
      <div class="w3-col s4">
        <h4>Contact</h4>
        <p>Questions? Go ahead.</p>
        <form action="form.asp" target="_blank">
          <p><input class="w3-input w3-border" type="text" placeholder="Name" name="Name" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Email" name="Email" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Subject" name="Subject" required></p>
          <p><input class="w3-input w3-border" type="text" placeholder="Message" name="Message" required></p>
          <button type="submit" class="w3-btn-block w3-padding w3-black">Send</button>
        </form>
      </div>

      <div class="w3-col s4">
        <h4>About</h4>
      <p><a href="#" onclick="document.getElementById('about').style.display='block'">About us</a></p>
      </div>

      <div class="w3-col s4 w3-justify">
        <h4>Store</h4>
        <p><i class="fa fa-fw fa-map-marker"></i> Liquorholic</p>
        <p><i class="fa fa-fw fa-phone"></i> +65 12345678</p>
        <p><i class="fa fa-fw fa-envelope"></i> ex@mail.com</p>
        <h4>We accept</h4>
        <p><i class="fa fa-fw fa-cc-amex"></i> Visa</p>
        <p><i class="fa fa-fw fa-credit-card"></i> Master</p>
        <br>
        <a href="https://www.facebook.com/ming.kang.752?fref=ts" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"><i class="fa fa-facebook-official w3-xlarge w3-hover-text-indigo"></i>
		<a href="https://www.instagram.com/ianpswaries/" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"/><i class="fa fa-instagram w3-xlarge w3-hover-text-purple"></i>
        <a href="#" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"/><i class="fa fa-twitter w3-xlarge w3-hover-text-light-blue"></i>
        <a href="#" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"/><i class="fa fa-pinterest w3-xlarge w3-hover-text-red"></i>
        <a href="#" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"/><i class="fa fa-flickr w3-xlarge w3-hover-text-blue"></i>
      </div>
    </div>
  </footer>
  
<!-- Newsletter Modal -->
<div id="newsletter" class="w3-modal">
  <div class="w3-modal-content w3-animate-zoom w3-padding-jumbo">
    <div class="w3-container w3-white w3-center">
	  <form method="post" action="" name="sub">
      <i onclick="document.getElementById('newsletter').style.display='none'" class="fa fa-remove w3-closebtn w3-xlarge w3-hover-text-grey w3-margin"></i>
      <h2 class="w3-wide">NEWSLETTER</h2>
      <p>Join our mailing list to receive updates on new arrivals and special offers.</p>
      <p><input class="w3-input w3-border" type="email" placeholder="Enter e-mail" name="emailSub"></p>
      <button type="submit" class="w3-btn w3-padding-large w3-red w3-margin-bottom" value="yes" name="sub">Subscribe</button>
	  </form>
    </div>
  </div>
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

</script>
</body>
</html>
