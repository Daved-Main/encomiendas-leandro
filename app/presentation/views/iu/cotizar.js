document.addEventListener('DOMContentLoaded', () => {
      // Referencias a inputs
      const nombreRemi     = document.getElementById('nombre-remi');
      const nombreDesti    = document.getElementById('nombre-desti');
      const ciudadDesti    = document.getElementById('ciudad-desti');
      const direccionDesti = document.getElementById('direccion-desti');
      const tipoPaquete    = document.getElementById('tipo-paquete');
      const articulo       = document.getElementById('articulo');
      const peso           = document.getElementById('peso');
      const fragil         = document.getElementById('fragil');

      // Referencias a resumen
      const resRemitente    = document.getElementById('resRemitente');
      const resDestinatario = document.getElementById('resDestinatario');
      const resDestino      = document.getElementById('resDestino');
      const resTipo         = document.getElementById('resTipo');
      const resArticulo     = document.getElementById('resArticulo');
      const resFragil       = document.getElementById('resFragil');
      const resCosto        = document.getElementById('resCosto');

      // Modal y elementos de cotización
      const btnCalcular     = document.getElementById('btnCalcular');
      const modalCotizacion = document.getElementById('modalCotizacion');
      const btnCerrarModal  = document.getElementById('btnCerrarModal');
      const textoCosto      = document.getElementById('textoCosto');

      // Función para actualizar el panel de resumen en tiempo real
      function actualizarResumen() {
        resRemitente.textContent    = nombreRemi.value || '–';
        resDestinatario.textContent = nombreDesti.value || '–';
        resDestino.textContent      = ciudadDesti.value
                                     ? `${ciudadDesti.value}, ${direccionDesti.value}`
                                     : '–';
        resTipo.textContent         = tipoPaquete.value || '–';
        resArticulo.textContent     = articulo.value || '–';
        resFragil.textContent       = fragil.checked ? 'Sí' : 'No';
        resCosto.textContent        = peso.value
                                     ? `$${(parseFloat(peso.value) * 7).toFixed(2)} USD (ref.)`
                                     : '–';
      }

      // Asignar listeners para mantener el resumen actualizado
      [ nombreRemi, nombreDesti, ciudadDesti, direccionDesti,
        tipoPaquete, articulo, peso, fragil
      ].forEach(el => {
        el.addEventListener('input', actualizarResumen);
        el.addEventListener('change', actualizarResumen);
      });

      // Al hacer clic en “Calcular Costo” abre el modal
      btnCalcular.addEventListener('click', () => {
        const pesoValor = parseFloat(peso.value);
        if (isNaN(pesoValor) || pesoValor <= 0) {
          alert('Por favor, ingresa un peso válido en libras.');
          return;
        }
        const costo = (pesoValor * 7).toFixed(2);
        textoCosto.textContent = `$${costo} USD`;
        modalCotizacion.classList.remove('hidden');
      });

      // Cerrar modal al hacer clic en “Cerrar”
      btnCerrarModal.addEventListener('click', () => {
        modalCotizacion.classList.add('hidden');
      });

      // Cerrar modal si se hace clic fuera del cuadro blanco
      modalCotizacion.addEventListener('click', (e) => {
        if (e.target === modalCotizacion) {
          modalCotizacion.classList.add('hidden');
        }
      });

      // Inicializar resumen al cargar la página
      actualizarResumen();
    });