<?php
include __DIR__ . '/layout/header.php';
if (empty($_SESSION['username'])) {


?>
    <div class="container">
        <br></br>
        <div class="row justify-content-center">
        <div class="card border-dark">
            <div class="card-header">
                <h4 class="text-center">Ingresar</h4>
            </div>
            <div class="card-body">
                <form action="check.php" method="post">
                    <div class="form-group">
                        <label>
                            <h4>Usuario:</h4>
                        </label>
                        <input type="text" class="form-control" name="user" placeholder="Escribe tu Usuario" required>
                    </div>
                    <p></p>
                    <div class="form-group">
                        <label>
                            <h4>Contraseña:</h4>
                        </label>
                        <input type="password" class="form-control" name="user_pw" placeholder="Escribe tu Contraseña" required>
                    </div>
                    <p></p>
                    <button type="submit" class="btn btn-primary" name="enviar">Ingresar</button>

                    <a class="btn btn-success" href="registro.php">Registrarse</a>
                </form>
            </div>
        </div>
        </div>
    </div>

<?php
} else {
?>
    <div class="container">
        <br></br>
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card border-primary">
                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                    <div class="card-body">
                        <h2 class="text-center">Bienvenido <b><?= $_SESSION['username'] ?></b>!<br></br><a class="btn btn-success" href="index.php">Ir al Inicio</a></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
include __DIR__ . '/layout/footer.php'; ?>