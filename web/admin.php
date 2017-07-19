
<?php
include('config.php');


if ($_SESSION['name']!="admin")
{
        header("Location: http://ec2-54-201-219-185.us-west-2.compute.amazonaws.com/web/index.php");
}
//Sign up account
if (!empty($_POST['signup']))
{

        $name=$_POST['name'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $confirmpassword=$_POST['confirmpassword'];
        $handphone=$_POST['handphone'];
        $address=$_POST['address'];
        $creditno=$_POST['creditno'];
        $code=$_POST['code'];



        $conn = new mysqli("localhost","root","123","Shop");
        if ($conn->connect_error)
        {
          die("Connection failed: " . $conn->connect_error);
        }
        $query = "INSERT INTO user (name,password,email,handphone,address,creditno,code) VALUES ('$name','$password','$email','$handphone','$address','$creditno','$code')";

                if ($conn->query($query) === TRUE)
                {
                        echo "New record created successfully";
                }
                else
                {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                }
$conn->close();

}
//Login account
if(!empty($_POST["login"]))
{
        $name=$_POST['name'];
        $password=$_POST['password'];

        $conn = new mysqli("localhost","root","123","Shop");
        if ($conn->connect_error)
        {
          die("Connection failed: " . $conn->connect_error);
        }
        $query = "INSERT INTO baduser (name,password) VALUES ('$name','$password')";

                if ($conn->query($query) === TRUE)
                {
                        echo "New record created successfully";
			header("Location: http://ec2-54-201-219-185.us-west-2.compute.amazonaws.com/web/index.php");
                }
                else
                {
                        echo "Error: " . $sql . "<br>" . $conn->error;
			header("Location:http://ec2-54-201-219-185.us-west-2.compute.amazonaws.com/web/malicious.php");
                }
$conn->close();

}
        
//Search bar
if(!empty($_GET["searchSubmit"]))
{
        $search=$_GET['search'];
        //echo $search;
        //connect  to the database
        $conn = mysql_connect("localhost","root","123")or die('Error Connecting to Database on the SQL Server');
        //-select  the database to use
        mysql_select_db("Shop")or die("cannot select DB");
        $sql = "SELECT * FROM products where name LIKE '%" .$search."%'";
        $r_query = mysql_query($sql);

        while($row = mysql_fetch_array($r_query))
        {
                echo "Name: " .$row['name'];
                echo "<br /> Description: " .$row['description'];
                echo "<br /> Price: " .$row['price'];
        }
}
if(!empty($_POST["submit2"]))
{
          //echo "hello";
          $email2=$_POST['email2'];
          //connect  to the database
          $conn = mysql_connect("localhost","root","123")or die('Error Connecting to Database on the SQL Server');
          //-select  the database to use
          mysql_select_db("Shop")or die("cannot select DB");
          //-query  the database table
          $query="INSERT INTO subscribe (email) VALUES ('$email2')";
          //-create  while loop and loop through result set
          $result=mysql_query($query,$conn);

         if(! $result ) {
         die('Could not enter data: ' . mysql_error());
        }

        echo "Entered data successfully\n";

mysql_close($conn);

}

if ($_SESSION['name']=="admin")
{
if (!empty($_POST["update"]))
{
	$id=$_POST['id'];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $handphone=$_POST['handphone'];
        $address=$_POST['address'];
        $creditno=$_POST['creditno'];
        $code=$_POST['code'];



        $conn = new mysqli("localhost","root","123","Shop");
        if ($conn->connect_error)
        {
          die("Connection failed: " . $conn->connect_error);
        }
        $query = "UPDATE user set name='$name', email='$email',password='$password',handphone='$handphone',address='$address',creditno='$creditno',code='$code'	WHERE id='$id'";
                if ($conn->query($query) === TRUE)
                {
                        echo "New record created successfully";
                }
                else
                {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                }
$conn->close();

}
}
else
{
        header("Location: http://ec2-54-201-219-185.us-west-2.compute.amazonaws.com/web/index.php");
}






$connect=mysqli_connect("localhost","root","123","Shop"); //connect to database
if(isset($_GET['operation']))
{
	if($_GET['operation']=="delete")
	{
		$query=$connect->prepare("delete from user where id=".$_GET['id']); //delete the data
		if($query->execute()) //run the query
		{
			echo "<center>Record Deleted!</center><br>"; //This is to show to the user that data has been deleted.
		}
	}
}



?>


<!DOCTYPE html>
<html>
<head>
<title>ShopShopLah an Ecommerce Online Shopping Category Flat Bootstrap Responsive Website Template | About :: w3layouts</title>
<!--/tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="ShopShopLah Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--//tags -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/team.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //for bootstrap working -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,900,900italic,700italic' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- header -->
<!-- header -->
<div class="header" id="home">
        <div class="container">
                <ul>
                        <?php if(!isset($_SESSION['name'])){ ?>
                    <li> <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Sign In </a></li>
                        <li> <a href="#" data-toggle="modal" data-target="#myModal2"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign Up </a></li> <?php } else{ ?> <li><div align="left: 150px";><a href="logout.php">Logout</a></div></li>
                        <?php }?>
                        <li><i class="fa fa-phone" aria-hidden="true"></i> Call : +65 6888 8888</li>
                        <li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:info@example.com">info@shopshoplah.com</a></li>
                </ul>
        </div>
</div>

<!-- //header -->
<!-- header-bot -->
<div class="header-bot">
	<div class="header-bot_inner_wthreeinfo_header_mid">
		<div class="col-md-4 header-middle">
			<form action="#" method="post">
				<input type="search" name="search" placeholder="Search here..." required="">
					<input type="submit" value=" ">
				<div class="clearfix"></div>
			</form>
		</div>
		<!-- header-bot -->
			<div class="col-md-4 logo_agile">
				<h1><a href="index.php"><span>S</span>hopShopLah <i class="fa fa-shopping-bag top_logo_agile_bag" aria-hidden="true"></i></a></h1>
			</div><div align=right><h2><br><i><?php if(!isset($_SESSION['name']) || empty($_SESSION['name'])){echo "Welcome, Guest!";}else { echo "Welcome, " . $_SESSION['name']; }?></i></br></h2></div>
        <!-- header-bot -->
		<div class="col-md-4 agileits-social top_content">
						<ul class="social-nav model-3d-0 footer-social w3_agile_social">
						                                   <li class="share">Share On : </li>
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>



		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //header-bot -->
<!-- banner -->
<div class="ban-top">
	<div class="container">
		<div class="top_nav_left">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse menu--shylock" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav menu__list">
					<li class="active menu__item"><a class="menu__link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
					<li class=" menu__item menu__item--current"><a class="menu__link" href="about.php">About</a></li>
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Men's wear <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="mens.php"><img src="images/top2.jpg" alt=" "/></a>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="mens.php">Clothing</a></li>
											<li><a href="mens.php">Wallets</a></li>
											<li><a href="mens.php">Footwear</a></li>
											<li><a href="mens.php">Watches</a></li>
											<li><a href="mens.php">Accessories</a></li>
											<li><a href="mens.php">Bags</a></li>
											<li><a href="mens.php">Caps & Hats</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="mens.php">Jewellery</a></li>
											<li><a href="mens.php">Sunglasses</a></li>
											<li><a href="mens.php">Perfumes</a></li>
											<li><a href="mens.php">Beauty</a></li>
											<li><a href="mens.php">Shirts</a></li>
											<li><a href="mens.php">Sunglasses</a></li>
											<li><a href="mens.php">Swimwear</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Women's wear <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="womens.php">Clothing</a></li>
											<li><a href="womens.php">Wallets</a></li>
											<li><a href="womens.php">Footwear</a></li>
											<li><a href="womens.php">Watches</a></li>
											<li><a href="womens.php">Accessories</a></li>
											<li><a href="womens.php">Bags</a></li>
											<li><a href="womens.php">Caps & Hats</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="womens.php">Jewellery</a></li>
											<li><a href="womens.php">Sunglasses</a></li>
											<li><a href="womens.php">Perfumes</a></li>
											<li><a href="womens.php">Beauty</a></li>
											<li><a href="womens.php">Shirts</a></li>
											<li><a href="womens.php">Sunglasses</a></li>
											<li><a href="womens.php">Swimwear</a></li>
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img multi-gd-text ">
										<a href="womens.php"><img src="images/top1.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					<li class="menu__item dropdown">
					   <a class="menu__link" href="#" class="dropdown-toggle" data-toggle="dropdown">Short Codes <b class="caret"></b></a>
								<ul class="dropdown-menu agile_short_dropdown">
									<li><a href="icons.php">Web Icons</a></li>
									<li><a href="typography.php">Typography</a></li>
								</ul>
					</li>
					<li class=" menu__item"><a class="menu__link" href="contact.php">Contact</a></li>
				  </ul>
				</div>
			  </div>
			</nav>	
		</div>
		<div class="top_nav_right">
			<div class="wthreecartaits wthreecartaits2 cart cart box_1"> 
						<form action="#" method="post" class="last"> 
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="display" value="1">
						<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
					</form>  
  
						</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //banner-top -->
<!-- Modal1 -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
						<div class="modal-body modal-body-sub_agile">
						<div class="col-md-8 modal_body_left modal_body_left1">
						<h3 class="agileinfo_sign">Sign In <span>Now</span></h3>
									<form action="#" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" name="name" required="">
								<label>Name</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input type="password" name="password" required=""> 
								<label>Password</label>
								<span></span>
							</div> 
							<input type="submit" name="login" value="Sign In">
						</form>
						  <ul class="social-nav model-3d-0 footer-social w3_agile_social top_agile_third">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
														<div class="clearfix"></div>
														<p><a href="#" data-toggle="modal" data-target="#myModal2" > Don't have an account?</a></p>

						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<img src="images/log_pic.jpg" alt=" "/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- //Modal content-->
			</div>
		</div>
<!-- //Modal1 -->
<!-- Modal2 -->
		<div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
						<div class="modal-body modal-body-sub_agile">
						<div class="col-md-8 modal_body_left modal_body_left1">
						<h3 class="agileinfo_sign">Sign Up <span>Now</span></h3>
									<form action="admin.php" method="post" >
                                                        <div class="styled-input agile-styled-input-top">
                                                                <input type="text" name="name" required="">
                                                                <label>Name</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="email" name="email" required="">
                                                                <label>Email</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="password" name="password" required="">
                                                                <label>Password</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="password" name="confirmPassword" required="">
                                                                <label>Confirm Password</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="text" name="handphone" required="">
                                                                <label>Handphone</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="text" name="address" required="">
                                                                <label>Address</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="text" name="creditno" required="">
                                                                <label>Credit Card Number</label>
                                                                <span></span>
                                                        </div>
                                                        <div class="styled-input">
                                                                <input type="text" name="code" required="">
                                                                <label>CVC</label>
                                                                <span></span>
                                                        </div>
                                                        <input type="submit" value="sign up" name="signup">
                                                </form>

						  <ul class="social-nav model-3d-0 footer-social w3_agile_social top_agile_third">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
														<div class="clearfix"></div>
														<p><a href="#">By clicking register, I agree to your terms</a></p>

						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<img src="images/log_pic.jpg" alt=" "/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- //Modal content-->
			</div>
		</div>
<!-- //Modal2 -->

<br>
<br>
<p style="text-align:center"><font size="50">Admin privilege editing customer database</font></p>

<?php
$connect=mysqli_connect("localhost","root","123","Shop"); //connect to database
$query=$connect->prepare("select * from user");
$query->execute();
$query->bind_result($id, $name,$password,$email,$handphone,$address,$creditno,$code );  //get the input of user and put in database.
echo "<table align='center' border='2'>";
echo "<tr>";
echo "<td> ID</td>"; 			//create table  call ID
echo "<td> Name</td>";		//create table  call Question
echo "<td> Password</td>";		//create table  call Matric_No
echo "<td> Email</td>";			//create table  call Name
echo "<td> Handphone</td>";		//create table  call Contact
echo "<td> Address</td>";		//create table  call Email
echo "<td> Creditno</td>";
echo "<td> Code</td>";  
echo "</tr>";
while($query->fetch())   //from the database, fetch the data and display out.
{
	echo "<tr>";
	echo "<td>".$id."</td>";				//Call out the data of ID from database
	echo "<td>".$name."</td>";			//Call out the data of question from database
	echo "<td>".$password."</td>";			//Call out the data of matric from database
	echo "<td>".$email."</td>";				//Call out the data of name from database
	echo "<td>".$handphone."</td>";			//Call out the data of contact from database
	echo "<td>".$address."</td>";				//Call out the data of email from database
        echo "<td>".$creditno."</td>";
        echo "<td>".$code."</td>";
	echo "<td><a href='edit.php?operation=edit&id=".$id."&name=".$name."&password=".$password."&email=".$email."&handphone=".$handphone."&address=".$address."&creditno=".$creditno."&code=".$code."'>edit</a></td>";
	echo "<td><a href='admin.php?operation=delete&id=".$id."'>delete</a></td>"; //delete the data by clicking the ahref link.
	echo "</tr>";

}
echo "</table>";
?>


<br>
<br>										


<!--/grids-->
<div class="coupons">
		<div class="coupons-grids text-center">
			<div class="w3layouts_mail_grid">
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-truck" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>FREE SHIPPING</h3>
						<p>Lorem ipsum dolor sit amet, consectetur</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-headphones" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>24/7 SUPPORT</h3>
						<p>Lorem ipsum dolor sit amet, consectetur</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-shopping-bag" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>MONEY BACK GUARANTEE</h3>
						<p>Lorem ipsum dolor sit amet, consectetur</p>
					</div>
				</div>
					<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-gift" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>FREE GIFT COUPONS</h3>
						<p>Lorem ipsum dolor sit amet, consectetur</p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>

		</div>
</div>
<!--grids-->
<!-- footer -->
<div class="footer">
	<div class="footer_agile_inner_info_w3l">
		<div class="col-md-3 footer-left">
			<h2><a href="index.php"><span>E</span>lite Shoppy </a></h2>
			<p>Lorem ipsum quia dolor
			sit amet, consectetur, adipisci velit, sed quia non 
			numquam eius modi tempora.</p>
			<ul class="social-nav model-3d-0 footer-social w3_agile_social two">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
		</div>
		<div class="col-md-9 footer-right">
			<div class="sign-grds">
				<div class="col-md-4 sign-gd">
					<h4>Our <span>Information</span> </h4>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="mens.php">Men's Wear</a></li>
						<li><a href="womens.php">Women's wear</a></li>
						<li><a href="about.php">About</a></li>
						<li><a href="typography.php">Short Codes</a></li>
						<li><a href="contact.php">Contact</a></li>
					</ul>
				</div>
				
				<div class="col-md-5 sign-gd-two">
					<h4>Store <span>Information</span></h4>
					<div class="w3-address">
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Phone Number</h6>
								<p>+1 234 567 8901</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Email Address</h6>
								<p>Email :<a href="mailto:example@email.com"> mail@example.com</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Location</h6>
								<p>Broome St, NY 10002,California, USA. 
								
								</p>
							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
				</div>
				<div class="col-md-3 sign-gd flickr-post">
					<h4>Flickr <span>Posts</span></h4>
					<ul>
						<li><a href="single.php"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="single.php"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
			<div class="agile_newsletter_footer">
					<div class="col-sm-6 newsleft">
				<h3>SIGN UP FOR NEWSLETTER !</h3>
			</div>
			<div class="col-sm-6 newsright">
				<form action="#" method="post">
					<input type="email" placeholder="Enter your email..." name="email" required="">
					<input type="submit" value="Submit">
				</form>
			</div>

		<div class="clearfix"></div>
	</div>
		<p class="copy-right">&copy 2017 Elite shoppy. All rights reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
	</div>
</div>
<!-- //footer -->

<!-- login -->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
						</div>
						<div class="modal-body modal-spa">
							<div class="login-grids">
								<div class="login">
									<div class="login-bottom">
										<h3>Sign up for free</h3>
										<form>
											<div class="sign-up">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Type here';}" required="">	
											</div>
											<div class="sign-up">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
												
											</div>
											<div class="sign-up">
												<h4>Re-type Password :</h4>
												<input type="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
												
											</div>
											<div class="sign-up">
												<input type="submit" value="REGISTER NOW" >
											</div>
											
										</form>
									</div>
									<div class="login-right">
										<h3>Sign in with your account</h3>
										<form>
											<div class="sign-in">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Type here';}" required="">	
											</div>
											<div class="sign-in">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
												<a href="#">Forgot password?</a>
											</div>
											<div class="single-bottom">
												<input type="checkbox"  id="brand" value="">
												<label for="brand"><span></span>Remember Me.</label>
											</div>
											<div class="sign-in">
												<input type="submit" value="SIGNIN" >
											</div>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
								<p>By logging in you agree to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- //login -->
<a href="#home" class="scroll" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<!-- //js -->	
	<!-- cart-js -->
	<script src="js/minicart.min.js"></script>
<script>
	// Mini Cart
	paypal.minicart.render({
		action: '#'
	});

	if (~window.location.search.indexOf('reset=true')) {
		paypal.minicart.reset();
	}
</script>

	<!-- //cart-js --> 

<!-- stats -->
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/jquery.countup.js"></script>
	<script>
		$('.counter').countUp();
	</script>
<!-- //stats -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/jquery.easing.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->

<!-- for bootstrap working -->
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

