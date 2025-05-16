document.addEventListener('DOMContentLoaded', function() {
    cargarEventos();
});

function cargarEventos() {
    fetch('../controller/action/ajax_eventos.php', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        // Verificar estado de la respuesta
        if (!response.ok) {
            const error = await response.json().catch(() => null);
            throw new Error(error?.message || `Error HTTP: ${response.status}`);
        }
        
        // Verificar tipo de contenido
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError("La respuesta no es JSON válido");
        }
        
        return response.json();
    })
    .then(eventos => {
        if (!eventos || !Array.isArray(eventos)) {
            throw new Error("Formato de datos inválido");
        }

        const contenedor = document.querySelector('.contenedor');
        contenedor.innerHTML = '';
        
        eventos.forEach(evento => {
            const fecha = evento.fecha ? new Date(evento.fecha) : new Date();
            const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            const tarjeta = document.createElement('div');
            tarjeta.className = 'card';
            tarjeta.innerHTML = `
                <img src="assets/img/runner5.png" alt="${evento.nombre || 'Evento'}">
                <h3>${evento.nombre || 'Nombre no disponible'}</h3>
                <p>${evento.descripcion || 'Descripción no disponible'}</p>
                <strong>${fechaFormateada}</strong>
                <span>${evento.ubicacion || 'Ubicación no especificada'}</span>
            `;
            
            contenedor.appendChild(tarjeta);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `No se pudieron cargar los eventos.<br><small>${error.message}</small>`,
            confirmButtonText: 'Entendido'
        });
    });

    fetch('../controller/action/ajax_eventos.php')
  .then(r => r.text())
  .then(console.log)
  .catch(console.error);
}