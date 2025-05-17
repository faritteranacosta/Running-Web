// detallesCarrera.js
// Script para mostrar los detalles de la carrera seleccionada en detalles.html

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const idCarrera = params.get('id');
    if (idCarrera) {
        cargarDetallesCarrera(idCarrera);
    }
});

function cargarDetallesCarrera(idCarrera) {
    fetch(`../controller/action/ajax_carreras.php?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`)
        .then(r => r.json())
        .then(carrera => {
            if (!carrera) return;
            // Actualizar título y datos principales
            document.getElementById('titulo').textContent = carrera.nombre || 'Carrera';
            document.querySelector('.date').textContent = carrera.fecha ? formatearFecha(carrera.fecha) : '';
            document.querySelector('.description').textContent = carrera.descripcion || '';
            // Actualizar detalles adicionales
            const detalles = document.querySelector('.details');
            if (detalles) {
                detalles.innerHTML = `
                    <div class="event-info">
                        <h3>Fecha:</h3>
                        <p>${carrera.fecha ? formatearFecha(carrera.fecha) : 'No disponible'}</p>
                        <h3>Hora:</h3>
                        <p>${carrera.hora || 'No disponible'}</p>
                        <h3>Distancia:</h3>
                        <p>${carrera.distancia || 'No disponible'} km</p>
                        <h3>Categoría:</h3>
                        <p>${carrera.categoria || 'No disponible'}</p>
                        <h3>Punto de encuentro:</h3>
                        <p>${carrera.punto_encuentro || 'No disponible'}</p>
                    </div>
                `;
            }
        });
}

function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
