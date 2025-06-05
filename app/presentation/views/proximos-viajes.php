<?php
// Archivo: app/presentation/views/proximos-viajes.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Próximos Viajes — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <?php include __DIR__ . '/iu/navbar.php';?>

  <div class="max-w-4xl mx-auto mt-12 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Consulta nuestros próximos viajes</h1>

    <?php if (!empty($viajesProximos)):
        $fechasSalida = array_column($viajesProximos, 'fecha_salida_actual');
        $fechasEntrega = array_column($viajesProximos, 'fecha_entrega_actual');
        $primeraSalida = date('d/m/Y', strtotime(min($fechasSalida)));
        $fechasEntregaFiltradas = array_filter($fechasEntrega, fn($f) => !is_null($f));
        $ultimaEntrega = !empty($fechasEntregaFiltradas) ? date('d/m/Y', strtotime(max($fechasEntregaFiltradas))) : null;
    ?>
      <p class="text-lg mb-4 text-center">
        Viajes programados:
        <span class="font-semibold"><?= $primeraSalida ?></span>
        –
        <span class="font-semibold">
          <?= $ultimaEntrega ?: '-' ?>
        </span>
      </p>
    <?php endif; ?>

    <div class="overflow-x-auto mb-8">
      <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-2 text-left">Lugar de salida</th>
            <th class="px-4 py-2 text-left">Lugar de destino</th>
            <th class="px-4 py-2 text-left">Fecha de salida</th>
            <th class="px-4 py-2 text-left">Fecha de entrega</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($viajesProximos)): ?>
            <tr>
              <td colspan="4" class="px-4 py-3 text-center text-gray-500">No hay viajes programados en este momento.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($viajesProximos as $viaje): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2"> <?= htmlspecialchars($viaje['lugar_salida_actual']) ?> </td>
                <td class="px-4 py-2"> <?= htmlspecialchars($viaje['lugar_destino_actual']) ?> </td>
                <td class="px-4 py-2"> <?= date('d/m/Y H:i', strtotime($viaje['fecha_salida_actual'])) ?> </td>
                <td class="px-4 py-2">
                  <?= $viaje['fecha_entrega_actual'] ? date('d/m/Y H:i', strtotime($viaje['fecha_entrega_actual'])) : '-' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php 
    if (!empty($viajesProximos)) {
        usort($viajesProximos, fn($a, $b) => strtotime($a['fecha_salida_actual']) <=> strtotime($b['fecha_salida_actual']));
        $proximo = $viajesProximos[0];
    ?>
      <div class="bg-blue-500 text-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">
          Próximo viaje: <?= date('d/m/Y', strtotime($proximo['fecha_salida_actual'])) ?>
        </h2>
        <p class="mb-1">
          <span class="font-medium">Salida:</span>
          <?= htmlspecialchars($proximo['lugar_salida_actual']) ?> – 
          <span class="font-medium">Destino:</span>
          <?= htmlspecialchars($proximo['lugar_destino_actual']) ?>
        </p>
        <p class="mb-1">
          <span class="font-medium">Hora de recogida:</span>
          <?= date('H:i', strtotime($proximo['fecha_salida_actual'])) ?>
        </p>
        <p>
          <span class="font-medium">Hora estimada de entrega:</span>
          <?= $proximo['fecha_entrega_actual'] ? date('d/m/Y H:i', strtotime($proximo['fecha_entrega_actual'])) : '-' ?>
        </p>
      </div>
    <?php } ?>

    <div class="text-center mt-6">
      <a href="index.php?route=home" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition">
        ¡Comienza ahora!
      </a>
    </div>
  </div>
  <?php include __DIR__ . '/iu/footer.php'; ?>
</body>
</html>
