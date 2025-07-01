<?php
session_start();
include __DIR__ . '/../db/conexion_local.php';

$rango = isset($_SESSION['rango']) ? $_SESSION['rango'] : '0'; // Espectador
if (empty($rango)) {
    $rango = '0'; // Si no tiene rango, asignar espectador por defecto
}
?>

<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
  <meta charset="UTF-8">
  <title>Pixelate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css\style.css" rel="stylesheet">
  <script src="/Pixelate/js/darkmode.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include __DIR__ . '/navbar.php'; ?>
