<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbsql = "pixelate";
    $con = new mysqli($host,$user,$pass,$dbsql);
    if($con->connect_error){
        die("Homero, algo pasa con la base de datos. -Marge... no voy a mentirte... " . $con->connect_error);

    }

?>