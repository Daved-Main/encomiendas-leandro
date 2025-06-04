<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
        <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">

    <div class="bg-white p-8 rounded-lg shadow-md w-sm h-150 pt-10">
        <h1 class="text-center text-xl font-bold mb-4 pb-20">Iniciar Sesión</h1>
        <form action="index.php?route=api/login" method="POST" class="pl-7 pr-7">
            <a href="index.php?route=home"
               class="absolute top-4 right-4 
                      w-10 h-10                        
                      flex items-center justify-center 
                      bg-gray-200 hover:bg-gray-300    
                      rounded-full shadow-md           
                      transition">
              <span class="text-3xl leading-none 
                           text-gray-600 hover:text-gray-900" style="transform: translateY(-3px);">
                &times;
              </span>
            </a>
            <label for="username" class="text-gray-600/70">Correo Electronico</label>
            <input type="email" name="email" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

            <label for="password" class="text-gray-600/70">Contraseña</label>
            <input type="password" name="password" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

            <button class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500  mt-3 cursor-pointer" type="submit">Iniciar</button>
            <p class="pl-13 pt-4 text-sm text-sky-500"><a href="index.php?route=request_reset">¿Olvidaste tu contraseña?</a></p>
            <div class="border-1 mt-4 border-gray-400/50"></div>
            <p class="place-self-center pt-3">Continuar con</p>
            
            <button class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500  mt-3 cursor-pointer">Google</button>
            <p class="text-sm place-self-center pt-4">¿Aún no tienes una cuenta? <a href="index.php?route=registrar" class="text-sky-500">¡Regístrate!</a></p>
        </form>
    </div>

    <!-- Ventana emergente -->
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
        <a href="index.php?route=home"
           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
          Inicio
        </a>
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
  </script>
  <?php unset($_SESSION['errors'], $_SESSION['success']); ?>
  <?php endif; ?>

</body>
</html>