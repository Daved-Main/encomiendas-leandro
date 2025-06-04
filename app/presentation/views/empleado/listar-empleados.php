<?php

require_once __DIR__ . '/../../helpers/Fechas.php';

$estado = $_GET['estado'] ?? 'activos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Listado de Empleados — Encomiendas Leandro</title>
  <link rel="stylesheet" href="/app/presentation/views/iu/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <?php include __DIR__ . '/../iu/navbar.php'; ?>

  <!-- Contenedor principal -->
  <div class="pt-20 max-w-7xl mx-auto px-4">

        <h1 id="part-3" class="text-2xl font-bold mb-6">Generación de Reportes CSV y PDF</h1>

  <!-- Botones de Exportación CSV / PDF -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <!-- Listado Empleados -->
      <a href="index.php?route=admin/exportarEmpleadosListadoCsv"
         class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Listado Empleados (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarEmpleadosListadoPdf"
         class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Listado Empleados (PDF)
        </button>
      </a>

      <!-- Antigüedad Empleados -->
      <a href="index.php?route=admin/exportarEmpleadosAntiguedadCsv"
         class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Antigüedad Empleados (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarEmpleadosAntiguedadPdf"
         class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Antigüedad Empleados (PDF)
        </button>
      </a>

      <!-- Salarios por Puesto -->
      <a href="index.php?route=admin/exportarEmpleadosSalariosCsv"
         class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Salarios por Puesto (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarEmpleadosSalariosPdf"
         class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Salarios por Puesto (PDF)
        </button>
      </a>

      <!-- Próximos Aniversarios -->
      <a href="index.php?route=admin/exportarEmpleadosAniversariosCsv"
         class="block">
        <button
          class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition">
          📄 Próximos Aniversarios (CSV)
        </button>
      </a>
      <a href="index.php?route=admin/exportarEmpleadosAniversariosPdf"
         class="block">
        <button
          class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition">
          📑 Próximos Aniversarios (PDF)
        </button>
      </a>
    </div>
        <!-- ==================== Separador antes de los botones de exportación ==================== -->
    <div class="border-t border-gray-300 mt-8 mb-6"></div>

    <!-- Título + Filtros -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
      <h2 class="text-3xl font-bold">Empleados</h2>

      <!-- Botones de filtro -->
      <div class="flex flex-wrap gap-2">
        <a href="index.php?route=empleado/listar&estado=activos"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'activos'
                  ? 'bg-blue-800'
                  : 'bg-blue-600 hover:bg-blue-700' ?>">
          Activos
        </a>
        <a href="index.php?route=empleado/listar&estado=archivados"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'archivados'
                  ? 'bg-blue-800'
                  : 'bg-blue-600 hover:bg-blue-700' ?>">
          Archivados
        </a>
        <a href="index.php?route=empleado/listar&estado=todos"
           class="px-4 py-2 rounded-md text-white font-medium transition 
             <?= $estado === 'todos'
                  ? 'bg-blue-800'
                  : 'bg-blue-600 hover:bg-blue-700' ?>">
          Todos
        </a>
      </div>
    </div>


  
    <!-- Tabla de empleados -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full table-fixed">
        <thead class="bg-gray-200">
          <tr>
            <th class="w-1/12 px-4 py-2 text-left">ID</th>
            <th class="w-2/12 px-4 py-2 text-left">Nombre</th>
            <th class="w-2/12 px-4 py-2 text-left">Correo</th>
            <th class="w-1/12 px-4 py-2 text-left">Teléfono</th>
            <th class="w-1/12 px-4 py-2 text-left">Puesto</th>
            <th class="w-1/12 px-4 py-2 text-left">Salario</th>
            <th class="w-2/12 px-4 py-2 text-left">Contratado</th>
            <th class="w-1/12 px-4 py-2 text-left">Archivado</th>
            <th class="w-1/12 px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($empleados)): ?>
            <tr>
              <td colspan="9" class="px-4 py-3 text-center text-gray-500">
                No hay empleados para mostrar.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($empleados as $e): ?>
              <tr class="<?= $e['archived'] ? 'bg-red-50' : 'bg-white' ?> hover:bg-gray-100">
                <td class="px-4 py-2"><?= htmlspecialchars($e['id_empleado']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($e['nombre']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($e['correo_electronico']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($e['telefono'] ?? '—') ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($e['puesto'] ?? '—') ?></td>
                <td class="px-4 py-2">$<?= number_format($e['salario'], 2) ?></td>
                <td class="px-4 py-2"><?= aHoraElSalvador($e['fecha_contratacion']) ?></td>
                <td class="px-4 py-2"><?= $e['archived'] ? 'Sí' : 'No' ?></td>
                <td class="px-4 py-2 text-center">
                  <?php if (! $e['archived']): ?>
                    <div class="inline-flex space-x-2">
                      <!-- Botón Archivar -->
                      <form action="index.php?route=empleado/desactivar" method="POST">
                        <input type="hidden" name="id_empleado"
                               value="<?= htmlspecialchars($e['id_empleado']) ?>">
                        <button type="submit"
                                class="px-2 py-1 bg-red-500 text-white text-sm rounded-md
                                       hover:bg-red-600 transition">
                          Archivar
                        </button>
                      </form>
                      <!-- Botón Editar -->
                      <a href="index.php?route=empleado/editar&id_empleado=<?= htmlspecialchars($e['id_empleado']) ?>"
                         class="px-2 py-1 bg-yellow-500 text-white text-sm rounded-md
                                hover:bg-yellow-600 transition">
                        Editar
                      </a>
                    </div>
                  <?php else: ?>
                    <div class="inline-flex">
                      <!-- Botón Desarchivar -->
                      <form action="index.php?route=empleado/activar" method="POST">
                        <input type="hidden" name="id_empleado"
                               value="<?= htmlspecialchars($e['id_empleado']) ?>">
                        <button type="submit"
                                class="px-2 py-1 bg-green-500 text-white text-sm rounded-md
                                       hover:bg-green-600 transition">
                          Desarchivar
                        </button>
                      </form>
                    </div>
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
