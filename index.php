<!DOCTYPE html>
<html>
<head>
<?php
session_start();
?>
<link rel="stylesheet" href="Ferma.css">
<link rel="stylesheet" href="produse.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title> Crama lu' Codrut </title>
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>


<body>
<div id="mainpage">
<!--Navigation Bar-->
<div class="navbar">
<ul>
	<li id="homebutton"><a class="navbara" href="index.php">Home</a></li>
	<li class="leftbutton"><a class="navbara" href="#struguri">Soiuri</a></li>
	<li class="left dropdown" >
	<form method="POST" action="Vinuri.php">
	<input class="dropdownbtn" type="submit" name="vinuri" value="Vinuri">
	</form>
	<div class="dropdown-content">
	<form method="POST" action="index.php">
	
		<input class="dropdownform" type="submit" name="rosu" value="Rosu">
		<input class="dropdownform" type="submit" name="alb" value="Alb">
		<input class="dropdownform" type="submit" name="roze" value="Roze">
	</form>
	<?php 
	$_SESSION['tip']=" ";
	if(isset($_POST['vinuri'])){
		if($_POST['vinuri']){
			$_SESSION['tip']=" ";
			header("location:Vinuri.php");
		}
	}
	if(isset($_POST['rosu'])){
		if($_POST['rosu']){
			$_SESSION['tip']='R';
			header("location:Vinuri.php");
		}
	}
	if(isset($_POST['alb'])){
		if($_POST['alb']){
			$_SESSION['tip']='A';
			header("location:Vinuri.php");
		}
	}
	if(isset($_POST['roze'])){
		if($_POST['roze']){
			$_SESSION['tip']='RZ';
			header("location:Vinuri.php");
		}
	}
	echo $_SESSION['tip'];
	?>
		
	</div>
	</li>
	<li class="rightbutton"><a class="navbara" href="Cos.php"><i class="fa fa-shopping-cart" style="font-size:20px"></i> Cos(<?php

                    if(isset($_SESSION['cart'])){
                      $count=count($_SESSION['cart']);
                      echo $count;
                    }else echo "0";

                ?>)</a></li>
	<li id="login" class="rightbutton"><a class="navbara" href="login.php">Login</a></li> 
</ul>
</div>

	<?php
	
	

	if(isset($_SESSION["logged"])){
		if($_SESSION["logged"]==1)
		echo '<script src="loggedin.js">','</script>';
	}

	
	?>
<!--End Navigation Bar-->

<!--Breadcrumb-->
<div class="breadcrumb">
<h1>Crama lu' Codrut</h1>
</div>
<!--End Breadcrumb-->
<!--Products-->
<div id="product-container">
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	
</div>

<!--End Products-->



<div class="footer">


</div>

</body>



</html>