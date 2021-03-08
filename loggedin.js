document.getElementById("login").className="dropdown";

document.getElementById("login").innerHTML=
'<a class="dropdownbtn" href="#profile.php"><i class="fa fa-user" style="color:white " ></i>  Cont</a><div class="dropdown-content"><a class="dropdownform" href="Date.php">Datele mele</a><a class="dropdownform" href="#Comenzi">Comenzi</a><a class="dropdownform" id="logoutbtn" href="logout.php">Logout   <i class="fa fa-sign-out" aria-hidden="true"></a></div>';
		
//butonul Login din prima pagina devine butonul Cont cand utilizatorul este logat