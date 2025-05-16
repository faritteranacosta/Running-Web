// carreras.js
// Funciones AJAX para gestionar carreras

const API_URL = '/controller/action/ajax_carreras.php';

export function insertarCarrera(data) {
    return fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'insertar',
            ...data
        })
    }).then(r => r.json());
}

export function actualizarCarrera(data) {
    return fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'actualizar',
            ...data
        })
    }).then(r => r.json());
}

export function eliminarCarrera(idCarrera) {
    return fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'eliminar',
            idCarrera
        })
    }).then(r => r.json());
}

export function obtenerCarreraPorId(idCarrera) {
    return fetch(`${API_URL}?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`)
        .then(r => r.json());
}

export function listarCarreras() {
    return fetch(`${API_URL}?action=listar`)
        .then(r => r.json());
}

// Renderizado dinámico de carreras en carreras.html

document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.contenedor') && document.getElementById('carrera-destacada')) {
        cargarCarreras();
    }
});

function cargarCarreras() {
    listarCarreras()
        .then(carreras => {
            if (!carreras || !Array.isArray(carreras)) {
                throw new Error('Formato de datos inválido');
            }
            const contenedor = document.querySelector('.contenedor');
            contenedor.innerHTML = '';
            if (carreras.length > 0) {
                renderCarreraDestacada(carreras[0]);
            }
            carreras.forEach(carrera => {
                const tarjeta = document.createElement('div');
                tarjeta.className = 'card';
                tarjeta.innerHTML = `
                    <img src="assets/img/runner5.png" alt="Carrera">
                    <h3>${carrera.nombre || 'Nombre no disponible'}</h3>
                    <p>${carrera.descripcion || 'Descripción no disponible'}</p>
                    <strong>${carrera.fecha ? formatearFecha(carrera.fecha) : ''}</strong>
                `;
                contenedor.appendChild(tarjeta);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `No se pudieron cargar las carreras.<br><small>${error.message}</small>`,
                    confirmButtonText: 'Entendido'
                });
            }
        });
}

function renderCarreraDestacada(carrera) {
    const destacada = document.getElementById('carrera-destacada');
    if (!destacada) return;
    destacada.innerHTML = `
        <img src="assets/img/runner3.png" alt="Corredores en una carrera">
        <div class="event-info">
            <h2 id="titulo">${carrera.nombre || 'Carrera Destacada'}</h2>
            <p class="status">Próximo</p>
            <strong class="date">${carrera.fecha ? formatearFecha(carrera.fecha) : ''}</strong>
            <p class="description">${carrera.descripcion || ''}</p>
            <a href="detalles.html?id=${carrera.idCarrera || ''}" id="enlace_boton" >
                <button class="details-btn" i="boton">Participar</button>
            </a>
        </div>
    `;
}

function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Ejemplo de uso:
// insertarCarrera({distancia: 10, evento: 1, tipoCarrera: 2, categoria: 3, ruta: 4}).then(console.log);
// actualizarCarrera({idCarrera: 1, distancia: 10, evento: 1, tipoCarrera: 2, categoria: 3, ruta: 4}).then(console.log);
// eliminarCarrera(1).then(console.log);
// obtenerCarreraPorId(1).then(console.log);
// listarCarreras().then(console.log);
