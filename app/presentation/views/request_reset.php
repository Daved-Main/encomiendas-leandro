<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Flashes de error/éxito
$errors  = $_SESSION['errors']   ?? [];
$success = $_SESSION['success']  ?? [];
$msgsErr = $errors['messages']   ?? [];
$msgsOk  = $success['messages']  ?? [];
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar Contraseña</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
</head>
<body class="relative flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">
  <!-- Contenedor padre (relativo) para posicionar la X -->
  <div class="absolute inset-0 flex items-center justify-center">
    <!-- “X” de cerrar, posicionada sobre el degradado, justo arriba a la derecha del card -->
    <a href="index.php?route=login"
       class="absolute top-4 right-4 
              w-10 h-10                        
              flex items-center justify-center 
              bg-gray-200 hover:bg-gray-300    
              rounded-full shadow-md           
              transition">
      <span class="text-3xl text-gray-600 hover:text-gray-900 leading-none"
            style="transform: translateY(-3px);">
        &times;
      </span>
    </a>

    <!-- Aquí va el cuadro blanco centrado -->
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
      <!-- Título -->
      <h2 class="text-center text-2xl font-bold mb-6">Recuperar Contraseña</h2>

      <!-- Mensajes de éxito -->
      <?php if (!empty($msgsOk)): ?>
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
          <ul class="list-disc list-inside space-y-1">
            <?php foreach ($msgsOk as $m): ?>
              <li><?= htmlspecialchars($m) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Mensajes de error -->
      <?php if (!empty($msgsErr)): ?>
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
          <ul class="list-disc list-inside space-y-1">
            <?php foreach ($msgsErr as $m): ?>
              <li><?= htmlspecialchars($m) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Formulario de solicitud -->
      <form action="index.php?route=request_reset" method="POST" class="space-y-4">
        <div>
          <label for="email" class="block text-gray-600 mb-1">Correo Electrónico</label>
          <input type="email"
                 name="email"
                 id="email"
                 required
                 placeholder="usuario@ejemplo.com"
                 class="w-full p-2 border border-gray-300 rounded-lg bg-gray-50
                        focus:outline-none focus:ring-2 focus:ring-cyan-300 transition"
          >
        </div>
        <button type="submit"
                class="w-full text-white py-2 rounded-full 
                       bg-gradient-to-r from-teal-300/70 to-cyan-500 hover:opacity-90 transition"
        >
          Enviar Enlace de Recuperación
        </button>
      </form>

      <!-- Enlace secundario para volver a login -->
      <p class="mt-6 text-center text-sm text-gray-600">
        ¿Recordaste tu contraseña?
        <a href="index.php?route=login" class="text-sky-500 hover:underline">Inicia Sesión</a>
      </p>
    </div>
  </div>
</body>
</html>
