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
    <title>Crear Cuenta</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">
    <div class="bg-white p-8 rounded-lg shadow-md w-sm h-150 pt-10">
        <h2 class="text-center text-xl font-bold mb-4 pb-20">Registro</h2>
        <form action="index.php?route=registrar" method="POST" class="pl-7 pr-7">
          
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
            <label for="username" class="text-gray-600/70">Usuario</label>
            <input type="text" name="name" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
            
            <label for="username" class="text-gray-600/70">Correo</label>
            <input type="text" name="email" class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">
            
            <label for="password" class="text-gray-600/70">Contraseña</label>
            <input type="password" name="password" class="w-full p-2 border mb-1 rounded-lg border-gray-500/50 bg-gray-400/10">

            <div class="flex flex-row items-center mb-4">
              <input id="terms" name="terms" type="checkbox" required class="mr-2">
              <a href="index.php?route=terminos"><label for="terms" class="text-sm text-gray-400">Acepto los términos y condiciones</label></a>
            </div>


            <button class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500 mt-6 cursor-pointer" type="submit">Registrar</button>
            <p class="text-sm pl-4 pt-4">¿Ya tienes una cuenta? <a href="index.php?route=login" class="text-sky-500">¡Inicia Sesión!</a></p>
            
        </form>
    </div>

<?php if (!empty($_SESSION['errors'])): 
    $flash   = $_SESSION['errors'];
    $isError = $flash['type'] === 'error';
  ?>
  <div id="flashOverlay" class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
    <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-xl shadow-lg w-80 mx-4">
      <div class="px-6 py-4 border-b <?= $isError ? 'border-red-400' : 'border-green-400' ?>">
        <h2 class="text-lg font-semibold">
          <?= $isError ? '¡Error!' : '¡Éxito!' ?>
        </h2>
      </div>
      <div class="px-6 py-4">
        <ul class="list-disc list-inside space-y-1">
          <?php foreach ($flash['messages'] as $msg): ?>
            <li><?= htmlspecialchars($msg) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="px-6 py-4 flex justify-end space-x-2 border-t">
        <?php if (! $isError): ?>
          <a href="index.php?route=home"
             class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
            Inicio
          </a>
          <a href="index.php?route=login"
             class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Login
          </a>
        <?php else: ?>
          <button id="flashClose"
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            Cerrar
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="app/presentation/views/iu/registrar.js" defer></script>
  <?php unset($_SESSION['errors']); endif; ?>

</body>
</html>