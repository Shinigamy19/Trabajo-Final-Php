  <?php
  include __DIR__ . '/../db/conexion_local.php';
  ?>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Pixelate</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="diseños.php">Pixel Art</a></li>
          <li class="nav-item"><a class="nav-link" href="paletas.php">Paletas</a></li>
          <li class="nav-item"><a class="nav-link" href="sonidos.php">Sonidos</a></li>
      <?php    
      if(empty($_SESSION['username']))
			{
        ?>
				<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              Tu Cuenta
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="login.php">Ingresar</a></li>
              <li><a class="dropdown-item" href="register.php">Registrarte</a></li>
            </ul>
          </li>
        </ul>
				<?php
        }
        else
			  {
        ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              Tu Cuenta
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
              <li><a class="dropdown-item" href="subirarchivos.php">Subir Archivo</a></li>
              <li><a class="dropdown-item" href="opciones.php">Configuración</a></li>
              <div class="dropdown-divider"></div>
              <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
          </li>
        </ul>
        <?php
        }
        if (!empty($_SESSION['username'])) {
          $username = $_SESSION['username'];
          $sql = "SELECT rango FROM usuarios WHERE username = ?";
          $stmt = $con->prepare($sql);
          $stmt->bind_param("s", $username);
          $stmt->execute();
          $stmt->bind_result($rango);
          $stmt->fetch();
          $stmt->close();
        }
        if ($rango=="255") //255 es el administrador
				{
          ?>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="administrar.php">Administrar</a></li>
        </ul>
        <?php
        }
        ?>
         <!-- Switch Modo Claro/Oscuro -->
      <div class="form-check form-switch me-3">
        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
        <label class="form-check-label text-dark" for="darkModeSwitch">Modo oscuro</label>
      </div>
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Buscar">
          <button class="btn btn-dark" type="submit">Buscar</button>
        </form>
      </div>
    </div>
  </nav>