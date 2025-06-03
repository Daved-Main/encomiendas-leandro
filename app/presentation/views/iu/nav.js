// app/presentation/views/iu/nav.js

document.addEventListener('DOMContentLoaded', function() {
  // Toggle del menú móvil (hamburguesa)
  const btn       = document.getElementById('btn-mobile-menu');
  const menu      = document.getElementById('mobile-menu');
  const iconMenu  = document.getElementById('icon-menu');
  const iconClose = document.getElementById('icon-close');

  if (btn && menu && iconMenu && iconClose) {
    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      iconMenu.classList.toggle('hidden');
      iconClose.classList.toggle('hidden');
    });
  }

  // Toggle submenu Admin
  const adminBtn  = document.getElementById('admin-menu-button');
  const adminMenu = document.getElementById('admin-menu');
  adminBtn?.addEventListener('click', () => {
    if (adminMenu) adminMenu.classList.toggle('hidden');
  });

  // Toggle submenu Empleado
  const empBtn  = document.getElementById('empleado-menu-button');
  const empMenu = document.getElementById('empleado-menu');
  empBtn?.addEventListener('click', () => {
    if (empMenu) empMenu.classList.toggle('hidden');
  });

  // Toggle submenu Usuario
  const userBtn  = document.getElementById('user-menu-button');
  const userMenu = document.getElementById('user-menu');
  userBtn?.addEventListener('click', () => {
    if (userMenu) userMenu.classList.toggle('hidden');
  });
});
