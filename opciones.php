<?php include __DIR__ . '/layout/header.php';

if(isset($_SESSION['username'])) {

    if(isset($_POST['enviar'])) { 
        $user_name = $_SESSION['username'];
        $user_pass_current = md5($_POST['user_pw_current']);
        $user_pass = $_POST['user_pw'];
        $user_pass2 = $_POST['user_pw2'];

        if($user_pass != $user_pass2) {
            ?>
            <div class="container">
                <br></br>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <img class="card-img-top" src="holder.js/100x180/" alt="">
                            <div class="card-body">
                                <h2 class="text-center">Las contraseñas no coinciden.<br></br>
                                <a class="btn btn-success" href="opciones.php">Reintentar</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Verifica la contraseña actual
            $sql_check = "SELECT * FROM usuarios WHERE username='".$user_name."' AND pw_md5='".$user_pass_current."'";
            $result_check = $con->query($sql_check);

            if($result_check && $result_check->num_rows == 1) {
                // Contraseña actual correcta, procede a actualizar
                $user_pass_new = md5($user_pass);
                $sql = "UPDATE usuarios SET pw_md5='".$user_pass_new."' WHERE username='".$user_name."'";
                $result = $con->query($sql);

                if($result) {
                    ?>
                    <div class="container">
                        <br></br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                                    <div class="card-body">
                                        <h2 class="text-center">La contraseña se cambió correctamente.<br></br>
                                        <a class="btn btn-success" href="index.php">Ir al inicio</a></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="container">
                        <br></br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="card border-danger">
                                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                                    <div class="card-body">
                                        <h2 class="text-center">No se pudo cambiar la contraseña.<br></br>
                                        <a class="btn btn-success" href="opciones.php">Reintentar</a></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Contraseña actual incorrecta
                ?>
                <div class="container">
                    <br></br>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="card border-danger">
                                <img class="card-img-top" src="holder.js/100x180/" alt="">
                                <div class="card-body">
                                    <h2 class="text-center">La contraseña actual es incorrecta.<br></br>
                                    <a class="btn btn-success" href="opciones.php">Reintentar</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    } else {
?>

<div class="container">
    <br></br>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-header">
                    <h4 class="text-center">Cambio de Contraseña</h4>
                </div>
                <div class="card-body">
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationCustom02">Contraseña Actual:</label>
                                <input type="password" class="form-control" name="user_pw_current" id="validationCustom02" minlength="4" placeholder="********" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationCustom03">Nueva Contraseña:</label>
                                <input type="password" class="form-control" name="user_pw" id="validationCustom03" minlength="4"  placeholder="********" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom04">Repetir Nueva Contraseña:</label>
                            <input type="password" class="form-control" name="user_pw2" id="validationCustom04" minlength="4" placeholder="********" required>
                            <div class="invalid-feedback">
                                La contraseña no coincide.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" name="enviar">
                            Cambiar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dropDownSelect1"></div>

<?php
    }
} else {
    echo "Acceso denegado.";
}

include __DIR__ . '/layout/footer.php'; ?>