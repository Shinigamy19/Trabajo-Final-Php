<?php
include __DIR__ . '/layout/header.php';
if (isset($_POST['enviar'])) {
    if ($_POST['user_pw'] != $_POST['user_pw2']) {
?>
        <div class="container">
            <br></br>
            <div class="row">
                <div class="col-md-4">

                </div>

                <div class="col-md-4">

                    <div class="card">
                        <img class="card-img-top" src="holder.js/100x180/" alt="">
                        <div class="card-body">
                            <h2 class="text-center">Las contraseñas no coinciden.<br></br><a class="btn btn-success" href="registro.php">Reintentar</a></h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>
        </div>
        <?php
    } else {
        $user_name = $_POST['user'];
        $user_pass = $_POST['user_pw'];
        $user_mail = $_POST['user_mail'];

        $sql = "SELECT username FROM usuarios WHERE username='" . $user_name . "'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
        ?>
            <div class="container">
                <br></br>
                <div class="row">
                    <div class="col-md-4">

                    </div>

                    <div class="col-md-4">

                        <div class="card">
                            <img class="card-img-top" src="holder.js/100x180/" alt="">
                            <div class="card-body">
                                <h2 class="text-center">El nombre de usuario ya esta en uso. <br></br><a class="btn btn-success" href="registro.php">Reintentar</a></h2>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            </div>
            </div>
            <?php
        } else {
            $user_pass = md5($user_pass);
            $default_img = 'img/perfiles/default.png';
            $reg = "INSERT INTO usuarios (username,pw_md5,mail,reg_time,user_img) VALUES('" . $user_name . "','" . $user_pass . "', '" . $user_mail . "', NOW(), '$default_img')";
            if ($con->query($reg) === TRUE) {
            ?>
                <div class="container">
                    <br></br>
                    <div class="row">
                        <div class="col-md-4">

                        </div>

                        <div class="col-md-4">

                            <div class="card">
                                <img class="card-img-top" src="holder.js/100x180/" alt="">
                                <div class="card-body">
                                    <h2 class="text-center">Registro exitoso. Espere 5 segundos!</h2>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

                </div>
                </div>
    <?php
                header('Refresh: 5; URL = login.php');
            } else {
                echo "Algo salio mal. <a href='javascript:history.back();'>Reintentar</a>";
            }
        }
    }
} else {

    ?>
    <div class="container">
        <br></br>
        <div class="row">
            <div class="col-md-4">

            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Registro</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="validationCustom01">Usuario:</label>
                                    <input type="text" name="user" class="form-control" id="validationCustom01" placeholder="Vegeta777" minlength="4" required>
                                    <div class="valid-feedback">
                                        Disponible!
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationCustomEmail">Email:</label>
                                    <div class="input-group">
                                    </div>
                                    <input type="text" name="user_mail" class="form-control" id="validationCustomEmail" minlength="10" placeholder="ejemplo@gmail.com "
                                        aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Ingresa tu mail.

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="validationCustom03">Contraseña:</label>
                                    <input type="password" class="form-control" id="user_pw" name="user_pw" id="validationCustom03" minlength="4" placeholder="********" required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationCustom04">Repetir Contraseña:</label>
                                <input type="password" class="form-control" id="user_pw2" name="user_pw2" id="validationCustom04" minlength="4" placeholder="********" required>
                                <div class="invalid-feedback">
                                    La contraseña no coincide.
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                    <label class="form-check-label" for="invalidCheck">
                                        <a href="termycon.php" target="_blank">Terminos y Condiciones</a>
                                    </label>
                                    <div class="invalid-feedback">
                                        Necesitas aceptar los terminos y condiciones.
                                    </div>
                                </div>
                            </div>
                            <p></p>
                            <button class="btn btn-primary" type="submit" name="enviar">Registrarse</button>
                        </form>
                    </div>
                </div>



            </div>

        </div>
    </div>

<?php
}
include __DIR__ . '/layout/footer.php'; 
?>