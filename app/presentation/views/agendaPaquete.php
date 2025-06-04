<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda tu Paquete</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/iu/navbar.php';?>
    <div class="h-100 w-full content-center text-center bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF]">
        <h2 class="text-white font-bold" id="Slogan">Movemos tus encomiendas <br> con precisión y confianza.</h2>
    </div>
    <div class="flex content-center place-self-center">
        <h1 class="mt-15 font-bold text-5xl">¿Qué enviará hoy?</h1>
    </div>
        <div class="grid grid-cols-2 gap-48">
            <div class="w-100 place-self-end mt-15">
                <span class="font-semibold text-2xl">Información del Remitente</span>
                <form action="" method="POST" class="mt-5">
                    <label for="nombre-remi" class="text-black text-sm">Nombre Completo</label>
                    <input type="text" name="nombre-remi" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                    <label for="telefono-remi" class="text-black text-sm">Número Telefónico</label>
                    <input type="text" name="telefono-remi" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"><br><br>
                <span class="font-semibold text-2xl">Información del Destinatario</span><br><br>
                    <label for="nombre-desti" class="text-black text-sm">Nombre Completo</label>
                    <input type="text" name="nombre-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                    <label for="telefono-desti" class="text-black text-sm">Número Telefónico</label>
                    <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"><br><br>
                <span class="font-semibold text-2xl">Lugar de Destino</span><br><br>
                    <label for="nombre-desti" class="text-black text-sm">Ciudad de Destino</label>
                    <select class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        <option selected></option>
                        <option>Opción 1</option>
                        <option>Opción 2</option>
                    </select>
                    <label for="telefono-desti" class="text-black text-sm">Dirección</label>
                    <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10"><br><br>
                <span class="font-semibold text-2xl">Detalles del Paquete</span><br><br>
                     <label for="nombre-desti" class="text-black text-sm">Tipo de Paquete</label>
                    <select class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        <option selected></option>
                        <option>Opción 1</option>
                        <option>Opción 2</option>
                    </select>
                    <div class="flex flex-row gap-4">
                        <div class="basis-2/3">
                        <label for="telefono-desti" class="text-black text-sm">Dirección</label>
                        <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        </div>
                        <div class="basis-1/3 ">
                        <label for="telefono-desti" class="text-black text-sm">Cantidad de Bultos</label>
                        <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        </div>
                    </div>
                    <div class="flex flex-row gap-4">
                        <div class="basis-1/3 ">
                        <label for="telefono-desti" class="text-black text-sm">Peso (Libras)</label>
                        <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        </div>
                        <div class="basis-1/3 ">
                        <label for="telefono-desti" class="text-black text-sm">Alto</label>
                        <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        </div>
                        <div class="basis-1/3 ">
                        <label for="telefono-desti" class="text-black text-sm">Ancho</label>
                        <input type="text" name="telefono-desti" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
                        </div>
                    </div>
                    <div class="flex flex-row">
                    <input type="checkbox">
                    <p class="text-sm text-black ml-1">¿Contenido Frágil?</p>
                    </div>
                    <input type="submit" value="Enviar Solicitud" class="bg-[#009966] p-2 px-3 mt-10 mb-10 rounded-md text-white font-semibold transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-105 hover:bg-green-800 cursor-pointer">
            </div>
            <div class="mt-16 place-self-start">
            <div class="bg-[#D6E4EF] h-130 w-90 place-self-center rounded-xl shadow-2xl">
                <div class="place-self-center pt-5 text-2xl">
                    <span class="font-bold">Resumen</span>
                </div>
                <div class="px-15 pt-10">
                    <p class="font-semibold">Remitente</p>
                    <p>-</p>
                </div>
                <div class="px-15 pt-5">
                    <p class="font-semibold">Destinatario</p>
                    <p>-</p>
                </div>
                <div class="px-15 pt-5">
                    <p class="font-semibold">Lugar de Destino</p>
                    <p>-</p>
                </div>
                <div class="px-15 pt-5">
                    <p class="font-semibold">Tipo de Paquete</p>
                    <p>-</p>
                </div>
                <div class="px-15 pt-5">
                    <p class="font-semibold">Artículo</p>
                    <p>-</p>
                </div>
                <div class="px-15 pt-5">
                    <p class="font-semibold">Contenido Frágil</p>
                    <p>-</p>
                </div>
            </div>
            <p class="place-self-center mt-8 font-bold font-4xl"><i class="fa-solid fa-circle-exclamation"></i> Empaqueta bien tus productos frágiles</p>
            </div>
        </div>
</body>
<?php include __DIR__ . '/iu/footer.php'; ?>

</html>