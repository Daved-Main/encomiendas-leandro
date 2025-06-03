<?php

require_once __DIR__ . '/../../helpers/Fechas.php';

$estado = $_GET['estado'] ?? 'activos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Listado de Usuarios — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/encomiedasLeandro/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <?php include __DIR__ . '/../iu/navbar.php'; ?>

  <!-- Contenedor principal -->
  <div class="pt-20 max-w-7xl mx-auto px-4">

    <h1 id="part-3" class="text-2xl font-bold mb-6">Generación de Reportes CSV y PDF</h1>
    <!-- Botones de Exportación CSV / PDF (Usuarios) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <!-- Listado Usuarios -->
      <a href="index.php?route=admin/exportarUsuariosListadoCsv" class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Listado Usuarios (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarUsuariosListadoPdf" class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Listado Usuarios (PDF)
        </button>
      </a>

      <!-- Usuarios Nuevos por Fecha -->
      <a href="index.php?route=admin/exportarUsuariosNuevosCsv" class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Nuevos Usuarios por Fecha (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarUsuariosNuevosPdf" class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Nuevos Usuarios por Fecha (PDF)
        </button>
      </a>

      <!-- Historial de Logins -->
      <a href="index.php?route=admin/exportarUsuariosHistorialLoginsCsv" class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Historial de Logins (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarUsuariosHistorialLoginsPdf" class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Historial de Logins (PDF)
        </button>
      </a>

      <!-- Top Usuarios más Activos -->
      <a href="index.php?route=admin/exportarUsuariosMasActivosCsv" class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Top Usuarios Activos (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarUsuariosMasActivosPdf" class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Top Usuarios Activos (PDF)
        </button>
      </a>
    </div>

    <div class="border-t border-gray-300 mt-4 mb-6"></div>


    <!-- Título + Filtros -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
      <div>
        <h1 class="text-2xl font-semibold">Encomiendas Leandro — Admin</h1>
        <h2 class="text-3xl font-bold">Usuarios</h2>
      </div>

      <!-- Botones de filtro -->
      <div class="flex flex-wrap gap-2">
        <a href="index.php?route=admin/listarUsuarios&estado=activos"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'activos' ? 'bg-blue-800' : 'bg-blue-600 hover:bg-blue-700' ?>">
          Activos
        </a>
        <a href="index.php?route=admin/listarUsuarios&estado=archivados"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'archivados' ? 'bg-blue-800' : 'bg-blue-600 hover:bg-blue-700' ?>">
          Archivados
        </a>
        <a href="index.php?route=admin/listarUsuarios&estado=todos"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'todos' ? 'bg-blue-800' : 'bg-blue-600 hover:bg-blue-700' ?>">
          Todos
        </a>
      </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
      <table class="min-w-full table-fixed">
        <thead class="bg-gray-200">
          <tr>
            <th class="w-1/12 px-4 py-2 text-left">ID</th>
            <th class="w-2/12 px-4 py-2 text-left">Nombre</th>
            <th class="w-3/12 px-4 py-2 text-left">Correo</th>
            <th class="w-2/12 px-4 py-2 text-left">Rol</th>
            <th class="w-2/12 px-4 py-2 text-left">Creado</th>
            <th class="w-2/12 px-4 py-2 text-left">Último Login</th>
            <th class="w-1/12 px-4 py-2 text-left">Archivado</th>
            <th class="w-1/12 px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($usuarios)): ?>
            <tr>
              <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                No hay usuarios para mostrar.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($usuarios as $u): ?>
              <tr class="<?= $u['archived'] ? 'bg-red-50' : 'bg-white' ?> hover:bg-gray-100">
                <td class="px-4 py-2"><?= htmlspecialchars($u['id_user']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($u['nombre']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($u['correo']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($u['rol']) ?></td>
                <td class="px-4 py-2"><?= aHoraElSalvador($u['created_at']) ?></td>
                <td class="px-4 py-2">
                  <?= $u['last_login'] 
                      ? aHoraElSalvador($u['last_login']) 
                      : '-' ?>
                </td>
                <td class="px-4 py-2"><?= $u['archived'] ? 'Sí' : 'No' ?></td>
                <td class="px-4 py-2 text-center">
                  <?php if ($u['archived']): ?>
                    <!-- Solo DESARCHIVAR si está archivado -->
                    <form action="index.php?route=admin/desarchivarUsuario"
                          method="POST"
                          class="inline">
                      <input type="hidden" name="id_user" 
                             value="<?= htmlspecialchars($u['id_user']) ?>">
                      <button type="submit"
                              class="px-2 py-1 bg-green-500 text-white text-sm rounded-md
                                     hover:bg-green-600 transition">
                        Desarchivar
                      </button>
                    </form>
                  <?php else: ?>
                    &mdash;  <!-- Si está activo, no mostrar botón -->
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    

  </div>

</body>
</html>
