document.addEventListener('DOMContentLoaded', () => {
  // Si existe el botón de "Cerrar" en el flash modal, lo ocultamos al hacer click
  const flashClose = document.getElementById('flashClose');
  const flashOverlay = document.getElementById('flashOverlay');

  flashClose?.addEventListener('click', () => {
    flashOverlay.remove();
  });

  // Si el usuario hace click fuera del contenido (en el overlay), también lo cerramos
  flashOverlay?.addEventListener('click', (e) => {
    if (e.target.id === 'flashOverlay') {
      flashOverlay.remove();
    }
  });
});
