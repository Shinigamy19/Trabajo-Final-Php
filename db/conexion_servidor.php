<?php
    $host = "mysql-shinigamy19.alwaysdata.net";
    $user = "420837_eros";
    $pass = "pixelate";
    $dbsql = "shinigamy19_pixelate";
    $con = new mysqli($host,$user,$pass,$dbsql);
    if($con->connect_error){
        die("Homero, algo pasa con la base de datos. -Marge... no voy a mentirte... " . $con->connect_error);

    }

?>