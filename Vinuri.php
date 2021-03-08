<!DOCTYPE html>
<?php 
	session_start();
?>
<?php
$serverName = "DESKTOP-459V8DR\SQLEXPRESS";
$connectionInfo = array ("Database" => "Ferma_Viticola");
$conn = sqlsrv_connect ($serverName, $connectionInfo);

if (!$conn){
	echo"Connection failed";
}
?>
<?php

if(isset($_POST["add"])){
	if(isset($_SESSION["cart"]))
	{	$count=count($_SESSION['cart']); //numarul de itemi din cos
		$prod_array_index=array_column ($_SESSION["cart"], "idProdus_cos");
		if(!in_array($_POST["idProdus_cos"], $prod_array_index)) 
		{
			$prod_array=array(
			'idProdus_cos' 			=> $_POST["idProdus_cos"],
			'prod_cantitate'		=> $_POST["quantity"]
		);
			$_SESSION["cart"][$count]=$prod_array; //daca produsul nu se gaseste deja in cos, se adauga in vectorul de produse pe pozitia $count
		}
		else
		echo '<script>alert("Produsul este deja in cos")</script>';
		
	}
	else{
		
		$prod_array = array(
		'idProdus_cos' 			=> $_POST["idProdus_cos"],
		'prod_cantitate'		=> $_POST["quantity"]
		);
		$_SESSION["cart"][0]=$prod_array;
	}

} //la apasarea butonului Adauga in cos, se verifica daca produsul se mai regaseste in cos si in caz contrar se adauga
		
	

?>

<html>
<head>

<link rel="stylesheet" href="Ferma.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="produse.css">
<title> Crama lu' Codrut - Vinuri </title>
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
	<form method="POST" action="index.php">
	<input class="dropdownbtn" type="submit" name="vinuri" value="Vinuri">
	</form>
	<div class="dropdown-content">
		<form method="POST" action="index.php">
	
		<input class="dropdownform" type="submit" name="rosu" value="Rosu">
		<input class="dropdownform" type="submit" name="alb" value="Alb">
		<input class="dropdownform" type="submit" name="roze" value="Roze">
	</form>
	
	</div>
	</li>
	<li class="rightbutton"><a class="navbara" href="Cos.php"><i class="fa fa-shopping-cart" style="font-size:20px"></i> Cos(<?php

                    if(isset($_SESSION['cart'])){        //afisare numar de produse din cos
                      $count=count($_SESSION['cart']);
                      echo $count;
                    }else echo "0";  

                ?>)</a></li>
	<li id="login" class="rightbutton"><a class="navbara" href="login.php">Login</a></li>
</ul>
</div>

	<?php
	


	if(isset($_SESSION["logged"])){   //verificare daca utilizatorul este logat
		if($_SESSION["logged"]==1)
		echo '<script src="loggedin.js">','</script>';
	}
	$tip=$_SESSION['tip'];
	if($tip!=" "){
	$query="SELECT Vinuri.ID_Vin, Vinuri.Denumire,Vinuri.Pret,PozeVinuri.Poza FROM Vinuri  LEFT JOIN PozeVinuri ON(Vinuri.ID_Vin=PozeVinuri.ID_Vin) WHERE Tip='$tip'"; //se selecteaza vinul in functie de tip: rosu alb sau roze
	}
	else{
	$query="SELECT Vinuri.ID_Vin, Vinuri.Denumire,Vinuri.Pret,PozeVinuri.Poza FROM Vinuri  LEFT JOIN PozeVinuri ON(Vinuri.ID_Vin=PozeVinuri.ID_Vin)"; //se selecteaza toate vinurile, indiferent de tip
	}
	$stmt=sqlsrv_query($conn,$query);
	?>
<!--End Navigation Bar-->

<!--Breadcrumb-->
<div class="breadcrumb">
<h1>Vinuri</h1>
</div>
<!--End Breadcrumb-->
<!--products-->


	<div id="product-container">
	<?php  
		while($result=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){ //se extrage fiecare rand din tabela
	?>


	<div class="produs">
		<div class="imgProd">
			<?php echo '<img src="pozeVinuri/'.$result['Poza'].'.jpg">';?>
		</div>
		<div class="numProd">
			<?php echo $result['Denumire'];?>
		</div>
		<div class="pretProd">
			<?php echo $result['Pret']." lei";?>
		</div>
		<div class="butonCos">
		<form method="POST" action="Vinuri.php" class="cardform" >
			<input type="text" name="quantity" class="cantitate" maxlength="2" value="1"><br><br>
			<button type="submit" name="add" id="btnCos">Adauga in Cos</button>
			<input type="hidden" name="idProdus_cos" value="<?php echo $result['ID_Vin'];?>">
		</form>
		</div>
		
		
	</div>
	
	<?php	
	}?>
	</div>

	<div class="footer">
	
</div>
	
</div>

<!--End products-->

</body>



</html>