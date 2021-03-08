<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="Ferma.css">
<script>
function inregistrare(){
	 document.getElementById("register").innerHTML="Inregistrare reusita"; 
	 document.getElementById("register").style.color = "green";
	 document.getElementById("register").style.fontSize = "60px";
	 setTimeout("location.href = 'login.php';", 3000);
} //functie pentru afisarea mesajului de inregistrare reusita
</script>
<?php 

$serverName = "DESKTOP-459V8DR\SQLEXPRESS";
$connectionInfo = array ("Database" => "Ferma_Viticola");
$conn = sqlsrv_connect ($serverName, $connectionInfo);

if (!$conn){
	echo"Connection failed";
}

$numeErr=$prenumeErr=$sexErr=$pwdErr=$emailErr=$tlfErr="";
$nume=$prenume=$sex=$pwd=$email=$tlf="";

$flag=1;
if(isset($_POST['Submit'])) //la apasarea butonului Submit, se verifica daca datele din fiecare camp sunt valide
{  
	if(empty($_POST['fname'])) {
		$numeErr="Numele este obligatoriu";   //daca datele lipsesc sau sunt invalide, se genereaza un mesaj de eroare
		$flag=0;
	}else {$nume=$_POST['fname'];
	}
		if(empty($_POST['lname'])) {
		$prenumeErr="Prenumele este obligatoriu";
		$flag=0;
	}else {$prenume=$_POST['lname'];
	}
	if(empty($_POST['sex'])) {
		$sexErr="Genul este obligatoriu";
		$flag=0;
	}else {$sex=$_POST['sex'];
	}
	
	if(empty($_POST['pwd'])) {
		$pwdErr="Setati o parola";
		$flag=0;
	}else {$pwd=password_hash($_POST['pwd'],PASSWORD_DEFAULT); //parola este salvata in baza de date in format codificat
	}

	if(empty($_POST['email'])) {
		$emailErr="Adresa de email este obligatorie";
		$flag=0;
	}else {$email=$_POST['email'];
	}
	
	$persjur=$_POST['juridica'];
	
	if (!empty($email)&&!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailErr = "Format Invalid";
		$flag=0;
		}
		
	if(empty($_POST['phone'])){
		$tlfErr="Numarul de telefon este obligatoriu";
		$flag=0;
	}else {$tlf=$_POST['phone'];
	}
	
	if(!empty($tlf)&&!preg_match("/^(07)[0-9]{8}$/",$tlf)){
		$tlfErr="Numarul de telefon este invalid";
		$flag=0;
	}

if($flag){                   
	
sqlsrv_query($conn,"INSERT INTO Clienti (Nume,Prenume,Email, Parola, Sex,Telefon, Persoana_juridica) VALUES ('$nume', '$prenume','$email','$pwd','$sex','$tlf','$persjur')");

} //daca toate datele sunt valide se insereaza in baza de date in tabela Clienti

}

 ?>
 

</head>

<body>

<div id="register"  class="login-container">
	<form class="login-form" action="Register.php" method="POST">

		<span class="error">*Camp obligatoriu</span><br><br>
		<label for="fname">Nume:</label><br>
		<input type="text" id="fname" name="fname"><span class="error">*<?php echo $numeErr;?></span>
		<br>
		<label for="lname">Prenume:</label><br>
		<input type="text" id="lname" name="lname"><span class="error">*<?php echo $prenumeErr;?></span>
		<br>
		<label for="pwd">Parola:</label> <br>
		<input type="password" id="pwd" name="pwd"><span class="error">*<?php echo $pwdErr;?></span>
		<br>
		<label for="email">Email: </label><br>
		<input type="text" id="email" name="email"><span class="error">*<?php echo $emailErr;?></span>
		<br>
		<label for="phone">Telefon:</label><br>
		<input type="text" id="phone" name="phone"><span class="error">*<?php echo $tlfErr;?><span>
		<br>
		<label>Sex:   </label>
		<label for="male">M</label>
		<input type="radio" id="male" name="sex" value="M">
		<label for="female">F</label>
		<input type="radio" id="female" name="sex" value="F">

<span class="error">*<?php echo $sexErr;?></span>

<br><br>

<label for="juridica">Persoana juridica:</label>
<select id="juridica" name="juridica">
	<option value="DA">Da</option>
	<option value="NU">Nu</option>
</select>
<br>

<br>


<input type="submit" name="Submit" value="Inregistrare"> 

</form>
</div>

</body>
<?php 
if($flag){
if(isset($_POST['Submit']))
echo '<script type=\'text/javascript\'>','inregistrare()','</script>';
}
?>

</html>
