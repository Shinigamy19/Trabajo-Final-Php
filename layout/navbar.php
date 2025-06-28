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
          <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Pixel Art</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Paletas</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Sonidos</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Dibujar</a></li>
      <?php    
      if(empty($_SESSION['username']))
			{
        ?>
				<li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              Tu Cuenta
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Ingresar</a></li>
              <li><a class="dropdown-item" href="#">Registrarte</a></li>
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
              <li><a class="dropdown-item" href="#">Perfil</a></li>
              <li><a class="dropdown-item" href="#">Configuración</a></li>
              <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
            </ul>
          </li>
        </ul>
        <?php
        }
        if ($rango=="255") //255 es el administrador
				{
          ?>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="#">Administrar</a></li>
        </ul>
        <?php
        }
        ?>
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Buscar">
          <button class="btn btn-dark" type="submit">Buscar</button>
        </form>
      </div>
    </div>
  </nav>