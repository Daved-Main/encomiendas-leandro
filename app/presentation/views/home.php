<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/encomiedasLeandro/app/presentation/views/iu/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/iu/navbar.php';?>
    <div class="h-100 w-full content-center text-center bg-gradient-to-l from-violet-400 via-cyan-300/60 to-blue-400">
        <h1 class="text-black" id="slogan">¡Slogan!</h1>
        <span class="text-black italic">Ver más información</span>
    </div>

<div class="">
    <div class="flex flex-col md:flex-row items-center gap-4 place-self-start bg-white p-4 md:p-0">
    <img class="mask-radial-[100%_100%] mask-radial-from-50% mask-radial-at-left h-120 w-full md:w-auto object-cover" src="/encomiedasLeandro/app/presentation/views/iu/avion.jpg" alt="se encontro la imagen"/>

    <div class="font-large w-full md:w-auto">
      <p class="text-xl text-black font-bold uppercase text-center md:text-left" id="part-1">
        Movemos tus encomiendas <br/>
        con precisión y confianza
      </p>
      <p class="mt-2 text-gray-400 text-center md:text-left">
        Optimiza el envío de tus paquetes con un servicio inteligente.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full mt-5">
        <a href="index.php?route=agendaPaquete">
          <button type="button" class="bg-[#0066CC] p-2 px-3 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-blue-800 cursor-pointer w-full">
            Envía un paquete
          </button>
        </a>
        <a href="index.php?route=cotizaEnvio">
          <button
            type="button"
            class="
              bg-[#009966] p-2 px-3 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer w-full">
            Cotiza tu envío
          </button>
        </a>
      </div>
    </div>
  </div>
</div>



    <div class="border-1 mx-10 border-gray-600/20"></div>
    <div class="h-100 w-full text-center pt-15">
        <h1 id="part-2">Nuestros Servicios</h1>
        <div class="flex flex-row content-center place-self-center mt-15">
            <div class="w-64 h-65 bg-[#1E90FF] rounded-md text-white mx-10">
                <h2 class="py-5 px-5" id="cotizacion">Cotización de Envíos</h2>
                <p>Cotiza el envio de tus paquetes a las localidades para las cuales ofrecemos cobertura.</p>
            </div>
            <div class="w-64 bg-[#0066CC] rounded-md text-white mx-10">
                <h2 class="py-5" id="paquetes">Seguimiento de Paquetes</h2>
                <p>Consulta el estado de tu paquete durante el trayecto a su destino.</p>
            </div>
            <div class="w-64 bg-[#003366] rounded-md text-white mx-10">
                <h2 id="viajes" class="py-5">Calendario de Viajes</h2>
                <p>Donde podrás ver cuáles son las próximas fechas de viajes y recogida de paquetes.</p>
            </div>
        </div>
    </div>
    <div class="border-1 mx-10 my-20 border-gray-600/20"></div>
    <div class="content-center text-center place-self-center">

        <h1 id="part-3" class="text-2xl font-bold mb-6">¿Listo para enviar tu próxima encomienda?</h1>

        <!-- Si existe un viaje cargado, mostramos la tarjeta azul -->
        <?php if (!is_null($proximo)): ?>
            <div class="bg-[#007BFF] text-white rounded-lg mx-auto p-6 mb-8" style="max-width: 600px;">
                <h2 class="text-xl font-semibold mb-3">
                    Próximo viaje: <?= date('d/m/Y', strtotime($proximo['fecha_salida_proximo'])) ?>
                </h2>
                <p class="mb-1">
                    <span class="font-medium">Salida:</span>
                    <?= htmlspecialchars($proximo['lugar_salida_proximo']) ?>
                    —
                    <span class="font-medium">Destino:</span>
                    <?= htmlspecialchars($proximo['lugar_destino_proximo']) ?>
                </p>
                <p class="mb-1">
                    <span class="font-medium">Hora de recogida:</span>
                    <?= date('H:i', strtotime($proximo['fecha_salida_proximo'])) ?>
                </p>
                <p>
                    <span class="font-medium">Hora estimada de entrega:</span>
                    <?= $proximo['fecha_entrega_proximo']
                          ? date('d/m/Y H:i', strtotime($proximo['fecha_entrega_proximo']))
                          : '-' ?>
                </p>
            </div>
        <?php else: ?>
            <!-- Si no hay viajes programados -->
            <p class="text-gray-600 mb-6">No hay viajes programados en este momento.</p>
        <?php endif; ?>

        <!-- Botón verde de “¡Comienza ahora!” -->
    <a href="index.php?route=agendaPaquete"><button type="submit" value="¡Comienza ahora!" class="bg-[#009966] p-2 px-3 mb-10 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer">¡Comienza ahora!</button></a>
        <p class="text-gray-600">¡Estamos ansiosos por atenderte!</p>
    </div>
    
</body>
<?php include __DIR__ . '/iu/footer.php'; ?>
</html>