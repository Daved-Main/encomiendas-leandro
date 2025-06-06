<!-- CÓDIGO COMPLETO ACTUALIZADO: -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cotiza tu Paquete</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white text-gray-800">
  <?php include __DIR__ . '/iu/navbar.php'; ?>

  <div class="w-full text-center bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF] py-6">
    <h2 class="text-white font-bold text-xl md:text-2xl" id="Slogan">
      Movemos tus encomiendas <br> con precisión y confianza.
    </h2>
  </div>

  <div class="text-center mt-10">
    <h1 class="font-bold text-3xl md:text-5xl">Cotiza tu Envío</h1>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 md:px-8 mt-10 mb-16">
    <!-- Formulario -->
    <div class="w-full max-w-2xl mx-auto">
      <form id="cotizacionForm" method="POST">
        <span class="font-semibold text-2xl">Información del Remitente</span>
        <div class="mt-5">
          <label class="text-black text-sm">Nombre Completo</label>
          <input id="nombre-remi" type="text" name="nombre-remi"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
          <label class="text-black text-sm">Número Telefónico</label>
          <input id="telefono-remi" type="tel" name="telefono-remi"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Información del Destinatario</span>
        <div class="mt-5">
          <label class="text-black text-sm">Nombre Completo</label>
          <input id="nombre-desti" type="text" name="nombre-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
          <label class="text-black text-sm">Número Telefónico</label>
          <input id="telefono-desti" type="tel" name="telefono-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Lugar de Destino</span>
        <div class="mt-5">
          <label class="text-black text-sm">Ciudad de Destino</label>
          <select id="ciudad-desti" name="ciudad-desti"
                  class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            <option value="" disabled selected>Selecciona ciudad</option>
            <option>San Salvador</option>
            <option>Santa Tecla</option>
            <option>Ahuachapán</option>
          </select>
          <label class="text-black text-sm">Dirección Completa</label>
          <input id="direccion-desti" type="text" name="direccion-desti"
                 class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
        </div>

        <span class="font-semibold text-2xl">Detalles del Paquete</span>
        <div class="mt-5">
          <label class="text-black text-sm">Tipo de Paquete</label>
          <select id="tipo-paquete" name="tipo-paquete"
                  class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10" required>
            <option value="" disabled selected>Selecciona tipo</option>
            <option>Documentos</option>
            <option>Electrónicos</option>
            <option>Ropa</option>
            <option>Otros</option>
          </select>

          <div class="flex flex-wrap gap-4">
            <div class="basis-full sm:basis-2/3">
              <label class="text-black text-sm">Descripción/Artículo</label>
              <input id="articulo" type="text" name="articulo"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="Ej: Celular, Ropa, Libros, etc.">
            </div>
            <div class="basis-full sm:basis-1/3">
              <label class="text-black text-sm">Cantidad de Bultos</label>
              <input id="bultos" type="number" name="bultos" min="1" value="1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
            </div>
          </div>

          <div class="flex flex-wrap gap-4">
            <div class="basis-full sm:basis-1/3">
              <label class="text-black text-sm">Peso (Libras)</label>
              <input id="peso" type="number" name="peso" min="0" step="0.1" required
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 5.5">
            </div>
            <div class="basis-full sm:basis-1/3">
              <label class="text-black text-sm">Alto (cm)</label>
              <input id="alto" type="number" name="alto" min="0" step="0.1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 30">
            </div>
            <div class="basis-full sm:basis-1/3">
              <label class="text-black text-sm">Ancho (cm)</label>
              <input id="ancho" type="number" name="ancho" min="0" step="0.1"
                     class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"
                     placeholder="ej. 20">
            </div>
          </div>

          <div class="flex items-center">
            <input id="fragil" type="checkbox" name="fragil"
                   class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="fragil" class="text-black text-sm ml-2">¿Contenido Frágil?</label>
          </div>
        </div>

        <div class="mt-10 mb-10">
          <button id="btnCalcular" type="button"
                  class="bg-[#009966] p-2 px-3 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer">
            Calcular Costo
          </button>
        </div>
      </form>
    </div>

    <!-- Panel de resumen -->
    <div class="w-full max-w-md mx-auto lg:mt-16">
      <div class="bg-[#D6E4EF] rounded-xl shadow-2xl p-6">
        <h3 class="text-center text-2xl font-bold mb-4">Resumen</h3>
        <div class="space-y-4">
          <div><p class="font-semibold">Remitente</p><p id="resRemitente">–</p></div>
          <div><p class="font-semibold">Destinatario</p><p id="resDestinatario">–</p></div>
          <div><p class="font-semibold">Lugar de Destino</p><p id="resDestino">–</p></div>
          <div><p class="font-semibold">Tipo de Paquete</p><p id="resTipo">–</p></div>
          <div><p class="font-semibold">Artículo</p><p id="resArticulo">–</p></div>
          <div><p class="font-semibold">Contenido Frágil</p><p id="resFragil">No</p></div>
          <div><p class="font-semibold">Costo Referencial</p><p id="resCosto">–</p></div>
        </div>
      </div>
      <p class="mt-6 text-center font-bold text-lg text-gray-800">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        Empaqueta bien tus productos frágiles
      </p>
    </div>
  </div>

  <?php include __DIR__ . '/iu/footer.php'; ?>

  <!-- Modal -->
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

  <script src="/app/presentation/views/iu/cotizar.js" defer></script>
</body>
</html>
