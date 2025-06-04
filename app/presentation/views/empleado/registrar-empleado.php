<?php
// app/presentation/views/empleado/registrar-empleado.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors  = $_SESSION['errors']  ?? [];
$success = $_SESSION['success'] ?? [];
unset($_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Empleado — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">
  <div class="bg-white p-8 rounded-lg shadow-md w-sm h-auto pt-10 relative">

    <h2 class="text-center text-xl font-bold mb-4 pb-4">Registrar Nuevo Empleado</h2>

    <?php if (!empty($success['messages'])): ?>
      <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
        <?php foreach ($success['messages'] as $m): ?>
          <p><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors['messages'])): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php foreach ($errors['messages'] as $m): ?>
          <p><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="index.php?route=empleado/registrar" method="POST" class="pl-7 pr-7 space-y-4">
            <a href="index.php?route=empleado/listar&estado=activos"
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
      <label for="nombre" class="text-gray-600/70">Nombre Completo</label>
      <input type="text"
             name="nombre"
             id="nombre"
             required
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <label for="correo_electronico" class="text-gray-600/70">Correo Electrónico</label>
      <input type="email"
             name="correo_electronico"
             id="correo_electronico"
             required
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <label for="telefono" class="text-gray-600/70">Teléfono</label>
      <input type="text"
             name="telefono"
             id="telefono"
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <label for="puesto" class="text-gray-600/70">Puesto</label>
      <input type="text"
             name="puesto"
             id="puesto"
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <label for="salario" class="text-gray-600/70">Salario</label>
      <input type="number"
             step="0.01"
             name="salario"
             id="salario"
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <label for="password" class="text-gray-600/70">Contraseña</label>
      <input type="password"
             name="password"
             id="password"
             required
             class="w-full p-2 border mb-3 rounded-lg border-gray-500/50 bg-gray-400/10">

      <button type="submit"
              class="w-full text-white p-2 rounded-full bg-gradient-to-r from-teal-300/70 to-cyan-500 mt-3 cursor-pointer">
        Guardar Empleado
      </button>

      <p class="text-sm pl-4 pt-4 text-cyan-600 hover:underline">
        <a href="index.php?route=empleado/listar&estado=activos">Volver al listado de empleados activos</a>
      </p>
    </form>
  </div>
</body>
</html>
