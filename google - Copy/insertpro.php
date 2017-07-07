<html>
<body>
<?php
function check_price($text)
{
	if(isset($text))
	{
		if (!empty($text) && preg_match("/^^([0-9]{0,2}((.)[0-9]{0,2}))$/", $text)) //a-z and spaces only
		{
			return TRUE;
		}
		return FALSE;
	}
}

function check_name($text)
{
	if(isset($text))
	{
		if (!empty($text) && preg_match("/^[A-Za-z0-9 _]*$/", $text)) //a-z and spaces only
		{
			return TRUE;
		}
		return FALSE;
	}
}


if(!check_name($_POST["product_code"]))
{
	echo "Registration fail.";
	echo "Please input a valid code. A valid name only contain alphabetic letter and spaces.";
	exit();
}

if(!check_name($_POST["product_name"]))
{
	echo "Registration fail.";
	echo "Please input a valid name. A valid contact only contain 8 numeric digits.";
	exit();
}

if(!check_name($_POST["product_desc"]))
{
	echo "Registration fail.";
	echo "Please input a valid desc.";
	exit();
}

if(!check_price($_POST["price"]))
{
	echo "Registration fail.";
	echo "Please input a valid price. A valid matric only contain alphabetic letter and number.";
	exit();
}

$con = mysqli_connect("localhost","root","P@ssw0rd!","demos"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}
$query= $con->prepare("INSERT INTO `products` (`product_code`,`product_name`, `product_desc`, `product_img_name`, `price`) VALUES
(?,?,?,?,?)");

$product_code=$_POST["product_code"];
$product_name=$_POST["product_name"];
$product_desc=$_POST["product_desc"];
$product_img_name=$_POST["product_img_name"];
$price=$_POST["price"];
$query->bind_param('ssssd', $product_code,$product_name,$product_desc,$product_img_name,$price); //bind the parameters
if ($query->execute())
{  //execute query
echo "Query executed.";
}
else
{
	echo "Error executing query.";
}

?>
<br> 
Successfully registered.
<br>
<?php header("Location: products.php"); ?>
</body>
</html> 