document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID de la carrera desde la URL
    const urlParams = new URLSearchParams(window.location.search);
    const idCarrera = urlParams.get('id');
    
    if (idCarrera) {
        console.log("Cargando detalles de la carrera ID:", idCarrera);
        cargarDetallesCarrera(idCarrera);
    } else {
        console.error("No se encontró un ID de carrera en la URL");
        document.getElementById('titulo').textContent = "Error: No se especificó una carrera";
    }
});

function cargarDetallesCarrera(idCarrera) {
    // Llamar a la API para obtener los detalles de la carrera
    fetch(`../controller/action/ajax_carreras.php?action=obtener&idCarrera=${idCarrera}`)
        .then(response => response.json())
        .then(carrera => {
            console.log("Datos de carrera recibidos:", carrera);
            
            if (!carrera) {
                console.error("No se encontraron datos para esta carrera");
                return;
            }
            
            // Actualizar la tarjeta principal (event-card)
            const titulo = document.getElementById('titulo');
            if (titulo) titulo.textContent = carrera.nombre || 'Carrera';

            const status = document.querySelector('.event-card .status');
            if (status) status.textContent = carrera.estado || 'Próximo';

            const date = document.querySelector('.event-card .date');
            if (date) date.textContent = carrera.fecha ? formatearFecha(carrera.fecha) : 'Fecha no disponible';

            const descripcion = document.querySelector('.event-card .description');
            if (descripcion) descripcion.textContent = carrera.descripcion || 'Sin descripción disponible';

            // Actualizar la tarjeta de detalles (card)
            const detallesInfo = document.querySelector('.card .event-info');
            if (detallesInfo) {
                detallesInfo.innerHTML = `
                    <h3>Fecha:</h3>
                    <p>${carrera.fecha ? formatearFecha(carrera.fecha) : 'No disponible'}</p>

                    <h3>Hora:</h3>
                    <p>${carrera.hora || 'No disponible'}</p>

                    <h3>Distancia:</h3>
                    <p>${carrera.distancia ? carrera.distancia + ' km' : 'No disponible'}</p>

                    <h3>Categoría:</h3>
                    <p>${carrera.categoria || 'No disponible'}</p>

                    <h3>Punto de encuentro:</h3>
                    <p>${carrera.punto_encuentro || 'No disponible'}</p>
                `;
            }

            // Patrocinador y equipo (si existen en la respuesta)
            const sponsor = document.querySelector('.card .sponsor');
            if (sponsor) sponsor.textContent = carrera.patrocinador || 'Patrocinador';
            const team = document.querySelector('.card .team');
            if (team) team.textContent = carrera.equipo || 'Chibolo Team';

            // Actualizar título de la página
            document.title = `${carrera.nombre || 'Carrera'} - Detalles`;
        })
        .catch(error => {
            console.error("Error al cargar los detalles de la carrera:", error);
            const titulo = document.getElementById('titulo');
            if (titulo) titulo.textContent = "Error al cargar los detalles";
        });
}

// Función para formatear fechas
function formatearFecha(fechaStr) {
    try {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    } catch (e) {
        console.error("Error al formatear fecha:", e);
        return fechaStr; // Devolver la fecha original si hay error
    }
}