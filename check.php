<?php 
include __DIR__ . '/layout/header.php'; 
include __DIR__ . 'db/conexion_local.php'; 
    
    if(isset($_POST['enviar'])) //si se enviaron los datos ->
    {  // comprobamos que los campos usuarios_nombre y usuario_clave no estén vacíos 
        if(empty($_POST['user']) || empty($_POST['user_pw']))  //Si user y pass no estan vacios ->
        {
            echo "El usuario o contraseña no han sido ingresados. <a href='javascript:history.back();'>Reintentar</a>";
        }
        else
        {      
            $user_name = $_POST['user'];
            $user_pass = $_POST['user_pw'];
            $user_pass = md5($user_pass); //Codifica en md5 ->
            $sql = "SELECT id, username, pw_md5 FROM usuarios WHERE username='".$user_name."' AND pw_md5='".$user_pass."'"; //Si los datos coinciden->
                
            $result = $con->query($sql);
         
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {     
                    $_SESSION['id'] = $row['id']; //Se crea la sesion con el id del usuario
                    $_SESSION['username'] = $row["username"]; //Se introduce en la sesion el nombre de usuario
                    header("Location: login.php");
                }
            }
            else 
            {
?>
                Usuario o Password incorrectos, <a href="login.php">Reintentar</a>
<?php
            }
        }
    }
    else
    {
        header("Location: login.php");
    }    
?>
<?php

include __DIR__ . '/layout/footer.php'; 

?>