<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_servidor.php';

// --- USUARIOS CRUD ---
// Actualizar rango
if (isset($_POST['update_rango'])) {
    $user_id = intval($_POST['user_id']);
    // No permitir cambiar el rango del usuario 1
    if ($user_id != 1) {
        $nuevo_rango = intval($_POST['rango']);
        $stmt = $con->prepare("UPDATE usuarios SET rango=? WHERE id=?");
        $stmt->bind_param("ii", $nuevo_rango, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
// Borrar usuario
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);

    // Obtener rango y verificar si es admin
    $stmt = $con->prepare("SELECT rango FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($rango_borrar);
    $stmt->fetch();
    $stmt->close();

    // Solo permitir borrar admin si el usuario actual es id 1 y nunca a sí mismo
    if (
        ($rango_borrar != 255) ||
        (isset($_SESSION['id']) && $_SESSION['id'] == 1 && $user_id != 1)
    ) {
        if (!isset($_SESSION['id']) || $_SESSION['id'] != $user_id) {
            $stmt = $con->prepare("DELETE FROM usuarios WHERE id=?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$usuarios = $con->query("SELECT id, username, mail, rango, reg_time FROM usuarios ORDER BY username ASC");

// --- PUBLICACIONES CRUD ---
// Borrar publicación
if (isset($_POST['delete_pub'])) {
    $pub_id = intval($_POST['pub_id']);
    // Obtener usuario dueño de la publicación y su rango
    $stmt = $con->prepare("SELECT usuario FROM publicaciones WHERE id=?");
    $stmt->bind_param("i", $pub_id);
    $stmt->execute();
    $stmt->bind_result($usuario_pub);
    $stmt->fetch();
    $stmt->close();

    $stmt = $con->prepare("SELECT id, rango FROM usuarios WHERE username=?");
    $stmt->bind_param("s", $usuario_pub);
    $stmt->execute();
    $stmt->bind_result($id_pub, $rango_pub);
    $stmt->fetch();
    $stmt->close();

    // Solo permitir borrar publicación de admin si el usuario actual es id 1 y nunca la suya propia
    if (
        ($rango_pub != 255) ||
        (isset($_SESSION['id']) && $_SESSION['id'] == 1 && $id_pub != 1)
    ) {
        // Opcional: eliminar archivo físico
        $stmt = $con->prepare("SELECT archivo FROM publicaciones WHERE id=?");
        $stmt->bind_param("i", $pub_id);
        $stmt->execute();
        $stmt->bind_result($archivo);
        if ($stmt->fetch() && file_exists($archivo)) {
            unlink($archivo);
        }
        $stmt->close();
        $stmt = $con->prepare("DELETE FROM publicaciones WHERE id=?");
        $stmt->bind_param("i", $pub_id);
        $stmt->execute();
        $stmt->close();
    }
}
// Editar publicación
if (isset($_POST['edit_pub'])) {
    $pub_id = intval($_POST['pub_id']);
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $stmt = $con->prepare("UPDATE publicaciones SET titulo=?, descripcion=? WHERE id=?");
    $stmt->bind_param("ssi", $titulo, $descripcion, $pub_id);
    $stmt->execute();
    $stmt->close();
}
$publicaciones = $con->query("SELECT id, usuario, titulo, descripcion, archivo, tipo, fecha FROM publicaciones ORDER BY fecha DESC");
?>

<div class="container mt-4">
    <h2 class="mb-4">Administración</h2>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">Usuarios</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="publicaciones-tab" data-bs-toggle="tab" data-bs-target="#publicaciones" type="button" role="tab">Publicaciones</button>
        </li>
    </ul>
    <div class="tab-content" id="adminTabsContent">
        <!-- Usuarios -->
        <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rango</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $usuarios->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['mail']) ?></td>
                                <td>
                                    <form method="post" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                        <?php if ($row['id'] == 1): ?>
                                            <select name="rango" class="form-select form-select-sm" style="width:auto;display:inline-block;" disabled>
                                                <option value="255" selected>Administrador</option>
                                            </select>
                                            <button type="submit" name="update_rango" class="btn btn-primary btn-sm" disabled>Actualizar</button>
                                        <?php else: ?>
                                            <select name="rango" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                                                <option value="0" <?= $row['rango'] == 0 ? 'selected' : '' ?>>Usuario</option>
                                                <option value="255" <?= $row['rango'] == 255 ? 'selected' : '' ?>>Administrador</option>
                                            </select>
                                            <button type="submit" name="update_rango" class="btn btn-primary btn-sm">Actualizar</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td><?= htmlspecialchars($row['reg_time']) ?></td>
                                <td>
                                    <?php
                                    // Solo el usuario 1 puede borrar administradores (y nunca a sí mismo)
                                    if (
                                        ($row['rango'] != 255) ||
                                        (isset($_SESSION['id']) && $_SESSION['id'] == 1 && $row['id'] != 1)
                                    ): ?>
                                        <form method="post" onsubmit="return confirm('¿Seguro que deseas borrar este usuario?');">
                                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Borrar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">No permitido</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Publicaciones -->
        <div class="tab-pane fade" id="publicaciones" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-info">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Archivo</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pub = $publicaciones->fetch_assoc()): ?>
                            <tr>
                                <td><?= $pub['id'] ?></td>
                                <td><?= htmlspecialchars($pub['usuario']) ?></td>
                                <td>
                                    <form method="post" class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                                        <input type="hidden" name="pub_id" value="<?= $pub['id'] ?>">
                                        <input type="text" name="titulo" value="<?= htmlspecialchars($pub['titulo']) ?>" class="form-control form-control-sm" maxlength="100" required>
                                </td>
                                <td>
                                    <textarea name="descripcion" class="form-control form-control-sm" rows="1" maxlength="255"><?= htmlspecialchars($pub['descripcion']) ?></textarea>
                                </td>
                                <td>
                                    <?php if ($pub['tipo'] == 'imagen' || $pub['tipo'] == 'paleta'): ?>
                                        <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="img" style="max-width:60px;max-height:60px;">
                                    <?php elseif ($pub['tipo'] == 'audio'): ?>
                                        <audio controls style="width:100px;">
                                            <source src="<?= htmlspecialchars($pub['archivo']) ?>">
                                        </audio>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($pub['tipo']) ?></td>
                                <td><?= htmlspecialchars($pub['fecha']) ?></td>
                                <td>
                                    <?php
                                    // Consultar rango del dueño de la publicación
                                    $rango_pub = 0;
                                    $id_pub = 0;
                                    $stmt = $con->prepare("SELECT id, rango FROM usuarios WHERE username=?");
                                    $stmt->bind_param("s", $pub['usuario']);
                                    $stmt->execute();
                                    $stmt->bind_result($id_pub, $rango_pub);
                                    $stmt->fetch();
                                    $stmt->close();

                                    // Solo el usuario 1 puede borrar publicaciones de administradores (y nunca la suya propia)
                                    if (
                                        ($rango_pub != 255) ||
                                        (isset($_SESSION['id']) && $_SESSION['id'] == 1 && $id_pub != 1)
                                    ): ?>
                                        <button type="submit" name="edit_pub" class="btn btn-primary btn-sm mb-1">Guardar</button>
                                    </form>
                                    <form method="post" onsubmit="return confirm('¿Seguro que deseas borrar esta publicación?');" style="display:inline;">
                                        <input type="hidden" name="pub_id" value="<?= $pub['id'] ?>">
                                        <button type="submit" name="delete_pub" class="btn btn-danger btn-sm">Borrar</button>
                                    </form>
                                    <?php else: ?>
                                        <button type="submit" name="edit_pub" class="btn btn-primary btn-sm mb-1">Guardar</button>
                                        </form>
                                        <span class="text-muted">No permitido</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>