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

 
  <div class="flex">
    <div class="flex-1 p-20">
        <h2 class="text-4xl font-bold text-left mb-10 mt-20">Lista de Paquetes Recibidos</h2>
        <div class="grid grid-cols-2">
          <div class="justify-start">
            <label class="text-sm">Filtrar por fecha</label><br>
            <input type="date" class="border-2 border-gray-300 rounded-lg px-5 text-gray-500/80">
            <input type="submit" value="Filtrar" class="bg-[#0068CC] text-white px-2 py-1 rounded-lg ml-4 cursor-pointer">
          </div>
          <div class="justify-end place-self-end">
            <input type="submit" value="Imprimir Viñetas" class="bg-[#009966] text-white px-2 py-1 rounded-lg cursor-pointer">
          </div>
        </div>
        <div class="bg-[#D9D9D9] w-800 border-2 border-gray-400/60 rounded-lg mt-20">
        <table class="table-auto w-full text-center">
          <thead class="border-collapse border-b-2 border-gray-400/60">
            <tr>
              <th>Código</th>
              <th>Remitente</th>
              <th>Destinatario</th>
              <th>Tipo</th>
              <th>Contenido</th>
              <th>Bultos</th>
              <th class="border-collapse border-l-2 border-gray-400/60">Estado</th>
              <th class="border-collapse border-l-2 border-gray-400/60">Acciones</th>
            </tr>
          </thead>

          <!-- Coloca aquí la misma estructura HTML que ya tienes,
          y reemplaza solo el tbody de la tabla: -->
<tbody>
<?php foreach ($paquetes as $paquete): ?>
  <tr>
    <td><?= $paquete->getCodigoRastreo() ?></td>
    <td><?= $paquete->getNombreRemitente() ?></td>
    <td><?= $paquete->getNombreDestinatario() ?></td>
    <td><?= $paquete->getTipoPaquete() ?></td>
    <td><?= $paquete->getNombreDelArticulo() ?></td>
    <td><?= $paquete->getCantidadBultos() ?></td>

    <!-- Columna de estado y actualización -->
    <td class="border-collapse border-l-2 border-gray-400/60 w-60">
      <form method="POST" action="index.php?route=actualizar_estado" class="flex gap-2 items-center">
        <input type="hidden" name="id_paquete" value="<?= $paquete->getId() ?>">
        <select name="estado" class="border rounded px-2 py-1">
          <?php
            $estados = ['Recibido', 'En camino', 'Entregado', 'Retenido'];
            foreach ($estados as $estado) {
              $selected = $paquete->getEstado() === $estado ? 'selected' : '';
              echo "<option value=\"$estado\" $selected>$estado</option>";
            }
          ?>
        </select>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-3 py-1 rounded">
          Actualizar
        </button>
      </form>
    </td>
    <td class="border-collapse border-l-2 border-gray-400/60"><button class="bg-[#0068CC] text-white rounded-lg cursor-pointer px-1 m-2">Viñeta</button></td>
  </tr>
<?php endforeach; ?>
</tbody>


        </table>
        </div>
    </div>
  </div>