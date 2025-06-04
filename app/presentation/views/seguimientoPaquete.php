<?php 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Paquete</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/iu/navbar.php';?>

    <div class="h-100 w-full content-center text-center bg-gradient-to-r from-[#1E90FF] via-[#B3DAFF] to-[#A680FF]">
        <h2 class="text-white font-bold" id="Slogan">Movemos tus encomiendas <br> con precisión y confianza.</h2>
    </div>
    <div class="flex place-self-center">
        <h1 class="mt-15 font-bold text-5xl">Consulta el estado de tu paquete</h1>
    </div>
    <div class="place-self-center mt-15 text-lg">
        <p>Ingresa tu código de seguimiento para ver la ruta del envío</p>
    </div>

     <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Formulario de búsqueda -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <label for="tracking" class="block text-sm font-medium text-gray-700 mb-1">Código de seguimiento</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="tracking" 
                            placeholder="Ej: 01 05 2489" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition duration-200">
                        Revisar estado
                    </button>
                </div>
            </div>
        </div>

        <!-- Timeline de seguimiento -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Historial de seguimiento</h2>
            
            <div class="space-y-8">
                <!-- Paso 1 -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="w-px h-full bg-gray-300 mt-2"></div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-gray-800">Solicitud registrada</h3>
                            <span class="text-sm text-gray-500">22/04/2025, 10:15</span>
                        </div>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="w-px h-full bg-gray-300 mt-2"></div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-800">Verificación</h3>
                                <p class="text-sm text-gray-600 mt-1">Paquete recibido</p>
                            </div>
                            <span class="text-sm text-gray-500">22/04/2025, 16:32</span>
                        </div>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-full text-white">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="w-px h-full bg-gray-300 mt-2"></div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start">
                            <h3 class="font-medium text-gray-800">En tránsito</h3>
                            <span class="text-sm text-gray-500">En progreso</span>
                        </div>
                    </div>
                </div>

                <!-- Paso 4 -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-300 rounded-full text-white">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-medium text-gray-400">Paquete entregado</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include __DIR__ . '/iu/footer.php'; ?>
</html>