<?php

include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

$result = $con->query("SELECT * FROM publicaciones ORDER BY fecha DESC");
?>

<div class="container my-5">
  <h1>Publicaciones</h1>
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

        // Determinar la categoría
        if ($pub['tipo'] == 'imagen') {
          $categoria = 'Diseño';
        } elseif ($pub['tipo'] == 'paleta') {
          $categoria = 'Paleta';
        } elseif ($pub['tipo'] == 'audio') {
          $categoria = 'Audio';
        } else {
          $categoria = ucfirst($pub['tipo']);
        }
      ?>
      <div class="col-md-4 mb-4">
        <div class="card border-info h-100">
          <div class="card-header">
            <h4><?= htmlspecialchars($pub['titulo']) ?></h4>
            <span class="badge bg-secondary"><?= $categoria ?></span>
          </div>
          <div class="card-body text-center">
            <h5 class="card-title">
              <img src="<?= $foto ?>" alt="Perfil" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;vertical-align:middle;">
               <?= htmlspecialchars($pub['usuario']) ?>
            </h5>
            <?php if($pub['tipo'] == 'imagen'): ?>
              <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="<?= htmlspecialchars($pub['titulo']) ?>" style="max-width:100%;">
            <?php elseif($pub['tipo'] == 'paleta'): ?>
              <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="Paleta" style="max-width:100%;">
            <?php elseif($pub['tipo'] == 'audio'): ?>
              <audio controls src="<?= htmlspecialchars($pub['archivo']) ?>"></audio>
            <?php endif; ?>
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