<?php
// app/presentation/views/iu/navbar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ¿Usuario logueado? ¿Rol?
$isLoggedIn   = isset($_SESSION['user']) && !empty($_SESSION['role']);
$isAdmin      = $isLoggedIn && ($_SESSION['role'] === 'admin');
$isEmpleado   = $isLoggedIn && ($_SESSION['role'] === 'empleado');
$isUsuario    = $isLoggedIn && ($_SESSION['role'] === 'usuario');

$nombreUsuario  = $isUsuario  ? ($_SESSION['user']['nombre'] ?? '') : '';
$nombreEmpleado = $isEmpleado ? ($_SESSION['empleado_nombre'] ?? ($_SESSION['user']['nombre'] ?? '')) : '';
$nombreAdmin    = $isAdmin    ? ($_SESSION['user']['nombre'] ?? '') : '';
?>
<nav class="sticky top-0 z-50 bg-blue-500 text-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-wrap justify-between items-center h-16">


      <div class="flex-shrink-0">
        <a href="index.php?route=home" class="flex items-center space-x-2 hover:text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M3 14h18M3 6h18M3 18h18" />
          </svg>
          <span class="text-xl font-semibold">Encomiendas Leandro</span>
        </a>
      </div>


      <div class="hidden xl:flex xl:space-x-6">
        <a href="index.php?route=home"
           class="px-3 py-2 rounded-md hover:bg-blue-600 transition">
          Inicio
        </a>
        <a href="index.php?route=agendaPaquete"
           class="px-3 py-2 rounded-md hover:bg-blue-600 transition">
          Enviar Paquete
        </a>
        <a href="index.php?route=proximosViajes"
           class="px-3 py-2 rounded-md hover:bg-blue-600 transition">
          Próximos Viajes
        </a>
        <a href="index.php?route=seguimientoPaquete"
           class="px-3 py-2 rounded-md hover:bg-blue-600 transition">
          Seguimiento
        </a>
      </div>


      <div class="hidden xl:flex xl:items-center xl:space-x-4">
        <?php if (! $isLoggedIn): ?>
          <!-- Nadie logueado: Iniciar Sesión / Registrarse -->
          <a href="index.php?route=login"
             class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-md transition">
            Iniciar Sesión
          </a>
          <a href="index.php?route=registrar"
             class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 rounded-md transition">
            Registrarse
          </a>

        <?php elseif ($isAdmin): ?>
          <!-- ADMIN logueado -->
          <div class="relative">
            <button id="admin-menu-button"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md flex items-center focus:outline-none">
              <?= htmlspecialchars($nombreAdmin) ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-white" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <ul id="admin-menu" class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg hidden">
              <!-- Opciones de Admin -->
              <li><a href="index.php?route=listar_paquetes" class="block px-4 py-2 hover:bg-gray-100">Paquetes Recibidos</a></li>
              <li><a href="index.php?route=historialPaquetes"     class="block px-4 py-2 hover:bg-gray-100">Ver Paquetes</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=admin/listarUsuarios&estado=activos" class="block px-4 py-2 hover:bg-gray-100">Listar Usuarios</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=empleado/listar&estado=activos"       class="block px-4 py-2 hover:bg-gray-100">Listar Empleados</a></li>
              <li><a href="index.php?route=empleado/registrar"                   class="block px-4 py-2 hover:bg-gray-100">Registrar Empleado</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=admin/listarViajeProximo"             class="block px-4 py-2 hover:bg-gray-100">Ver Próximos Viajes</a></li>
              <li><a href="index.php?route=admin/nuevoViajeProximo"               class="block px-4 py-2 hover:bg-gray-100">Agendar Viaje</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Cerrar Sesión</a></li>
            </ul>
          </div>

        <?php elseif ($isEmpleado): ?>
          <!-- EMPLEADO logueado -->
          <div class="relative">
            <button id="empleado-menu-button"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md flex items-center focus:outline-none">
              <?= htmlspecialchars($nombreEmpleado) ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-white" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <ul id="empleado-menu" class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg hidden">
              <li><a href="index.php?route=empleado/dashboard"                class="block px-4 py-2 hover:bg-gray-100">Mi Dashboard</a></li>
              <li><a href="index.php?route=admin/listarUsuarios&estado=activos"class="block px-4 py-2 hover:bg-gray-100">Listar Usuarios</a></li>
              <li><a href="index.php?route=admin/listarViajeProximo"          class="block px-4 py-2 hover:bg-gray-100">Ver Próximos Viajes</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=empleado/logout"                    class="block px-4 py-2 text-red-600 hover:bg-gray-100">Cerrar Sesión</a></li>
            </ul>
          </div>

        <?php elseif ($isUsuario): ?>
          <!-- USUARIO normal logueado -->
          <div class="relative">
            <button id="user-menu-button"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md flex items-center focus:outline-none">
              <?= htmlspecialchars($nombreUsuario) ?>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-white" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <ul id="user-menu" class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg hidden">
              <li><a href="index.php?route=historialPaquetes"     class="block px-4 py-2 hover:bg-gray-100">Ver Paquetes</a></li>
              <li><a href="index.php?route=historialEnvios" class="block px-4 py-2 hover:bg-gray-100">Historial de Envíos</a></li>
              <li><a href="index.php?route=proximosViajes"  class="block px-4 py-2 hover:bg-gray-100">Próximos Viajes</a></li>
              <li class="border-t my-1"></li>
              <li><a href="index.php?route=logout"           class="block px-4 py-2 text-red-600 hover:bg-gray-100">Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <!-- ====================== -->
      <!--   BOTÓN HAMBURGUESA    -->
      <!--   (solo en móviles <1280px) -->
      <!-- ====================== -->
      <div class="xl:hidden flex items-center">
        <button id="btn-mobile-menu" class="focus:outline-none p-2 rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" id="icon-menu" class="h-6 w-6 text-white" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" id="icon-close" class="h-6 w-6 text-white hidden" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- ========================= -->
  <!--   MENÚ MÓVIL colapsable   -->
  <!--   (solo en móviles <1280px) -->
  <!-- ========================= -->
  <div id="mobile-menu" class="xl:hidden bg-blue-600 text-white px-4 pb-4 space-y-1 hidden">
    <a href="index.php?route=home" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Inicio</a>
    <a href="index.php?route=agendaPaquete" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Enviar Paquete</a>
    <a href="index.php?route=proximosViajes" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Próximos Viajes</a>
    <a href="index.php?route=seguimientoPaquete" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Seguimiento</a>
    <div class="border-t border-blue-500 my-2"></div>

    <?php if (! $isLoggedIn): ?>
      <a href="index.php?route=login"     class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Iniciar Sessión</a>
      <a href="index.php?route=registrar" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Registrarse</a>

    <?php elseif ($isAdmin): ?>
      <a href="index.php?route=listar_paquetes" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Paquetes Recibidos</a>
      <a href="index.php?route=historialPaquetes" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Ver Paquetes</a>
      <hr class="border-blue-500">
      <a href="index.php?route=admin/listarUsuarios&estado=activos" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Listar Usuarios</a>
      <hr class="border-blue-500">
      <a href="index.php?route=empleado/listar&estado=activos" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Listar Empleados</a>
      <a href="index.php?route=empleado/registrar"   class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Registrar Empleado</a>
      <hr class="border-blue-500">
      <a href="index.php?route=admin/listarViajeProximo" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Ver Próximos Viajes</a>
      <a href="index.php?route=admin/nuevoViajeProximo"   class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Agendar Viaje</a>
      <hr class="border-blue-500">
      <a href="index.php?route=logout" class="block px-3 py-2 bg-red-500 hover:bg-red-600 rounded-md text-center transition">Cerrar Sesión</a>

    <?php elseif ($isEmpleado): ?>
      <a href="index.php?route=empleado/dashboard" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Mi Dashboard</a>
      <a href="index.php?route=admin/listarUsuarios&estado=activos" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Listar Usuarios</a>
      <a href="index.php?route=admin/listarViajeProximo" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Ver Próximos Viajes</a>
      <a href="index.php?route=empleado/logout" class="block px-3 py-2 bg-red-500 hover:bg-red-600 rounded-md text-center transition">Cerrar Sesión</a>

    <?php elseif ($isUsuario): ?>
      <a href="index.php?route=historialPaquetes" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Ver Paquetes</a>
      <a href="index.php?route=historialEnvios" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Historial Envíos</a>
      <a href="index.php?route=proximosViajes" class="block px-3 py-2 rounded-md hover:bg-blue-700 transition">Próximos Viajes</a>
      <a href="index.php?route=logout"          class="block px-3 py-2 bg-red-500 hover:bg-red-600 rounded-md text-center transition">Cerrar Sesión</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Incluir siempre nav.js al final -->
<script src="app/presentation/views/iu/nav.js" defer></script>
