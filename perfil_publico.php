<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

$user = $_GET['user'] ?? '';
if (!$user) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Usuario no especificado.</div></div>';
    include __DIR__ . '/layout/footer.php';
    exit;
}

// Obtener datos usuario
$stmt = $con->prepare("SELECT mail, user_img FROM usuarios WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($mail, $user_img);
if (!$stmt->fetch()) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Usuario no encontrado.</div></div>';
    include __DIR__ . '/layout/footer.php';
    exit;
}
$stmt->close();

// Obtener publicaciones del usuario
$stmt = $con->prepare("SELECT titulo, descripcion, archivo, tipo, fecha FROM publicaciones WHERE usuario=? ORDER BY fecha DESC");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            <img src="<?= $user_img ? htmlspecialchars($user_img) : 'img/perfiles/default.png' ?>" alt="Foto de perfil" class="rounded-circle mb-3" style="width:150px;height:150px;object-fit:cover;">
        </div>
        <div class="col-md-9">
            <h2><?= htmlspecialchars($user) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($mail) ?></p>
        </div>
    </div>
    <h3>Publicaciones de <?= htmlspecialchars($user) ?></h3>
    <div class="row">
        <?php while($pub = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card border-info h-100">
                    <div class="card-header"><h4><?= htmlspecialchars($pub['titulo']) ?></h4></div>
                    <div class="card-body text-center">
                        <?php if($pub['tipo'] == 'imagen' || $pub['tipo'] == 'paleta'): ?>
                            <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="<?= htmlspecialchars($pub['titulo']) ?>" style="max-width:100%;">
                        <?php elseif($pub['tipo'] == 'audio'): ?>
                            <audio controls src="<?= htmlspecialchars($pub['archivo']) ?>"></audio>
                        <?php endif; ?>
                        <p class="mt-2"><?= htmlspecialchars($pub['descripcion']) ?></p>
                    </div>
                    <div class="card-footer text-muted">
                        <h6><?= date('d/m/Y H:i', strtotime($pub['fecha'])) ?></h6>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>