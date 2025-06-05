<?php
// app/presentation/views/admin/listar-viaje-proximo.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? null;
$errors  = $_SESSION['errors'] ?? null;
unset($_SESSION['success'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administrar Viajes Actuales — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <?php include __DIR__ . '/../iu/navbar.php';?>

  <div class="max-w-6xl mx-auto mt-12 px-4">

    <h1 class="text-3xl font-bold mb-6 text-center">
      Administración de Viajes Actuales
    </h1>

    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
        <?php foreach ($success['messages'] as $msg): ?>
          <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php foreach ($errors['messages'] as $msg): ?>
          <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="mb-6 text-right">
      <a href="index.php?route=admin/nuevoViajeProximo"
         class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition">
        + Agendar nuevo viaje
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Salida</th>
            <th class="px-4 py-2 text-left">Destino</th>
            <th class="px-4 py-2 text-left">Fecha Salida</th>
            <th class="px-4 py-2 text-left">Fecha Entrega</th>
            <th class="px-4 py-2 text-left">Capacidad</th>
            <th class="px-4 py-2 text-left">Viaje #Mes</th>
            <th class="px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($viajes)): ?>
            <tr>
              <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                No hay viajes actuales agendados.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($viajes as $viaje): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2"> <?= $viaje['id_viaje_actual'] ?> </td>
                <td class="px-4 py-2"> <?= htmlspecialchars($viaje['lugar_salida_actual']) ?> </td>
                <td class="px-4 py-2"> <?= htmlspecialchars($viaje['lugar_destino_actual']) ?> </td>
                <td class="px-4 py-2"> <?= date('d/m/Y H:i', strtotime($viaje['fecha_salida_actual'])) ?> </td>
                <td class="px-4 py-2">
                  <?= $viaje['fecha_entrega_actual']
                        ? date('d/m/Y H:i', strtotime($viaje['fecha_entrega_actual']))
                        : '-' ?>
                </td>
                <td class="px-4 py-2 text-center"> <?= (int)$viaje['capacidad_paquetes'] ?> </td>
                <td class="px-4 py-2 text-center"> <?= (int)$viaje['id_viaje_mes'] ?> </td>
                <td class="px-4 py-2 text-center space-x-2">
                  <a href="index.php?route=admin/editarViajeProximo&id=<?= $viaje['id_viaje_actual'] ?>"
                     class="text-blue-600 hover:underline">Editar</a>
                  |
                  <a href="index.php?route=admin/eliminarViajeProximo&id=<?= $viaje['id_viaje_actual'] ?>"
                     class="text-red-600 hover:underline"
                     onclick="return confirm('¿Seguro que deseas eliminar este viaje?');">
                    Eliminar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6 text-center">
      <a href="index.php?route=home" class="text-gray-600 hover:underline">
        ← Volver al Dashboard
      </a>
    </div>

  </div>
</body>
</html>
