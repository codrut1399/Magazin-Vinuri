<!DOCTYPE html>
<html>
<head>
<?php
session_start();
$emptyAdress=" ";

		
?>
<?php
$serverName = "DESKTOP-459V8DR\SQLEXPRESS";
$connectionInfo = array ("Database" => "Ferma_Viticola");
$conn = sqlsrv_connect ($serverName, $connectionInfo);

if (!$conn){
	echo"Connection failed";
}
?>
<link rel="stylesheet" href="Ferma.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="cos.css">
</head>



<body>


<div class="login-container" id="coscontainer">
	
		<div class="table-responsive">
		<table class="table">
			<tr>
				<th width="40%">Nume Produs </th>
				<th width="20%">Soi</th>
				<th width="10%">Cantitate </th>
				<th width="10%">Pret </th>
				<th width="10%">Total </th>
			</tr>
	<?php
	$total= 0;
	 if(isset($_SESSION['cart']))  //daca exista produse in cos, sunt afisate sub forma de tabel
	{ $idProd=array_column($_SESSION['cart'],'idProdus_cos');
		$query="SELECT Vinuri.ID_Vin, Vinuri.Denumire, Vinuri.Pret, Vinuri.Stoc_l, Soiuri.Nume_Soi FROM Vinuri LEFT JOIN Soiuri ON (Vinuri.ID_Soi=Soiuri.ID_Soi)"; //se selecteaza informatiile despre produsele din cos, din doua table
		$stmt=sqlsrv_query($conn, $query);
		
		while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
		foreach($_SESSION["cart"] as $keys => $values){
			if($row['ID_Vin']==$values['idProdus_cos']){
			
		   cartElement($row['Denumire'], $values["prod_cantitate"],$row['Pret'],$row['Nume_Soi']);
		   
			$total = $total+($values["prod_cantitate"] * $row['Pret']);
				}
				
			}
		}
	} 
	?>
		<tr>
			<th> Total de plata </th>
			<td align="right"> <?php echo number_format($total,2);?> lei</td>
		</tr>
		
		
		
	
	
</table>


	   <?php 
if(isset($_POST["emptyCart"])){
	$_SESSION["cart"]=array();
	header("location:Cos.php");
}
if(isset($_POST["home"]))
{
	header("location:Vinuri.php");
}
	?>
	
<?php
$flag=0;

	if(isset($_POST["adresa"])) //se verifica daca datele din formular sunt valide
	if(!empty($_POST["adresa"]))
	{$flag=1;
	$emptyAdress=" ";
	$adresa=$_POST["adresa"];
	$curier=$_POST["curier"];
	
	$id_Client=$_SESSION["id_client"];
	}
	else{
		$emptyAdress="Introduceti adresa";
	}
	
	$data=date("d/m/Y - h:i:sa");
	
	if(isset($_POST["comanda"]) && $flag){
	$query="INSERT INTO Comenzi(Data,Total,ClientID) VALUES('$data','$total','$id_Client')";  //introducere comanda in Comenzi
	sqlsrv_query($conn,$query);
	$getid="SELECT Comenzi.ComandaID FROM Comenzi JOIN Clienti ON (Comenzi.ClientID=Clienti.ClientID) 
			WHERE Comenzi.Data='$data' 
			AND Clienti.Email=(SELECT Clienti.Email FROM Clienti JOIN Comenzi ON (Comenzi.ClientID=Clienti.ClientID) WHERE Comenzi.Data='$data')"; //extragem ID-ul comenzii pe care tocmai am inregistrat-o
	$stmt=sqlsrv_query($conn,$getid);
	$result=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
	$idComanda=$result["ComandaID"];
	$query2="INSERT INTO Livrari (ComandaID, Adresa_Livrare, Curier) VALUES ('$idComanda','$adresa','$curier')"; //introducere detalii de livrare in Livrari
	sqlsrv_query($conn,$query2);
	
	$queryVin="SELECT ID_Vin FROM Vinuri";
	$stmt=sqlsrv_query($conn,$queryVin);
	while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
		foreach($_SESSION["cart"] as $keys => $values){
			if($row['ID_Vin']==$values['idProdus_cos']){
				$vin=$row['ID_Vin'];
				$cant=$values['prod_cantitate'];
				$query3="INSERT INTO Continut_Comanda_Vinuri(ComandaID, ID_Vin, Cantitate) VALUES ('$idComanda','$vin','$cant')"; //introducere Vinuri si cantitati in tabela de Continut comanda
				sqlsrv_query($conn,$query3);
			}
		}
	}
	
	
	}
	
	?>
	<div id="detalii">
	<form class="login-form" method="POST" action="Cos.php">
	<label for="adresa">Adresa de livrare: </label> <br>
	<input type="text" name="adresa"><br><br>
	<span class="error"><?php echo $emptyAdress?></span><br><br>
	<label for="curier">Alegeti firma de curierat: </label>
	<select name="curier">
		<option value="fancourier">Fan Courier</option>
		<option value="dhl">DHL</option>
	</select><br><br><br>
	<input id="final" type="submit"  name="comanda" value="Finalizare comanda">
	</form>
	
	
			
       </div>
</div>

<form method="POST">
<input type="submit" class="cartbutton" name="emptyCart" value="Goleste cosul"> <br> <br>
<input type="submit" class="cartbutton" name="home" value="Continua cumparaturile">
</form>
</div>





</div>
</body>

</html>

<?php
function cartElement($numeprod,$cantitate, $pret,$soi) //functie pentru afisarea datelor despre un produs din cos

{	echo '<tr class="produs-cos">';
	echo '<td>'.$numeprod.'</td>';
	echo '<td>'.$soi.'</td>';
	echo '<td>'.$cantitate.'</td>';
	echo '<td>'.$pret.'</td>';
	echo '<td>';
	echo number_format($cantitate * $pret,2);
	echo 'lei </td>';
	echo '<td style="text-align:center;" width="10%">';
	echo '</tr>';
}

if(isset($_SESSION["logged"])){
		if($_SESSION["logged"]!=1)
		echo '<script src="cos_script.js">','</script>';
	}
	else
	{echo '<script src="cos_script.js">','</script>';
	
	}

	
?>
<script>
var cart=document.getElementsByClassName('produs-cos');
 if (cart.length==0)
 {
	 document.getElementById("coscontainer").innerHTML='<div class="login-form"><span class="empty">Nu ati adaugat niciun produs in cos!</span><br><br><a class="cartbutton" href="Vinuri.php">Continuati cumparaturile</a><br><br></div>';
 }
 </script>
