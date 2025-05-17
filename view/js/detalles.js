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
            document.querySelector('.date').textContent = carrera.fecha ? formatearFecha(carrera.fecha) : 'No disponible';
            document.querySelector('.description').textContent = carrera.descripcion || 'No disponible';

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
                    </div>
                `;
            }

            // Actualizar el patrocinador
            const sponsorElement = document.querySelector('.sponsor');
            if (sponsorElement) {
                sponsorElement.textContent = `Patrocinador: ${carrera.patrocinador || 'No disponible'}`;
            }
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const idCarrera = params.get('id');
    if (idCarrera) {
        cargarDetallesCarrera(idCarrera);
    }

    const participarBtn = document.querySelector('.participate-btn');
    if (participarBtn) {
        participarBtn.addEventListener('click', function() {
            registrarParticipacion(idCarrera);
        });
    }
});

function registrarParticipacion(id_evento) {
    fetch('../controller/action/ajax_participar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id_evento=${encodeURIComponent(id_evento)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.querySelector('.participate-btn').disabled = true;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al intentar registrar la participación');
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