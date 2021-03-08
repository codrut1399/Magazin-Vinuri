<?php
session_start();
session_destroy(); //distruge sesiunea curenta, impreuna cu toate variabilele 
header("location:index.php"); //redirectionare spre pagina principala
?>