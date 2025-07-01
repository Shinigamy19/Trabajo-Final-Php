<?php
include __DIR__ . '/layout/header.php';
if (isset($_POST['enviar'])) {
    if ($_POST['user_pw'] != $_POST['user_pw2']) {
?>
        <div class="container">
            <br>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="card border-dark">
                        <img class="card-img-top d-none d-md-block" src="holder.js/100x180/" alt="">
                        <div class="card-body">
                            <h2 class="text-center">Las contraseñas no coinciden.<br><a class="btn btn-success mt-3" href="registro.php">Reintentar</a></h2>
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
                <br>
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                        <div class="card border-dark">
                            <img class="card-img-top d-none d-md-block" src="holder.js/100x180/" alt="">
                            <div class="card-body">
                                <h2 class="text-center">El nombre de usuario ya está en uso.<br><a class="btn btn-success mt-3" href="registro.php">Reintentar</a></h2>
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
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                            <div class="card border-dark">
                                <img class="card-img-top d-none d-md-block" src="holder.js/100x180/" alt="">
                                <div class="card-body">
                                    <h2 class="text-center">Registro exitoso. Espere 5 segundos!</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
                header('Refresh: 5; URL = login.php');
            } else {
                echo "Algo salió mal. <a href='javascript:history.back();'>Reintentar</a>";
            }
        }
    }
} else {
    ?>
    <div class="container">
        <br>
        <div class="row justify-content-center">
            <div class="card border-dark">
                <div class="card-header">
                    <h4 class="text-center">Registro</h4>
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-group mb-3">
                            <label>
                                <h4>Usuario:</h4>
                            </label>
                            <input type="text" name="user" class="form-control" placeholder="Vegeta777" minlength="4" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>
                                <h4>Email:</h4>
                            </label>
                            <input type="email" name="user_mail" class="form-control" minlength="10" placeholder="ejemplo@gmail.com" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>
                                <h4>Contraseña:</h4>
                            </label>
                            <input type="password" class="form-control" name="user_pw" minlength="4" placeholder="********" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>
                                <h4>Repetir Contraseña:</h4>
                            </label>
                            <input type="password" class="form-control" name="user_pw2" minlength="4" placeholder="********" required>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    <a href="termycon.php" target="_blank">Términos y Condiciones</a>
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mb-2" type="submit" name="enviar">Registrarse</button>
                        <a class="btn btn-success w-100" href="login.php">Ingresar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
}
include __DIR__ . '/layout/footer.php'; ?>