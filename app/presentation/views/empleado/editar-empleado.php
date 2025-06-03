<?php
// app/presentation/views/empleado/editar-empleado.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recuperamos posibles errores de validación (flash messages)
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

// La variable $empleado (entidad) DEBE ser pasada por el controlador antes de invocar esta vista.
// Por ejemplo: $empleado = $this->repo->obtenerPorId($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Empleado — Encomiendas Leandro</title>
  <!-- TailwindCSS en modo browser -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="/encomiedasLeandro/app/presentation/views/iu/style.css">
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-cyan-400 to-blue-500">

  <div class="bg-white p-8 rounded-lg shadow-md w-sm h-auto pt-10 relative">
    <!-- Botón “×” para volver al listado de empleados activos -->
    <a href="index.php?route=empleado/listar&estado=activos"
       class="absolute top-4 right-4 
              w-10 h-10                        
              flex items-center justify-center 
              bg-gray-200 hover:bg-gray-300    
              rounded-full shadow-md           
              transition">
      <span class="text-3xl leading-none 
                   text-gray-600 hover:text-gray-900" 
            style="transform: translateY(-3px);">
        &times;
      </span>
    </a>

    <h2 class="text-center text-xl font-bold mb-4 pb-4">Editar Empleado</h2>

    <?php if (!empty($errors['messages'])): ?>
      <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
        <?php foreach ($errors['messages'] as $m): ?>
          <p><?= htmlspecialchars($m) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="index.php?route=empleado/editar" method="POST" class="space-y-4">
      <!-- Campo oculto con el ID del empleado -->
      <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($empleado->getId()) ?>">

      <!-- Nombre (solo lectura) -->
      <div>
        <label for="nombre" class="block text-gray-600 mb-1">Nombre Completo</label>
        <input
          type="text"
          id="nombre"
          disabled
          class="w-full p-2 border border-gray-300 bg-gray-100 rounded-lg"
          value="<?= htmlspecialchars($empleado->getNombre()) ?>"
        />
      </div>

      <!-- Correo Electrónico (solo lectura) -->
      <div>
        <label for="correo" class="block text-gray-600 mb-1">Correo Electrónico</label>
        <input
          type="email"
          id="correo"
          disabled
          class="w-full p-2 border border-gray-300 bg-gray-100 rounded-lg"
          value="<?= htmlspecialchars($empleado->getCorreoElectronico()) ?>"
        />
      </div>

      <!-- Teléfono (solo lectura) -->
      <div>
        <label for="telefono" class="block text-gray-600 mb-1">Teléfono</label>
        <input
          type="text"
          id="telefono"
          disabled
          class="w-full p-2 border border-gray-300 bg-gray-100 rounded-lg"
          value="<?= htmlspecialchars($empleado->getTelefono() ?? '') ?>"
        />
      </div>

      <!-- Puesto (editable) -->
      <div>
        <label for="puesto" class="block text-gray-600 mb-1">Puesto</label>
        <input
          type="text"
          name="puesto"
          id="puesto"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-300"
          value="<?= htmlspecialchars($empleado->getPuesto() ?? '') ?>"
        />
      </div>

      <!-- Salario (editable) -->
      <div>
        <label for="salario" class="block text-gray-600 mb-1">Salario</label>
        <input
          type="number"
          step="0.01"
          name="salario"
          id="salario"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-300"
          value="<?= htmlspecialchars(
                     $empleado->getSalario() !== null
                       ? number_format($empleado->getSalario(), 2, '.', '')
                       : ''
                   ) ?>"
        />
      </div>

      <!-- Nueva Contraseña (opcional) -->
      <div>
        <label for="password" class="block text-gray-600 mb-1">Nueva Contraseña (opcional)</label>
        <input
          type="password"
          name="password"
          id="password"
          class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-300"
          placeholder="Déjalo vacío para no cambiar"
        />
      </div>

      <!-- Botón para guardar cambios -->
      <div>
        <button
          type="submit"
          class="w-full text-white p-2 bg-gradient-to-r from-teal-300/70 to-cyan-500 rounded-full hover:opacity-90 transition"
        >
          Guardar Cambios
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <a href="index.php?route=empleado/listar&estado=activos"
         class="text-cyan-600 hover:underline">
        Volver al listado de empleados activos
      </a>
    </div>
  </div>

</body>
</html>
