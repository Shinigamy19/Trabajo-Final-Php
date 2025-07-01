<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_SESSION['username'] ?? 'Invitado';

    // Diseño
    if (isset($_FILES['diseñoFile']) && $_FILES['diseñoFile']['error'] == 0) {
        $nombre = basename($_FILES['diseñoFile']['name']);
        $ruta = 'img/' . uniqid() . '_' . $nombre;
        move_uploaded_file($_FILES['diseñoFile']['tmp_name'], $ruta);
        $titulo = $_POST['titulo_diseño'] ?? "Diseño";
        $descripcion = $_POST['descripcion_diseño'] ?? "";
        $tipo = "imagen";
        $stmt = $con->prepare("INSERT INTO publicaciones (usuario, titulo, descripcion, archivo, tipo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, $titulo, $descripcion, $ruta, $tipo);
        $stmt->execute();
        $stmt->close();
    }
    // Paleta
    if (isset($_FILES['paletaFile']) && $_FILES['paletaFile']['error'] == 0) {
        $nombre = basename($_FILES['paletaFile']['name']);
        $ruta = 'img/' . uniqid() . '_' . $nombre;
        move_uploaded_file($_FILES['paletaFile']['tmp_name'], $ruta);
        $titulo = $_POST['titulo_paleta'] ?? "Paleta";
        $descripcion = $_POST['descripcion_paleta'] ?? "";
        $tipo = "paleta";
        $stmt = $con->prepare("INSERT INTO publicaciones (usuario, titulo, descripcion, archivo, tipo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, $titulo, $descripcion, $ruta, $tipo);
        $stmt->execute();
        $stmt->close();
    }
    // Audio
    if (isset($_FILES['audioFile']) && $_FILES['audioFile']['error'] == 0) {
        $nombre = basename($_FILES['audioFile']['name']);
        $ruta = 'audio/' . uniqid() . '_' . $nombre;
        move_uploaded_file($_FILES['audioFile']['tmp_name'], $ruta);
        $titulo = $_POST['titulo_audio'] ?? "Audio";
        $descripcion = $_POST['descripcion_audio'] ?? "";
        $tipo = "audio";
        $stmt = $con->prepare("INSERT INTO publicaciones (usuario, titulo, descripcion, archivo, tipo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, $titulo, $descripcion, $ruta, $tipo);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h2>Subir Archivos</h2>
            <br>
        </div>
    </div>
    <div class="row justify-content-center">
        <!-- Subir Diseño -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-primary h-100">
                <h3 class="card-header">Subí tu Diseño</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="diseñoFile" class="form-label">
                                Imagen (JPG, PNG, etc)
                            </label>
                            <input type="file" id="diseñoFile" class="form-control" name="diseñoFile" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="titulo_diseño" class="form-label">Título</label>
                            <input type="text" id="titulo_diseño" name="titulo_diseño" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="descripcion_diseño" class="form-label">Descripción</label>
                            <textarea id="descripcion_diseño" name="descripcion_diseño" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Subir Imagen</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Subir Paleta -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-success h-100">
                <h3 class="card-header">Subí tu Paleta</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="paletaFile" class="form-label">
                                Imagen de la paleta
                            </label>
                            <input type="file" id="paletaFile" class="form-control" name="paletaFile" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="titulo_paleta" class="form-label">Título</label>
                            <input type="text" id="titulo_paleta" name="titulo_paleta" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="descripcion_paleta" class="form-label">Descripción</label>
                            <textarea id="descripcion_paleta" name="descripcion_paleta" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Subir paleta</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Subir Audio -->
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-info h-100">
                <h3 class="card-header">Subí tus audios</h3>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="audioFile" class="form-label">
                                Archivo de audio (MP3, WAV, etc)
                            </label>
                            <input type="file" id="audioFile" class="form-control" name="audioFile" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="titulo_audio" class="form-label">Título</label>
                            <input type="text" id="titulo_audio" name="titulo_audio" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="descripcion_audio" class="form-label">Descripción</label>
                            <textarea id="descripcion_audio" name="descripcion_audio" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info w-100">Subir Audio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>