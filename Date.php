<!DOCTYPE html>
<html>
<head>
<title>Date personale</title>
<link rel="stylesheet" href="Ferma.css">
<link rel="stylesheet" href="User_data.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
function editPhone(){
	document.getElementById("phone").innerHTML='<form action="Date.php" method="POST"><label for="telefon">Telefon</label><br><input type="text" id="telefon" name="telefon"><input id="savePhone" type="submit" name="submit" value="Salveaza"></form>';
}

function gender(){
	document.getElementById("icon").innerHTML='<img src="https://img.icons8.com/dusk/240/000000/circled-user-female-skin-type-7.png"/>';
}
</script>
</head>
<?php
session_start();
if(isset($_SESSION["logged"])){
		if($_SESSION["logged"]!=1)			
		header("location:index.php");		//daca se incearca revenirea prin back la pagina "datele mele" fara ca utilizatorul sa fie logat, se redirectioneaza spre prima pagina
}
$serverName = "DESKTOP-459V8DR\SQLEXPRESS";
$connectionInfo = array ("Database" => "Ferma_Viticola");
$conn = sqlsrv_connect ($serverName, $connectionInfo);

if (!$conn)
	echo"Connection failed";

$username=$_SESSION['user'];
$query="SELECT * FROM Clienti WHERE Email='$username'"; //extragere date pentru clientul logat
$stmt=sqlsrv_query($conn,$query);
$result=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


?>

<?php 
$err=" ";
$tlf=" ";
$flag=1;
if(isset($_POST['submit'])){
	if (empty($_POST['telefon'])){
		$flag=0;
		header("location:Date.php");
	}else {$tlf=$_POST['telefon'];
	}
	
	
	if(!empty($tlf)&&!preg_match("/^(07)[0-9]{8}$/",$tlf)){
		$err="Introduceti un numar invalid";
		$flag=0;
	}
	
	if ($flag){
	$query="UPDATE Clienti SET Telefon='$tlf' WHERE Email='$username'"; //numarul de telefon poate fi schimbat de utilizator din interfata "datele mele"
	sqlsrv_query($conn,$query);
	header("location:Date.php");
	}

	
	
}
?>


<body>


<div id="data-container" > <br><br><br><br><br>
<div id="data">
<label for="nume">Nume   </label><br>
<span  class="data" name="nume" ><?php echo $result['Nume'];?></span><br><br>
<label for="prenume">Prenume </label><br>
<span   class="data" name="prenume"><?php echo $result['Prenume'];?></span><br><br>
<label for="email">Adresa de email</label><br>
<span class="data" name="email"><?php echo $result['Email'];?></span><br><br>

<span id="phone">
<label for="phone">Telefon </label> <button class="button" onclick="editPhone()"><i class="fa fa-edit"></i></button><br>
<span class="data" name="phone"><?php echo $result ['Telefon'];?></span>
</span>

<span class="error"><?php echo $err; ?></span>
<br><br><br>
<a id="homebtn" class="loginbutton" href="index.php">Home</a>
</div>

<div id="icon">
<img src="https://img.icons8.com/cute-clipart/240/000000/user-male-circle.png"/>
</div>



<?php if ($result['Sex']=='F'){
		
		echo  '<script type=\'text/javascript\'>','gender()','</script>';     //afisare avatar in functie de sex
}
?>
</body>
</html>