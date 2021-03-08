<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="Ferma.css">
<title>Login</title>
</head>

<?php
session_start();
$_SESSION["logged"]=0;
$_SESSION["id_client"]=0;
$serverName = "DESKTOP-459V8DR\SQLEXPRESS";
$connectionInfo = array ("Database" => "Ferma_Viticola");
$conn = sqlsrv_connect ($serverName, $connectionInfo);
$session=" ";
if (!$conn)
	

	echo"Connection failed";



if(isset($_POST["username"], $_POST["password"])) 
    {

        $username = $_POST["username"]; 
		$_SESSION['user']=$username;
        $password = $_POST["password"]; 
		$query="Select Parola FROM Clienti WHERE Email='$username'";
		$stmt=sqlsrv_query($conn,$query);
		$result=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		$hashedPwd=$result["Parola"];
		
        $res = sqlsrv_query($conn,"SELECT ClientID, Email FROM Clienti WHERE Email = '$username'");
		$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
        if(!sqlsrv_has_rows($res) || !password_verify($password,$hashedPwd)) //se verifica daca numele si parola se regasesc in tablea Clienti
       
        {
           $session="Numele contului sau parola sunt gresite";
        }
		else{
			$_SESSION["logged"]=1;
			$_SESSION["id_client"]=$row['ClientID'];
			header("location:index.php");
		}
}
?>


</head>




<body>
<div class="login-container">
<form class="login-form" action="Login.php" method="POST">
<label for="username">Nume de utilizator</label> <br>
<input type="text" id="username" name="username"> <br><br><br><br>
<label for="password">Parola</label><br>
<input type="password" id="password" name="password"> <br><br>
<span class=error><?php echo $session;?></span>
<br><br>
<input type="submit" name="Submit" value="Intra in cont"> <br><br>
<a class="loginbutton" href="Register.php">Inregistreaza-te</a>
</form>
</div>
</body>
</html>