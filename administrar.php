<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

// Actualizar rango
if (isset($_POST['update_rango'])) {
    $user_id = intval($_POST['user_id']);
    $nuevo_rango = intval($_POST['rango']);
    $stmt = $con->prepare("UPDATE usuarios SET rango=? WHERE id=?");
    $stmt->bind_param("ii", $nuevo_rango, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Borrar usuario
if (isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    $stmt = $con->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

$result = $con->query("SELECT id, username, mail, rango, reg_time FROM usuarios ORDER BY username ASC");
?>

<div class="container mt-4">
    <h2 class="mb-4">Administrar Usuarios</h2>
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
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['mail']) ?></td>
                    <td>
                        <form method="post" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <select name="rango" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                                <option value="0" <?= $row['rango']==0 ? 'selected' : '' ?>>Usuario</option>
                                <option value="255" <?= $row['rango']==255 ? 'selected' : '' ?>>Administrador</option>
                            </select>
                            <button type="submit" name="update_rango" class="btn btn-primary btn-sm">Actualizar</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($row['reg_time']) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('¿Seguro que deseas borrar este usuario?');">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php';