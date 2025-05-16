// carreras.js
// Funciones AJAX para gestionar carreras

// Cambiado a ruta relativa para que funcione desde view/
const API_URL = '../controller/action/ajax_carreras.php';

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
    cargarCarreras();
});

function cargarCarreras() {
    fetch('../controller/action/ajax_carreras.php?action=listar', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        if (!response.ok) {
            const error = await response.json().catch(() => null);
            throw new Error(error?.message || `Error HTTP: ${response.status}`);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError('La respuesta no es JSON válido');
        }
        return response.json();
    })
    .then(carreras => {
        if (!carreras || !Array.isArray(carreras)) {
            throw new Error('Formato de datos inválido');
        }
        const contenedor = document.querySelector('.contenedor');
        contenedor.innerHTML = '';
        carreras.forEach(carrera => {
            const fecha = carrera.fecha ? new Date(carrera.fecha) : null;
            const fechaFormateada = fecha ? fecha.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'No disponible';
            const tarjeta = document.createElement('div');
            tarjeta.className = 'card';
            tarjeta.innerHTML = `
                <img src="assets/img/runner5.png" alt="${carrera.nombre || 'Carrera'}">
                <h3>${carrera.nombre || 'Nombre no disponible'}</h3>
                <p>${carrera.descripcion || 'Descripción no disponible'}</p>
                <strong>${fechaFormateada}</strong>
                <p><strong>Categoría:</strong> ${carrera.categoria || 'No disponible'}</p>
                <a href="detalles.html?id=${carrera.idCarrera || ''}" class="enlace_boton">
                    <button class="details-btn">Participar</button>
                </a>
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
