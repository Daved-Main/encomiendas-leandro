<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['empleado']) || !$_SESSION['empleado']) {
    header("Location: index.php?route=empleado/login");
    exit;
}
$nombre = $_SESSION['empleado_nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Empleado — Encomiendas Leandro</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php include __DIR__ . '/../iu/navbar.php';?>
<body class="min-h-screen bg-gray-100 text-gray-800">


  <div class="p-6">
    <h1 class="text-2xl">Hola, <?= htmlspecialchars($nombre) ?></h1>
    <h2 class="text-xl font-semibold mb-4">Bienvenido a tu panel de empleado</h2>
    <p>Aquí podrás ver tus tareas, pedidos asignados o la información que desees.</p>
  </div>
</body>
</html>
