<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_servidor.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultados = [];

if ($q !== '') {
    $stmt = $con->prepare("SELECT * FROM publicaciones WHERE titulo LIKE CONCAT('%', ?, '%') OR descripcion LIKE CONCAT('%', ?, '%') ORDER BY fecha DESC");
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $resultados[] = $row;
    }
    $stmt->close();
}
?>

<div class="container my-4">
    <h2>Resultados de búsqueda para: <span class="text-primary"><?= htmlspecialchars($q) ?></span></h2>
    <?php if (count($resultados) > 0): ?>
        <div class="row">
            <?php foreach ($resultados as $pub): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <?php
                        // Obtener imagen de perfil del usuario
                        $img_perfil = 'img/perfiles/default.png';
                        $stmt = $con->prepare("SELECT user_img FROM usuarios WHERE username=?");
                        $stmt->bind_param("s", $pub['usuario']);
                        $stmt->execute();
                        $stmt->bind_result($img_perfil_db);
                        if ($stmt->fetch() && !empty($img_perfil_db)) {
                            $img_perfil = $img_perfil_db;
                        }
                        $stmt->close();
                        ?>
                        <div class="d-flex align-items-center p-2">
                            <a href="perfil.php?user=<?= urlencode($pub['usuario']) ?>">
                                <img src="<?= htmlspecialchars($img_perfil) ?>" alt="perfil" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                            </a>
                            <a href="perfil.php?user=<?= urlencode($pub['usuario']) ?>" class="fw-bold text-decoration-none">
                                <?= htmlspecialchars($pub['usuario']) ?>
                            </a>
                        </div>
                        <?php if ($pub['tipo'] == 'imagen' || $pub['tipo'] == 'paleta'): ?>
                            <img src="<?= htmlspecialchars($pub['archivo']) ?>" class="card-img-top" alt="img">
                        <?php elseif ($pub['tipo'] == 'audio'): ?>
                            <audio controls class="w-100 mt-2">
                                <source src="<?= htmlspecialchars($pub['archivo']) ?>">
                            </audio>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($pub['descripcion']) ?></p>
                            <small class="text-muted">Publicado el <?= htmlspecialchars($pub['fecha']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-3">No se encontraron publicaciones.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/layout/footer.php'; ?>