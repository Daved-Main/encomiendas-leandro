    document.addEventListener('DOMContentLoaded', () => {
      const nombreRemitente = document.getElementById('nombre_remitente');
      const nombreDestinatario = document.getElementById('nombre_destinatario');
      const ciudadDestino = document.getElementById('ciudad_destino');
      const direccionDestino = document.getElementById('direccion_destino');
      const tipoPaquete = document.getElementById('tipo_paquete');
      const articulo = document.getElementById('nombre_del_articulo');
      const fragil = document.getElementById('contenido_fragil');

      const resRemitente = document.getElementById('resRemitente');
      const resDestinatario = document.getElementById('resDestinatario');
      const resDestino = document.getElementById('resDestino');
      const resTipo = document.getElementById('resTipo');
      const resArticulo = document.getElementById('resArticulo');
      const resFragil = document.getElementById('resFragil');

      function actualizarResumen() {
        resRemitente.textContent = nombreRemitente.value || '–';
        resDestinatario.textContent = nombreDestinatario.value || '–';
        resDestino.textContent = ciudadDestino.value ? `${ciudadDestino.value}, ${direccionDestino.value}` : '–';
        resTipo.textContent = tipoPaquete.value || '–';
        resArticulo.textContent = articulo.value || '–';
        resFragil.textContent = fragil.checked ? 'Sí' : 'No';
      }

      [nombreRemitente, nombreDestinatario, ciudadDestino, direccionDestino, tipoPaquete, articulo, fragil].forEach(el => {
        el.addEventListener('input', actualizarResumen);
        el.addEventListener('change', actualizarResumen);
      });

      document.getElementById('agendaForm').addEventListener('submit', (e) => {
        alert('✅ Paquete registrado con éxito.');
      });

      actualizarResumen();
    });