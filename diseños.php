<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

// Solo publicaciones de tipo imagen (diseño)
$result = $con->query("SELECT * FROM publicaciones WHERE tipo='imagen' ORDER BY fecha DESC");
?>

<div class="container my-5">
  <h1>Diseños</h1>
  <div class="row">
    <?php while($pub = $result->fetch_assoc()): ?>
      <?php
        // Obtener la foto de perfil del usuario
        $stmt = $con->prepare("SELECT user_img FROM usuarios WHERE username=?");
        $stmt->bind_param("s", $pub['usuario']);
        $stmt->execute();
        $stmt->bind_result($user_img);
        $stmt->fetch();
        $stmt->close();
        $foto = $user_img ? htmlspecialchars($user_img) : 'img/perfiles/default.png';
      ?>
      <div class="col-md-4 mb-4">
        <div class="card border-info h-100">
          <div class="card-header">
            <h4><?= htmlspecialchars($pub['titulo']) ?></h4>
            <span class="badge bg-secondary">Diseño</span>
          </div>
          <div class="card-body text-center">
            <h5 class="card-title">
              <img src="<?= $foto ?>" alt="Perfil" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;vertical-align:middle;">
              <?= htmlspecialchars($pub['usuario']) ?>
            </h5>
            <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="<?= htmlspecialchars($pub['titulo']) ?>" style="max-width:100%;">
            <p class="mt-2"><?= htmlspecialchars($pub['descripcion']) ?></p>
            <a href="perfil_publico.php?user=<?= urlencode($pub['usuario']) ?>" class="card-link">Ver Perfil del Artista</a>
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