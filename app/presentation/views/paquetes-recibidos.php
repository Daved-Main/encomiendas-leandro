<!-- App/presentation/views -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Encomiendas Leandro</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
</head>
<body class="bg-white text-black">


<?php include __DIR__ . '/iu/navbar.php';?>

 
  <div class="pt-20 max-w-7xl mx-auto px-4">
  <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">📦 Lista de Paquetes Recibidos</h2>

  <!-- Filtros y exportación -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    
    <form method="GET" action="index.php">
      <input type="hidden" name="route" value="listar_paquetes">
      <label class="text-sm font-medium text-gray-600">Filtrar por fecha</label><br>
      <input type="date" name="fecha" value="<?= $_GET['fecha'] ?? '' ?>" class="border border-gray-300 rounded-md px-4 py-2 text-sm">
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md ml-2 text-sm">
        Filtrar
      </button>
    </form>


      <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
        🖨️ Imprimir Viñetas
      </button>
    
  </div>

  <!-- Tabla de paquetes -->
  <div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-gray-100 border-b border-gray-300">
        <tr>
          <th class="px-4 py-3 font-semibold">Código</th>
          <th class="px-4 py-3 font-semibold">Remitente</th>
          <th class="px-4 py-3 font-semibold">Destinatario</th>
          <th class="px-4 py-3 font-semibold">Tipo</th>
          <th class="px-4 py-3 font-semibold">Contenido</th>
          <th class="px-4 py-3 font-semibold">Bultos</th>
          <th class="px-4 py-3 font-semibold">Estado</th>
          <th class="px-4 py-3 font-semibold text-center">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php foreach ($paquetes as $paquete): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2"><?= $paquete->getCodigoRastreo() ?></td>
            <td class="px-4 py-2"><?= $paquete->getNombreRemitente() ?></td>
            <td class="px-4 py-2"><?= $paquete->getNombreDestinatario() ?></td>
            <td class="px-4 py-2"><?= $paquete->getTipoPaquete() ?></td>
            <td class="px-4 py-2"><?= $paquete->getNombreDelArticulo() ?></td>
            <td class="px-4 py-2"><?= $paquete->getCantidadBultos() ?></td>
            <td class="px-4 py-2">
              <form method="POST" action="index.php?route=actualizar_estado" class="flex items-center gap-2">
                <input type="hidden" name="id_paquete" value="<?= $paquete->getId() ?>">
                <select name="estado" class="border rounded px-2 py-1 text-sm">
                  <?php
                    $estados = ['Recibido', 'En camino', 'Entregado', 'Retenido'];
                    foreach ($estados as $estado) {
                      $selected = $paquete->getEstado() === $estado ? 'selected' : '';
                      echo "<option value=\"$estado\" $selected>$estado</option>";
                    }
                  ?>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                  Actualizar
                </button>
              </form>
            </td>
            <td class="px-4 py-2 text-center">
              <form method="POST" action="index.php?route=generar_vineta" target="_blank">
                <input type="hidden" name="codigo_rastreo" value="<?= $paquete->getCodigoRastreo() ?>">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                  Viñeta
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
