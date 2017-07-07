<?php
session_start();
/* DATABASE CONFIGURATION */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Passw0rd!');
define('DB_DATABASE', 'demos');
define("BASE_URL", "http://localhost/google/"); // Eg. http://yourwebsite.com


function getDB() //connect to database
{
	$dbhost=DB_SERVER; //localhost
	$dbuser=DB_USERNAME; //root
	$dbpass=DB_PASSWORD; // Passw0rd!
	$dbname=DB_DATABASE; // demos
	try {
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
		$dbConnection->exec("set names utf8");
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}
	catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

}
?>
<?php
$currency = '$'; //Currency Character or code

//MySql 
$db_username 	= 'root';
$db_password 	= 'Passw0rd!';
$db_name 		= 'demos';
$db_host 		= 'localhost';

//paypal settings
$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'ian-aries-facilitator_api1.hotmail.com'; //PayPal API Username
$PayPalApiPassword 		= '4WQTALZV6SDLXRZC'; //Paypal API password
$PayPalApiSignature 	= 'AFcWxV21C7fd0v3bYYYRCpSSRl31ApcTUP1ibYBnbQKXLoCqH-RcxE4w'; //Paypal API Signature
$PayPalCurrencyCode 	= 'SGD'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://yoursite.com/php-shopping-cart-master/paypal-express-checkout/'; //Point to paypal-express-checkout page
$PayPalCancelURL 		= 'http://yoursite.com/shopping-cart/paypal-express-checkout/cancel_url.html'; //Cancel URL if user clicks cancel

//Additional taxes and fees											
$HandalingCost 		= 0.00;  //Handling cost for the order.
$InsuranceCost 		= 0.00;  //shipping insurance cost for the order.
$shipping_cost      = 1.50; //shipping cost
$ShippinDiscount 	= 0.00; //Shipping discount for this order. Specify this as negative number (eg -1.00)
$taxes              = array( //List your Taxes percent here.
                            'VAT' => 12, 
                            'Service Tax' => 5
                            );

//connection to MySql						
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);						
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>