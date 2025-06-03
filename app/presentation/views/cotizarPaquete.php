<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cotiza tu Paquete</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="/encomiedasLeandro/app/presentation/views/iu/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/iu/navbar.php'; ?>

  <!-- BANNER SUPERIOR (idéntico a agendar-paquete.php) -->
  <div class="h-100 w-full content-center text-center bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF]">
    <h2 class="text-white font-bold" id="Slogan">
      Movemos tus encomiendas <br> con precisión y confianza.
    </h2>
  </div>

  <!-- TÍTULO PRINCIPAL -->
  <div class="flex content-center place-self-center">
    <h1 class="mt-15 font-bold text-5xl">Cotiza tu Envío</h1>
  </div>

  <!-- CONTENEDOR PRINCIPAL (FORM + RESUMEN) -->
  <div class="grid grid-cols-2 gap-48 px-8 mt-8 mb-10">
    <!-- IZQUIERDA: FORMULARIO -->
    <div class="w-100 place-self-end mt-15">
      <form id="cotizacionForm" method="POST">
        <!-- Información del Remitente -->
        <span class="font-semibold text-2xl">Información del Remitente</span>
        <div class="mt-5">
          <label for="nombre-remi" class="text-black text-sm">Nombre Completo</label>
          <input id="nombre-remi" type="text" name="nombre-remi"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
          <label for="telefono-remi" class="text-black text-sm">Número Telefónico</label>
          <input id="telefono-remi" type="tel" name="telefono-remi"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <!-- Información del Destinatario -->
        <span class="font-semibold text-2xl">Información del Destinatario</span>
        <div class="mt-5">
          <label for="nombre-desti" class="text-black text-sm">Nombre Completo</label>
          <input id="nombre-desti" type="text" name="nombre-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
          <label for="telefono-desti" class="text-black text-sm">Número Telefónico</label>
          <input id="telefono-desti" type="tel" name="telefono-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <!-- Lugar de Destino -->
        <span class="font-semibold text-2xl">Lugar de Destino</span>
        <div class="mt-5">
          <label for="ciudad-desti" class="text-black text-sm">Ciudad de Destino</label>
          <select id="ciudad-desti" name="ciudad-desti"
                  class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            <option value="" disabled selected>Selecciona ciudad</option>
            <option>San Salvador</option>
            <option>Santa Tecla</option>
            <option>Ahuachapán</option>
            <option>...</option>
          </select>
          <label for="direccion-desti" class="text-black text-sm">Dirección Completa</label>
          <input id="direccion-desti" type="text" name="direccion-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <!-- Detalles del Paquete -->
        <span class="font-semibold text-2xl">Detalles del Paquete</span>
        <div class="mt-5">
          <label for="tipo-paquete" class="text-black text-sm">Tipo de Paquete</label>
          <select id="tipo-paquete" name="tipo-paquete"
                  class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            <option value="" disabled selected>Selecciona tipo</option>
            <option>Documentos</option>
            <option>Electrónicos</option>
            <option>Ropa</option>
            <option>Otros</option>
          </select>

          <div class="flex flex-row gap-4">
            <div class="basis-2/3">
              <label for="articulo" class="text-black text-sm">Descripción/Artículo</label>
              <input id="articulo" type="text" name="articulo"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="Ej: Celular, Ropa, Libros, etc.">
            </div>
            <div class="basis-1/3">
              <label for="bultos" class="text-black text-sm">Cantidad de Bultos</label>
              <input id="bultos" type="number" name="bultos" min="1" value="1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
            </div>
          </div>

          <div class="flex flex-row gap-4">
            <div class="basis-1/3">
              <label for="peso" class="text-black text-sm">Peso (Libras)</label>
              <input id="peso" type="number" name="peso" min="0" step="0.1" required
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 5.5">
            </div>
            <div class="basis-1/3">
              <label for="alto" class="text-black text-sm">Alto (cm)</label>
              <input id="alto" type="number" name="alto" min="0" step="0.1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 30">
            </div>
            <div class="basis-1/3">
              <label for="ancho" class="text-black text-sm">Ancho (cm)</label>
              <input id="ancho" type="number" name="ancho" min="0" step="0.1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 20">
            </div>
          </div>

          <div class="flex flex-row items-center">
            <input id="fragil" type="checkbox" name="fragil" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="fragil" class="text-black text-sm ml-1">¿Contenido Frágil?</label>
          </div>
        </div>

        <!-- Botón “Calcular Costo” -->
        <div class="mt-10 mb-10">
          <button id="btnCalcular" type="button"
                  class="bg-[#009966] p-2 px-3 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer">
            Calcular Costo
          </button>
        </div>
      </form>
    </div>

    <!-- DERECHA: PANEL “RESUMEN” (idéntico al de agendar-paquete.php, con un campo extra para costo) -->
<div class="mt-16 place-self-start">
  <!-- Contenedor principal del Resumen: sin altura fija, con padding y ancho máximo -->
  <div class="bg-[#D6E4EF] rounded-xl shadow-2xl mx-auto w-full max-w-xs p-6">
    <!-- Título centrado -->
    <h3 class="text-center text-2xl font-bold mb-4">Resumen</h3>
    
    <!-- Items con espacio entre ellos -->
    <div class="space-y-4">
      <div>
        <p class="font-semibold">Remitente</p>
        <p id="resRemitente" class="text-gray-700">–</p>
      </div>
      <div>
        <p class="font-semibold">Destinatario</p>
        <p id="resDestinatario" class="text-gray-700">–</p>
      </div>
      <div>
        <p class="font-semibold">Lugar de Destino</p>
        <p id="resDestino" class="text-gray-700">–</p>
      </div>
      <div>
        <p class="font-semibold">Tipo de Paquete</p>
        <p id="resTipo" class="text-gray-700">–</p>
      </div>
      <div>
        <p class="font-semibold">Artículo</p>
        <p id="resArticulo" class="text-gray-700">–</p>
      </div>
      <div>
        <p class="font-semibold">Contenido Frágil</p>
        <p id="resFragil" class="text-gray-700">No</p>
      </div>
      <div>
        <p class="font-semibold">Costo Referencial</p>
        <p id="resCosto" class="text-gray-700">–</p>
      </div>
    </div>
  </div>

  <!-- Mensaje de advertencia centrado abajo -->
  <p class="mt-6 flex items-center justify-center text-center font-bold text-lg text-gray-800">
    <i class="fa-solid fa-circle-exclamation mr-2"></i>
    Empaqueta bien tus productos frágiles
  </p>
</div>
  </div> <!-- /.grid principal -->

  <?php include __DIR__ . '/iu/footer.php'; ?>

  <!-- VENTANA MODAL DE COTIZACIÓN (oculta) -->
  <div id="modalCotizacion"
       class="fixed inset-0 bg-black/20 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg w-80 mx-4">
      <div class="px-6 py-4 border-b border-gray-300">
        <h2 class="text-lg font-semibold text-gray-800">Costo de Envío (Referencia)</h2>
      </div>
      <div class="px-6 py-4 space-y-3">
        <p id="textoCosto" class="text-gray-700 text-center text-xl"></p>
        <p class="text-sm text-gray-600 text-center">
          * Este monto es solo una referencia y podría variar según dimensiones, rutas y condiciones reales.
        </p>
      </div>
      <div class="px-6 py-4 flex justify-end border-t border-gray-300">
        <button id="btnCerrarModal"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
          Cerrar
        </button>
      </div>
    </div>
  </div>

  <script src="/encomiedasLeandro/app/presentation/views/iu/cotizar.js" defer></script>

</body>
</html>
