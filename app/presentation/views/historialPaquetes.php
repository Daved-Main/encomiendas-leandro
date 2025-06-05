<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Encomiendas Agendadas</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include __DIR__ . '/iu/navbar.php'; ?>

<div class="pt-20 max-w-6xl mx-auto px-4">
  <h2 class="text-3xl font-bold mb-6">Mis Encomiendas Agendadas</h2>

  <div class="bg-white shadow rounded-lg overflow-hidden mb-10">
    <table class="min-w-full table-auto">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 text-left">Código</th>
          <th class="px-4 py-2 text-left">Destinatario</th>
          <th class="px-4 py-2 text-left">Ciudad</th>
          <th class="px-4 py-2 text-left">Dirección</th>
          <th class="px-4 py-2 text-left">Fecha</th>
          <th class="px-4 py-2 text-left">Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paquetes)): ?>
          <tr>
            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
              No has agendado ninguna encomienda.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($paquetes as $p): ?>
            <tr class="border-t hover:bg-gray-100">
              <td class="px-4 py-2 font-mono text-sm"><?= htmlspecialchars($p->getCodigoRastreo()) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($p->getNombreDestinatario()) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($p->getCiudadDestino()) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($p->getDireccionDestino()) ?></td>
              <td class="px-4 py-2"><?= $p->getFechaRegistro()->format('d/m/Y') ?></td>
              <td class="px-4 py-2">
                <span class="inline-block px-3 py-1 rounded-full text-white text-sm
                  <?php
                    echo match ($p->getEstado()) {
                      'Recibido'   => 'bg-blue-500',
                      'En camino'  => 'bg-yellow-500',
                      'Entregado'  => 'bg-green-500',
                      'Retenido'   => 'bg-red-500',
                      default      => 'bg-gray-500'
                    };
                  ?>">
                  <?= htmlspecialchars($p->getEstado()) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>

 <?php include __DIR__ . '/iu/footer.php'; ?>


</html>
