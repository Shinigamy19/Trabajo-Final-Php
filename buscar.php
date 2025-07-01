<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_servidor.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultados = [];
$usuarios_encontrados = [];

if ($q !== '') {
    // Buscar publicaciones
    $stmt = $con->prepare("SELECT * FROM publicaciones WHERE titulo LIKE CONCAT('%', ?, '%') OR descripcion LIKE CONCAT('%', ?, '%') ORDER BY fecha DESC");
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $resultados[] = $row;
    }
    $stmt->close();

    // Buscar usuarios
    $stmt = $con->prepare("SELECT username, user_img FROM usuarios WHERE username LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $stmt->bind_result($username, $user_img);
    while ($stmt->fetch()) {
        $usuarios_encontrados[] = [
            'username' => $username,
            'user_img' => $user_img ?: 'img/perfiles/default.png'
        ];
    }
    $stmt->close();
}
?>

<div class="container my-4">
    <h2>Resultados de búsqueda para: <span class="text-primary"><?= htmlspecialchars($q) ?></span></h2>

    <?php if (count($usuarios_encontrados) > 0): ?>
        <div class="row mb-4">
            <?php foreach ($usuarios_encontrados as $user): ?>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <a href="perfil.php?user=<?= urlencode($user['username']) ?>">
                            <img src="<?= htmlspecialchars($user['user_img']) ?>" alt="perfil" class="rounded-circle mt-3" style="width:70px;height:70px;object-fit:cover;">
                        </a>
                        <div class="card-body">
                            <a href="perfil.php?user=<?= urlencode($user['username']) ?>" class="fw-bold text-decoration-none h5 user-link">
                                <?= htmlspecialchars($user['username']) ?>
                            </a>
                            <div class="mt-3">
                                <a href="perfil.php?user=<?= urlencode($user['username']) ?>" class="btn btn-outline-primary btn-sm user-link-btn">
                                    Ver perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
                            <div class="mb-2">
                                <span class="badge 
                                    <?php
                                    switch ($pub['tipo']) {
                                        case 'imagen':
                                            echo 'bg-primary';
                                            $cat = 'Diseño';
                                            break;
                                        case 'paleta':
                                            echo 'bg-success';
                                            $cat = 'Paleta';
                                            break;
                                        case 'audio':
                                            echo 'bg-warning text-dark';
                                            $cat = 'Audio';
                                            break;
                                        default:
                                            echo 'bg-secondary';
                                            $cat = ucfirst(htmlspecialchars($pub['tipo']));
                                    }
                                    ?>">
                                    <?= $cat ?>
                                </span>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($pub['descripcion']) ?></p>
                            <small class="text-muted">Publicado el <?= htmlspecialchars($pub['fecha']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (count($usuarios_encontrados) == 0): ?>
        <div class="alert alert-warning mt-3">No se encontraron publicaciones ni usuarios.</div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/layout/footer.php'; ?>