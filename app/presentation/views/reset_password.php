<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors  = $_SESSION['errors']   ?? [];
$success = $_SESSION['success']  ?? []; 
unset($_SESSION['errors'], $_SESSION['success']);

$email = $_SESSION['recovery_email'] ?? '';

$showSuccessModal = isset($_GET['success']) && $_GET['success'] == '1';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restablecer Contraseña</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">

  <div class="relative bg-white p-8 rounded-lg shadow-md w-sm h-auto pt-10">
    <a href="index.php?route=login"
       class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-3xl leading-none 
              bg-gray-200 hover:bg-gray-300 p-1 rounded-full shadow-md transition">
      &times;
    </a>

    <h2 class="text-center text-xl font-bold mb-4 pb-4">
      Ingresa el Token y Nueva Contraseña
    </h2>

    <?php if (!empty($errors['messages'])): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul class="list-disc list-inside">
          <?php foreach ($errors['messages'] as $m): ?>
            <li><?= htmlspecialchars($m) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="index.php?route=reset_password" method="POST" class="px-4">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

      <label for="token" class="text-gray-600/70">Token (revisa tu correo)</label>
      <input type="text"
             name="token"
             id="token"
             maxlength="12"
             required
             placeholder="Escribe el token que recibiste"
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10
                    focus:outline-none focus:ring-2 focus:ring-cyan-300">

      <label for="new_password" class="text-gray-600/70">Nueva Contraseña</label>
      <input type="password"
             name="new_password"
             id="new_password"
             required
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10
                    focus:outline-none focus:ring-2 focus:ring-cyan-300">

      <label for="confirm_password" class="text-gray-600/70">Confirmar Contraseña</label>
      <input type="password"
             name="confirm_password"
             id="confirm_password"
             required
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10
                    focus:outline-none focus:ring-2 focus:ring-cyan-300">

      <button type="submit"
              class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500
                     mt-3 hover:opacity-90 transition">
        Restablecer Contraseña
      </button>
    </form>
  </div>

  <?php if ($showSuccessModal): ?>
    <div id="successOverlay" class="fixed inset-0 bg-black/20 flex items-center justify-center z-50">
      <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg w-80 mx-4">
        <div class="px-6 py-4 border-b border-green-400">
          <h2 class="text-lg font-semibold text-gray-800">¡Éxito!</h2>
        </div>
        <div class="px-6 py-4 text-gray-700">
          <p>Tu contraseña se ha restablecido correctamente.</p>
        </div>
        <div class="px-6 py-4 flex justify-end space-x-2 border-t">
          <a href="index.php?route=login"
             class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
            Ir al Login
          </a>
        </div>
      </div>
    </div>
    <script>
      document.getElementById('successOverlay').addEventListener('click', e => {
        if (e.target.id === 'successOverlay') {
          e.currentTarget.remove();
        }
      });
    </script>
  <?php endif; ?>

</body>
</html>
