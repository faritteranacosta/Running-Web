let paginaActualEventos = 1;
const eventosPorPagina = 20;
let modoEdicionEvento = false; // Declárala aquí
let idEventoAEditar = null;   // Declárala aquí

// Función para obtener eventos con paginación
async function obtenerEventos(pagina = 1, porPagina = 20) {
    try {
        const response = await fetch(`../controller/action/ajax_eventos.php?pagina=${pagina}&porPagina=${porPagina}`);
        if (!response.ok) {
            throw new Error('Error al obtener eventos');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
        return { eventos: [], total: 0 };
    }
}

// Función para renderizar la tabla de eventos
function renderizarTablaEventos(eventos, total) {
    const container = document.getElementById('eventos-table-container');
    if (!container) return;

    if (!eventos || eventos.length === 0) {
        container.innerHTML = '<p class="text-gray-500">No hay eventos registrados.</p>';
        return;
    }

    let rows = eventos.map(evento => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium">${evento.nombre}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${evento.tipo || 'Carrera'}</td>
            <td class="px-6 py-4 whitespace-nowrap">${formatearFecha(evento.fecha)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm">
                    ${evento.direccion || 'No especificada'}<br>
                    <span class="text-gray-500">${evento.ciudad || ''}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button onclick="editarEvento(${evento.id})" class="text-blue-500 hover:text-blue-700 mr-2">
                    <i class="fas fa-edit"></i> Editar
                </button>
                <button onclick="eliminarEvento(${evento.id})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </td>
        </tr>
    `).join('');

    const totalPaginas = Math.ceil(total / eventosPorPagina);

    let paginacion = '';
    if (totalPaginas > 1) {
        paginacion = `
            <nav class="flex justify-center mt-6" aria-label="Paginación">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <button ${paginaActualEventos === 1 ? 'disabled' : ''} onclick="cambiarPaginaEventos(${paginaActualEventos - 1})"
                            class="px-3 py-1 rounded-l border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActualEventos === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                            Anterior
                        </button>
                    </li>
                    <li>
                        <span class="px-3 py-1 border-t border-b border-gray-300 bg-white text-gray-700">
                            Página ${paginaActualEventos} de ${totalPaginas}
                        </span>
                    </li>
                    <li>
                        <button ${paginaActualEventos === totalPaginas ? 'disabled' : ''} onclick="cambiarPaginaEventos(${paginaActualEventos + 1})"
                            class="px-3 py-1 rounded-r border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActualEventos === totalPaginas ? 'opacity-50 cursor-not-allowed' : ''}">
                            Siguiente
                        </button>
                    </li>
                </ul>
            </nav>
        `;
    }

    container.innerHTML = `
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        ${rows}
                    </tbody>
                </table>
            </div>
        </div>
        ${paginacion}
    `;
}

// Función para mostrar eventos
async function mostrarEventos() {
    const data = await obtenerEventos(paginaActualEventos, eventosPorPagina);
    renderizarTablaEventos(data.eventos || data, data.total || (data.length || 0));
}

// Función para cambiar página
window.cambiarPaginaEventos = function(nuevaPagina) {
    paginaActualEventos = nuevaPagina;
    mostrarEventos();
};

// Función para formatear fecha
function formatearFecha(fecha) {
    if (!fecha) return 'No especificada';
    const date = new Date(fecha);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Función para crear evento
async function crearEvento(datosEvento) {
    try {
        const response = await fetch('../controller/action/ajax_eventosAdmin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'agregar',
                nombre: datosEvento.nombre,
                tipo: datosEvento.tipo,
                fecha: datosEvento.fecha, // Usar directamente
                hora: datosEvento.hora,   // Usar directamente
                descripcion: datosEvento.descripcion,
                id_patrocinador: datosEvento.id_patrocinador,
                ubicacion_id: datosEvento.ubicacion_id
            })
        });

        // Verifica primero el tipo de contenido
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error(`Respuesta no JSON: ${text.substring(0, 100)}...`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error en crearEvento:', error);
        return { 
            success: false, 
            error: error.message || 'Error de conexión' 
        };
    }
}

function esNumeroValido(valor) {
    return !isNaN(valor) && 
           valor !== null && 
           valor !== "" && 
           !isNaN(parseFloat(valor));
}

// Función para manejar el formulario de evento
window.manejarFormularioEvento = async function(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    try {
        const datosEvento = {
            nombre: formData.get('nombre'),
            tipo: formData.get('tipo'),
            fecha: formData.get('fecha'),
            hora: formData.get('hora'),
            descripcion: formData.get('descripcion'),
            ubicacion_id: formData.get('ubicacion_id'),
            id_patrocinador: formData.get('id_patrocinador')
        };

        console.log("Datos enviados desde el formulario:", datosEvento); // <-- AÑADE ESTA LÍNEA

        let resultado;
        if (modoEdicionEvento) {
            datosEvento.id_evento = idEventoAEditar;
            resultado = await actualizarEvento(datosEvento);
        } else {
            resultado = await crearEvento(datosEvento);
        }
    } catch (error) {
        console.error('Error en manejarFormularioEvento:', error);
        mostrarAlerta('Error al procesar la solicitud', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Evento';
    }
};

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'info') {
    const alertaExistente = document.querySelector('.alerta-temporal');
    if (alertaExistente) {
        alertaExistente.remove();
    }
    
    const colores = {
        success: 'bg-green-100 text-green-800 border-green-200',
        error: 'bg-red-100 text-red-800 border-red-200',
        info: 'bg-blue-100 text-blue-800 border-blue-200'
    };
    
    const iconos = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    };
    
    const alerta = document.createElement('div');
    alerta.className = `alerta-temporal fixed top-4 right-4 z-50 px-4 py-3 border rounded-lg ${colores[tipo]} shadow-lg`;
    alerta.innerHTML = `
        <div class="flex items-center">
            <i class="${iconos[tipo]} mr-2"></i>
            <span>${mensaje}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alerta);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}

async function obtenerEventoPorId(id) {
    try {
        const response = await fetch(`../controller/action/ajax_eventosAdmin.php?action=obtenerPorId&id=${id}`);
        if (!response.ok) {
            throw new Error('Error al obtener los detalles del evento');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error en obtenerEventoPorId:', error);
        mostrarAlerta('Error al cargar datos del evento', 'error');
        return { success: false, error: error.message };
    }
}


window.editarEvento = async function(idEvento) { //
    mostrarAlerta('Cargando datos del evento...', 'info'); //
    const data = await obtenerEventoPorId(idEvento);

    if (data.success && data.evento) {
        const evento = data.evento;
        // Mostrar el formulario
        showEventForm();

        // Rellenar el formulario con los datos del evento
        document.querySelector('#event-form [name="nombre"]').value = evento.nombre;
        document.querySelector('#event-form [name="tipo"]').value = evento.tipo;
        document.querySelector('#event-form [name="fecha"]').value = evento.fecha;
        document.querySelector('#event-form [name="hora"]').value = evento.hora;
        document.querySelector('#event-form [name="descripcion"]').value = evento.descripcion;
        document.querySelector('#event-form [name="ubicacion_id"]').value = evento.ubicacion_id;
        document.querySelector('#event-form [name="id_patrocinador"]').value = evento.id_patrocinador;

        // Cambiar el texto del botón y el título del formulario
        document.querySelector('#event-form h3').textContent = 'Editar Evento';
        document.querySelector('#event-form button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Actualizar Evento';

        modoEdicionEvento = true;
        idEventoAEditar = idEvento;
        mostrarAlerta('Datos cargados para edición', 'success');
    } else {
        mostrarAlerta(data.error || 'No se pudo cargar el evento para edición.', 'error');
    }
};


async function actualizarEvento(datosEvento) {
    try {
        const response = await fetch('../controller/action/ajax_eventosAdmin.php', {
            method: 'POST', // Usamos POST para la actualización como en tu PHP
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'actualizar',
                id_evento: datosEvento.id_evento,
                nombre: datosEvento.nombre,
                tipo: datosEvento.tipo,
                fecha: datosEvento.fecha,
                hora: datosEvento.hora,
                descripcion: datosEvento.descripcion,
                patrocinador: datosEvento.id_patrocinador, // <--- CAMBIO CLAVE
                ubicacion_id: datosEvento.ubicacion_id
            })
        });

        const contentType = response.headers.get('content-type'); //
        if (!contentType || !contentType.includes('application/json')) { //
            const text = await response.text(); //
            throw new Error(`Respuesta no JSON: ${text.substring(0, 100)}...`); //
        }

        return await response.json(); //
    } catch (error) {
        console.error('Error en actualizarEvento:', error);
        return {
            success: false,
            error: error.message || 'Error de conexión'
        };
    }
}


// Modificar manejarFormularioEvento para que actúe como agregar o editar
window.manejarFormularioEvento = async function(event) { //
    event.preventDefault(); //
    const form = event.target; //
    const formData = new FormData(form); //

    const submitBtn = form.querySelector('button[type="submit"]'); //
    submitBtn.disabled = true; //
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...'; //

   
    try {
        const datosEvento = {
            nombre: formData.get('nombre'),
            tipo: formData.get('tipo'),
            fecha: formData.get('fecha'),
            hora: formData.get('hora'), // <--- Fíjate en este
            descripcion: formData.get('descripcion'),
            ubicacion_id: formData.get('ubicacion_id'),
            id_patrocinador: formData.get('id_patrocinador')
        };

        console.log("Datos enviados desde el formulario:", datosEvento); // <-- Esta es la línea clave

        let resultado;
        if (modoEdicionEvento) {
            datosEvento.id_evento = idEventoAEditar; // Añadir el ID para la actualización
            resultado = await actualizarEvento(datosEvento);
        } else {
            // Unir fecha y hora para la creación si tu `agregarEvento` espera un solo campo de fecha-hora.
            // Si `agregarEvento` en PHP ya maneja fecha y hora por separado, no necesitas esta concatenación aquí.
            // Actualmente, tu `crearEvento` en JS los separa, pero tu PHP original los concatenaba.
            // Para mantener consistencia con el PHP modificado arriba, los enviamos separados.
            resultado = await crearEvento(datosEvento); //
        }

        if (resultado.success) { //
            mostrarAlerta(`Evento ${modoEdicionEvento ? 'actualizado' : 'creado'} correctamente`, 'success');
            form.reset(); //
            hideEventForm(); // Ocultar el formulario después de guardar
            mostrarEventos(); // // Recargar la tabla
            modoEdicionEvento = false; // Resetear modo
            idEventoAEditar = null; // Resetear ID
            // Restaurar título y texto del botón del formulario
            document.querySelector('#event-form h3').textContent = 'Crear Nuevo Evento';
            document.querySelector('#event-form button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Guardar Evento';
        } else {
            mostrarAlerta(resultado.error || `Error al ${modoEdicionEvento ? 'actualizar' : 'crear'} evento`, 'error'); //
        }
    } catch (error) {
        console.error('Error en manejarFormularioEvento:', error); //
        mostrarAlerta('Error al procesar la solicitud', 'error'); //
    } finally {
        submitBtn.disabled = false; //
        // Restaurar el texto original del botón según el modo
        submitBtn.innerHTML = modoEdicionEvento ? '<i class="fas fa-save"></i> Actualizar Evento' : '<i class="fas fa-save"></i> Guardar Evento';
    }
};

// Función para eliminar evento
window.eliminarEvento = async function(idEvento) {
    Swal.fire({
        title: '¿Eliminar evento?',
        text: '¿Estás seguro de que deseas eliminar este evento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch('../controller/action/ajax_eventosAdmin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'eliminar', id_evento: idEvento })
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'El evento fue eliminado correctamente',
                        confirmButtonColor: '#28a745',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    mostrarEventos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'No se pudo eliminar el evento',
                        confirmButtonColor: '#dc3545'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonColor: '#dc3545'
                });
            }
        }
    });
};