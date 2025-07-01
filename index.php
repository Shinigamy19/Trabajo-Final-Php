<?php
include __DIR__ . '/layout/header.php';
include __DIR__ . '/db/conexion_local.php';

$result = $con->query("SELECT * FROM publicaciones ORDER BY fecha DESC");
?>

<div class="container my-5">
  <h1>Pixel Art</h1>
  <div class="row">
    <?php while($pub = $result->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="card border-info h-100">
          <div class="card-header"><h4><?= htmlspecialchars($pub['titulo']) ?></h4></div>
          <div class="card-body text-center">
            <p>Usuario: <?= htmlspecialchars($pub['usuario']) ?></p>
            <?php if($pub['tipo'] == 'imagen'): ?>
              <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="<?= htmlspecialchars($pub['titulo']) ?>" style="max-width:100%;">
            <?php elseif($pub['tipo'] == 'paleta'): ?>
              <img src="<?= htmlspecialchars($pub['archivo']) ?>" alt="Paleta" style="max-width:100%;">
            <?php elseif($pub['tipo'] == 'audio'): ?>
              <audio controls src="<?= htmlspecialchars($pub['archivo']) ?>"></audio>
            <?php endif; ?>
            <p class="mt-2"><?= htmlspecialchars($pub['descripcion']) ?></p>
            <a href="#" class="card-link">Ver Perfil del Artista</a>
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