<?php
if(isset($_POST["update"])){
	if($_POST["update"]== "yes")
	{
$con = mysqli_connect("localhost","root","P@ssw0rd!","demos"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}
$id=$_POST["id"];
$query= $con->prepare("UPDATE products set product_code=?, product_name=? , product_desc=? , product_img_name=?, price=? where id = $id");
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
	}
}
?>
<br> 
Successfully registered.
<br>
<?php header("Location: products.php"); ?>
</body>
</html> 

