<?php
// app/presentation/views/admin/listar-viaje-proximo.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mensajes de éxito / error (seteados previamente en el controlador)
$success = $_SESSION['success'] ?? null;
$errors  = $_SESSION['errors'] ?? null;

// Limpiamos las variables de sesión para que no se muestren en recargas posteriores
unset($_SESSION['success'], $_SESSION['errors']);

// $viajes debe venir del controlador, p. ej.:
// $viajes = $this->repo->listarTodos(); 
// Cada elemento de $viajes es un array con las claves:
//   id_viaje_proximo, fecha_registro_proximo, fecha_salida_proximo,
//   fecha_entrega_proximo, lugar_salida_proximo, lugar_destino_proximo
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administrar Próximos Viajes — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/encomiendasLeandro/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include __DIR__ . '/../iu/navbar.php';?>

  <div class="max-w-4xl mx-auto mt-12 px-4">

    <!-- Título -->
    <h1 class="text-3xl font-bold mb-6 text-center">
      Administración de Próximos Viajes
    </h1>

    <!-- Mensajes de éxito -->
    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
        <?php foreach ($success['messages'] as $msg): ?>
          <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Mensajes de error -->
    <?php if (!empty($errors)): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php foreach ($errors['messages'] as $msg): ?>
          <p><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Botón para agendar un nuevo viaje -->
    <div class="mb-6 text-right">
      <a href="index.php?route=admin/nuevoViajeProximo"
         class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition">
        + Agendar nuevo viaje
      </a>
    </div>

    <!-- Tabla de viajes próximos -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Lugar de salida</th>
            <th class="px-4 py-2 text-left">Lugar de destino</th>
            <th class="px-4 py-2 text-left">Fecha de salida</th>
            <th class="px-4 py-2 text-left">Fecha de entrega</th>
            <th class="px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($viajes)): ?>
            <tr>
              <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                No hay viajes próximos agendados.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($viajes as $viaje): ?>
              <tr class="border-b hover:bg-gray-50">
                <!-- ID -->
                <td class="px-4 py-2"><?= $viaje['id_viaje_proximo'] ?></td>

                <!-- Lugar de salida -->
                <td class="px-4 py-2">
                  <?= htmlspecialchars($viaje['lugar_salida_proximo']) ?>
                </td>

                <!-- Lugar de destino -->
                <td class="px-4 py-2">
                  <?= htmlspecialchars($viaje['lugar_destino_proximo']) ?>
                </td>

                <!-- Fecha de salida -->
                <td class="px-4 py-2">
                  <?= date('d/m/Y H:i', strtotime($viaje['fecha_salida_proximo'])) ?>
                </td>

                <!-- Fecha de entrega -->
                <td class="px-4 py-2">
                  <?= $viaje['fecha_entrega_proximo']
                        ? date('d/m/Y H:i', strtotime($viaje['fecha_entrega_proximo']))
                        : '-' 
                  ?>
                </td>

                <!-- Acciones (Editar / Eliminar) -->
                <td class="px-4 py-2 text-center space-x-2">
                  <!-- Editar -->
                  <a href="index.php?route=admin/editarViajeProximo&id=<?= $viaje['id_viaje_proximo'] ?>"
                     class="text-blue-600 hover:underline">
                    Editar
                  </a>
                  |
                  <!-- Eliminar (confirmación con JavaScript) -->
                  <a href="index.php?route=admin/eliminarViajeProximo&id=<?= $viaje['id_viaje_proximo'] ?>"
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

    <!-- Link para volver al dashboard de admin -->
    <div class="mt-6 text-center">
      <a href="index.php?route=home" class="text-gray-600 hover:underline">
        ← Volver al Dashboard
      </a>
    </div>

  </div>

</body>
</html>
