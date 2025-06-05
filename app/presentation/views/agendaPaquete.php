<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agendar Encomienda</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/iu/navbar.php'; ?>

  <div class="h-100 w-full content-center text-center bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF]">
    <h2 class="text-white font-bold" id="Slogan">
      Movemos tus encomiendas <br> con precisión y confianza.
    </h2>
  </div>

  <div class="flex content-center place-self-center">
    <h1 class="mt-15 font-bold text-5xl">Agendar Encomienda</h1>
  </div>

  <div class="grid grid-cols-2 gap-48 px-8 mt-8 mb-10">
    <!-- Formulario -->
    <div class="w-100 place-self-end mt-15">
      <form action="index.php?route=paquete_registrar" method="POST">
        <input type="hidden" name="id_viaje_mes" value="<?= $idViajeMes ?>">
        <input type="hidden" name="mes" value="<?= $mes ?>">
        <input type="hidden" name="anio" value="<?= $anio ?>">

        <span class="font-semibold text-2xl">        
          Viaje #<?= $numeroViaje ?> de <?= ucfirst($nombreMes) ?>
        </span><br><br>


        <span class="font-semibold text-2xl">Información del Remitente</span>
        <div class="mt-5">
          <label class="text-black text-sm">Nombre Completo</label>
          <input type="text" name="nombre_remitente" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>

          <label class="text-black text-sm">Número Telefónico</label>
          <input type="tel" name="telefono_remitente" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Información del Destinatario</span>
        <div class="mt-5">
          <label class="text-black text-sm">Nombre Completo</label>
          <input type="text" name="nombre_destinatario" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>

          <label class="text-black text-sm">Número Telefónico</label>
          <input type="tel" name="telefono_destinatario" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Lugar de Destino</span>
        <div class="mt-5">
        <label class="text-black text-sm">Ciudad de Destino</label>
        <select name="ciudad_destino" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
          <optgroup label="Estados Unidos">
            <option value="Louisiana">Louisiana</option>
          </optgroup>
          <optgroup label="El Salvador">
            <option value="Santa Elena">Santa Elena</option>
            <option value="Ilopango">Ilopango</option>
            <option value="Soyapango">Soyapango</option>
            <option value="San Salvador">San Salvador</option>
            <option value="Sonsonate">Sonsonate</option>
          </optgroup>
        </select>

          <label class="text-black text-sm">Dirección</label>
          <input type="text" name="direccion_destino" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Detalles del Paquete</span>
        <div class="mt-5">
          <label class="text-black text-sm">Tipo de Paquete</label>
          <input type="text" name="tipo_paquete" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>

          <label class="text-black text-sm">Nombre del Artículo</label>
          <input type="text" name="nombre_del_articulo" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>

          <div class="flex flex-row gap-4">
            <div class="basis-1/3">
              <label class="text-black text-sm">Bultos</label>
              <input type="number" name="cantidad_bultos" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            </div>
            <div class="basis-1/3">
              <label class="text-black text-sm">Peso (lb)</label>
              <input type="number" name="peso" step="0.01" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            </div>
            <div class="basis-1/3">
              <label class="text-black text-sm">Alto (cm)</label>
              <input type="number" name="alto" step="0.01" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            </div>
            <div class="basis-1/3">
              <label class="text-black text-sm">Ancho (cm)</label>
              <input type="number" name="ancho" step="0.01" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            </div>
          </div>

          <div class="flex flex-row items-center mt-3 mb-5">
            <input type="checkbox" name="contenido_fragil" id="contenido_fragil" class="mr-2">
            <label for="contenido_fragil" class="text-sm text-black">¿Contenido Frágil?</label>
          </div>
        </div>

        <button type="submit" class="bg-[#009966] p-2 px-3 mt-5 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer">
          Agendar Paquete
        </button>
      </form>
    </div>

    <!-- Panel de resumen -->
    <div class="mt-16 place-self-start">
      <div class="bg-[#D6E4EF] rounded-xl shadow-2xl mx-auto w-full max-w-xs p-6">
        <h3 class="text-center text-2xl font-bold mb-4">Resumen</h3>
        <div class="space-y-4">
          <div><p class="font-semibold">Remitente</p><p id="resRemitente">–</p></div>
          <div><p class="font-semibold">Destinatario</p><p id="resDestinatario">–</p></div>
          <div><p class="font-semibold">Destino</p><p id="resDestino">–</p></div>
          <div><p class="font-semibold">Tipo</p><p id="resTipo">–</p></div>
          <div><p class="font-semibold">Artículo</p><p id="resArticulo">–</p></div>
          <div><p class="font-semibold">¿Frágil?</p><p id="resFragil">No</p></div>
        </div>
      </div>
      <p class="mt-6 text-center font-bold text-lg text-gray-800">
        <i class="fa-solid fa-circle-exclamation"></i> Empaqueta bien tus productos frágiles
      </p>
    </div>
  </div>

  <?php include __DIR__ . '/iu/footer.php'; ?>

  <script src="/app/presentation/views/iu/agenda.js" defer></script>

  <?php if (!empty($_SESSION['errors'])): ?>
  <div id="flashOverlay"
       class="fixed inset-0 bg-black/20 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-xl shadow-lg w-80 mx-4">
      <div class="px-6 py-4 border-b <?= $_SESSION['errors']['type']=='error' 
                                          ? 'border-red-400' 
                                          : 'border-green-400' ?>">
        <h2 class="text-lg font-semibold text-gray-800">
          <?= $_SESSION['errors']['type']=='error' ? '¡Error!' : '¡Éxito!' ?>
        </h2>
      </div>
      <div class="px-6 py-4">
        <ul class="list-disc list-inside space-y-1 text-gray-700">
          <?php foreach ($_SESSION['errors']['messages'] ?? [] as $msg): ?>
            <li><?= htmlspecialchars($msg) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="px-6 py-4 flex justify-end space-x-2 border-t">
        <?php if ($_SESSION['errors']['type'] == 'success'): ?>
          <a href="index.php?route=home"
             class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
            Inicio
          </a>
        <?php endif; ?>
        <button id="flashClose"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
          Cerrar
        </button>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('flashClose').addEventListener('click', () => {
      document.getElementById('flashOverlay').remove();
    });
    document.getElementById('flashOverlay').addEventListener('click', e => {
      if (e.target.id === 'flashOverlay') {
        e.currentTarget.remove();
      }
    });

    // Auto cerrar después de 3s
    setTimeout(() => {
      const modal = document.getElementById('flashOverlay');
      if (modal) modal.remove();
    }, 3000);
  </script>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>


</body>
</html>