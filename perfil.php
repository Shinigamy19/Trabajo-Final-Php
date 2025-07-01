<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

if (!isset($_SESSION['username'])) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Debes iniciar sesión para ver tu perfil.</div></div>';
    include __DIR__ . '/layout/footer.php';
    exit;
}

$username = $_SESSION['username'];

// Subir foto de perfil
if (isset($_POST['subir_foto']) && isset($_FILES['user_img']) && $_FILES['user_img']['error'] == 0) {
    $nombre = basename($_FILES['user_img']['name']);
    $ruta = 'img/perfiles/' . uniqid() . '_' . $nombre;
    if (!is_dir('img/perfiles')) mkdir('img/perfiles', 0777, true);
    move_uploaded_file($_FILES['user_img']['tmp_name'], $ruta);
    $stmt = $con->prepare("UPDATE usuarios SET user_img=? WHERE username=?");
    $stmt->bind_param("ss", $ruta, $username);
    $stmt->execute();
    $stmt->close();
}

// Obtener datos usuario
$stmt = $con->prepare("SELECT mail, user_img FROM usuarios WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($mail, $user_img);
$stmt->fetch();
$stmt->close();

// Editar publicación
if (isset($_POST['edit_pub'])) {
    $pub_id = intval($_POST['pub_id']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $stmt = $con->prepare("UPDATE publicaciones SET titulo=?, descripcion=? WHERE id=? AND usuario=?");
    $stmt->bind_param("ssis", $titulo, $descripcion, $pub_id, $username);
    $stmt->execute();
    $stmt->close();
}

// Borrar publicación
if (isset($_POST['delete_pub'])) {
    $pub_id = intval($_POST['pub_id']);
    // Eliminar archivo físico
    $stmt = $con->prepare("SELECT archivo FROM publicaciones WHERE id=? AND usuario=?");
    $stmt->bind_param("is", $pub_id, $username);
    $stmt->execute();
    $stmt->bind_result($archivo);
    if ($stmt->fetch() && file_exists($archivo)) {
        unlink($archivo);
    }
    $stmt->close();
    $stmt = $con->prepare("DELETE FROM publicaciones WHERE id=? AND usuario=?");
    $stmt->bind_param("is", $pub_id, $username);
    $stmt->execute();
    $stmt->close();
}

// Obtener publicaciones del usuario
$stmt = $con->prepare("SELECT id, titulo, descripcion, archivo, tipo, fecha FROM publicaciones WHERE usuario=? ORDER BY fecha DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            <img src="<?= $user_img ? htmlspecialchars($user_img) : 'img/perfiles/default.png' ?>" alt="Foto de perfil" class="rounded-circle mb-3" style="width:150px;height:150px;object-fit:cover;">
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="user_img" class="form-control mb-2" accept="image/*" required>
                <button type="submit" name="subir_foto" class="btn btn-primary btn-sm w-100">Cambiar foto</button>
            </form>
        </div>
        <div class="col-md-9">
            <h2><?= htmlspecialchars($username) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($mail) ?></p>
        </div>
    </div>
    <h3>Tus Publicaciones</h3>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-info">
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Archivo</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($pub = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <form method="post" class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                            <input type="hidden" name="pub_id" value="<?= $pub['id'] ?>">
                            <input type="text" name="titulo" value="<?= htmlspecialchars($pub['titulo']) ?>" class="form-control form-control-sm" maxlength="100" required>
                    </td>
                    <td>
                            <textarea name="descripcion" class="form-control form-control-sm" rows="1" maxlength="255"><?= htmlspecialchars($pub['descripcion']) ?></textarea>
                    </td>
                    <td>
                        <?php if($pub['tipo']=='imagen' || $pub['tipo']=='paleta'): ?>
                            <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="img" style="max-width:60px;max-height:60px;">
                        <?php elseif($pub['tipo']=='audio'): ?>
                            <audio controls style="width:100px;"><source src="<?= htmlspecialchars($pub['archivo']) ?>"></audio>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($pub['tipo']) ?></td>
                    <td><?= htmlspecialchars($pub['fecha']) ?></td>
                    <td>
                            <button type="submit" name="edit_pub" class="btn btn-primary btn-sm mb-1">Guardar</button>
                        </form>
                        <form method="post" onsubmit="return confirm('¿Seguro que deseas borrar esta publicación?');" style="display:inline;">
                            <input type="hidden" name="pub_id" value="<?= $pub['id'] ?>">
                            <button type="submit" name="delete_pub" class="btn btn-danger btn-sm">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php';