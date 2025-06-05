<?php
// app/presentation/views/admin/editar-viaje-proximo.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? [];
unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Viaje Actual — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="max-w-lg mx-auto mt-12 bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Editar Viaje Actual</h2>

    <?php if (!empty($success)): ?>
      <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
        <?php foreach ($success['messages'] as $m): ?>
          <p><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php foreach ($errors['messages'] as $m): ?>
          <p><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="index.php?route=admin/actualizarViajeProximo" method="POST" class="space-y-4">
      <input type="hidden" name="id_viaje_proximo" value="<?= $fila['id_viaje_actual'] ?>">

      <div>
        <label for="lugar_salida_proximo" class="block text-gray-600 mb-1">Lugar de salida</label>
        <input
          type="text"
          name="lugar_salida_proximo"
          id="lugar_salida_proximo"
          required
          value="<?= htmlspecialchars($fila['lugar_salida_actual']) ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="lugar_destino_proximo" class="block text-gray-600 mb-1">Lugar de destino</label>
        <input
          type="text"
          name="lugar_destino_proximo"
          id="lugar_destino_proximo"
          required
          value="<?= htmlspecialchars($fila['lugar_destino_actual']) ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="fecha_salida_proximo" class="block text-gray-600 mb-1">Fecha y hora de salida</label>
        <input
          type="datetime-local"
          name="fecha_salida_proximo"
          id="fecha_salida_proximo"
          required
          value="<?= date('Y-m-d\TH:i', strtotime($fila['fecha_salida_actual'])) ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="fecha_entrega_proximo" class="block text-gray-600 mb-1">Fecha y hora estimada de entrega (opcional)</label>
        <input
          type="datetime-local"
          name="fecha_entrega_proximo"
          id="fecha_entrega_proximo"
          value="<?= $fila['fecha_entrega_actual'] ? date('Y-m-d\TH:i', strtotime($fila['fecha_entrega_actual'])) : '' ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="capacidad_paquetes" class="block text-gray-600 mb-1">Capacidad de paquetes</label>
        <input
          type="number"
          name="capacidad_paquetes"
          id="capacidad_paquetes"
          min="1" max="40"
          required
          value="<?= (int)$fila['capacidad_paquetes'] ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div>
        <label for="id_viaje_mes" class="block text-gray-600 mb-1">Número de viaje del mes</label>
        <input
          type="number"
          name="id_viaje_mes"
          id="id_viaje_mes"
          min="1"
          required
          value="<?= (int)$fila['id_viaje_mes'] ?>"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
      </div>

      <div class="text-center space-x-4">
        <button
          type="submit"
          class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
          Guardar cambios
        </button>
        <a href="index.php?route=admin/listarViajeProximo" class="text-gray-600 hover:underline">
          Cancelar
        </a>
      </div>
    </form>
  </div>

</body>
</html>
