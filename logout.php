<?php 
include __DIR__ . '/layout/header.php'; 
 if(isset($_SESSION['username'])) //comprobamos el inicio de sesion->
 { 
     session_destroy();             //Destruimos la sesion actual->
     header("Location: login.php"); //Redireccionamos al login
 }
 else 
 { 
     echo "Operacion incorrecta.";
 } 

include __DIR__ . '/layout/footer.php';  ?>