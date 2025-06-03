<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors   = $_SESSION['errors']   ?? [];
$type     = $errors['type']       ?? 'error';
$messages = $errors['messages']   ?? [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verificar Código</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">

  <div class="bg-white p-8 rounded-lg shadow-md w-sm h-auto pt-10 relative">
    <h1 class="text-center text-xl font-bold mb-4 pb-2">Verificar Código 2FA</h1>
    
    <a href="index.php?route=login"
       class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 transition">
      X
    </a>
    
    <form action="index.php?route=verificarCodigo" method="POST" class="px-4">
      <label for="code" class="text-gray-600/70">Código de Verificación</label>
      <input type="text"
             id="code"
             name="code"
             maxlength="6"
             required
             class="w-full p-2 border mb-4 rounded-lg border-gray-500/50 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-300">

      <button type="submit"
              class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500 mt-2 hover:opacity-90 transition">
        Verificar
      </button>
    </form>
  </div>

  <!-- Flash messages -->
  <?php if (!empty($messages)): ?>
    <div id="flashOverlay" class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
      <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg w-80 mx-4">
        <div class="px-6 py-4 border-b <?= $type === 'error' ? 'border-red-400' : 'border-green-400' ?>">
          <h2 class="text-lg font-semibold text-gray-800">
            <?= $type === 'error' ? '¡Error!' : '¡Genial!' ?>
          </h2>
        </div>
        <div class="px-6 py-4 text-gray-700">
          <ul class="list-disc list-inside space-y-1">
            <?php foreach ($messages as $msg): ?>
              <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="px-6 py-4 flex justify-end space-x-2 border-t">
          <?php if ($type === 'success'): ?>
            <a href="index.php?route=login" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Ir al Login</a>
          <?php else: ?>
            <button id="flashClose" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Cerrar</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <script>
      document.getElementById('flashClose')?.addEventListener('click', () => {
        document.getElementById('flashOverlay').remove();
      });
      document.getElementById('flashOverlay')?.addEventListener('click', e => {
        if (e.target.id === 'flashOverlay') e.currentTarget.remove();
      });
    </script>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>

</body>
</html>
