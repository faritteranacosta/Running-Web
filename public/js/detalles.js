// Toggle Mobile Menu
document.getElementById('mobile-menu-button').addEventListener('click', function () {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});

// Función para formatear la fecha
function formatearFecha(fechaStr) {
    if (!fechaStr) return 'No disponible';
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

// Función para determinar el estado de la carrera
function determinarEstado(fechaStr) {
    if (!fechaStr) return { texto: 'Por definir', clase: 'status-pendiente' };

    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    const fechaCarrera = new Date(fechaStr);
    fechaCarrera.setHours(0, 0, 0, 0);

    if (fechaCarrera > hoy) {
        return { texto: 'Próximo', clase: 'status-proximo' };
    } else if (fechaCarrera.getTime() === hoy.getTime()) {
        return { texto: 'Hoy', clase: 'status-hoy' };
    } else {
        return { texto: 'Finalizado', clase: 'status-finalizado' };
    }
}

// Cargar detalles de la carrera
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const idCarrera = params.get('id');
    let idEvento = params.get('evento');
    let idRuta = params.get('idRuta');


    if (idCarrera) {
        cargarDetallesCarrera(idCarrera);
        cargarRuta(idRuta);
    } else {
        document.getElementById('titulo').textContent = 'Carrera no encontrada';
    }
});

async function cargarDetallesCarrera(idCarrera) {
    try {
        const response = await fetch(`../api/carreras?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const carrera = await response.json();
        if (!carrera) {
            throw new Error('No se encontró la carrera');
        }

        // Título y descripción
        document.getElementById('titulo').textContent = carrera.nombre || 'Carrera sin nombre';
        document.getElementById('descripcion-carrera').textContent = carrera.descripcion || 'No hay descripción disponible';

        // Fecha y estado
        const fechaFormateada = formatearFecha(carrera.fecha);
        document.getElementById('fecha-carrera').textContent = fechaFormateada;

        const estado = determinarEstado(carrera.fecha);
        const estadoElement = document.getElementById('estado-carrera');
        estadoElement.textContent = estado.texto;
        estadoElement.className = `status ${estado.clase}`;

        // Ubicación
        document.getElementById('ubicacion-carrera').textContent = carrera.direccion || 'Ubicación no disponible';

        // Imagen (no existe en backend, así que siempre default)
        const imagenCarrera = document.getElementById('carrera-imagen');
        imagenCarrera.src = '../public/assets/img/default_race.jpg';

        // Tags
        const tagsContainer = document.getElementById('tags-carrera');
        tagsContainer.innerHTML = '';

        if (carrera.distancia) {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">${carrera.distancia} km</span>`;
        }
        if (carrera.categoria) {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">${carrera.categoria}</span>`;
        }

        // Detalles técnicos
        document.getElementById('distancia-carrera').textContent = carrera.distancia ? `${carrera.distancia} km` : '-- km';
        document.getElementById('tipo-ruta').textContent = '--'; // No disponible en backend
        document.getElementById('elevacion-carrera').textContent = '-- m'; // No disponible

        // Patrocinador
        const patrocinadoresContainer = document.getElementById('patrocinadores');
        if (carrera.patrocinador) {
            patrocinadoresContainer.innerHTML = `<span class="font-semibold">${carrera.patrocinador}</span>`;
        } else {
            patrocinadoresContainer.innerHTML = '<p>No hay patrocinadores registrados</p>';
        }

        // Guardar el ID del evento
        window.idEvento = carrera.evento || null;

        // Validar inscripción y opciones
        validarInscripcion(window.idEvento);
        cargarOpcionesInscripcion(carrera);

    } catch (error) {
        document.getElementById('titulo').textContent = 'Error al cargar la carrera';
        document.getElementById('descripcion-carrera').textContent = error.message;
    }
}

// Nueva función para validar inscripción
async function validarInscripcion(idEvento) {
    if (!idEvento) {
        return;
    }
    
    const btnInscribirse = document.getElementById('btn-inscribirse');
    if (!btnInscribirse) {
        return;
    }
    
    // Eliminar eventos previos para evitar duplicados
    const nuevoBoton = btnInscribirse.cloneNode(true);
    btnInscribirse.parentNode.replaceChild(nuevoBoton, btnInscribirse);
    
    try {
        const checkUrl = `../api/participar?check=1&id_evento=${encodeURIComponent(idEvento)}`;
        console.log('Consultando:', checkUrl);
        
        const checkResponse = await fetch(checkUrl);
        const checkData = await checkResponse.json();
        console.log('Respuesta de verificación:', checkData);
        
        const yaInscrito = checkData && checkData.exists;
        
        if (yaInscrito) {
            nuevoBoton.disabled = true;
            nuevoBoton.textContent = 'Ya estás inscrito';
            nuevoBoton.className = 'w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed';
        } else {
            nuevoBoton.disabled = false;
            nuevoBoton.textContent = 'Confirmar inscripción';
            nuevoBoton.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
            nuevoBoton.addEventListener('click', function() {
                registrarParticipacion(idEvento);
            });
        }
    } catch (e) {
        nuevoBoton.disabled = false;
        nuevoBoton.textContent = 'Confirmar inscripción';
        nuevoBoton.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
        nuevoBoton.addEventListener('click', function() {
            registrarParticipacion(idEvento);
        });
    }
}

// Función para registrar participación vía API
async function registrarParticipacion(idEvento) {
    const btnInscribirse = document.getElementById('btn-inscribirse');
    if (!btnInscribirse) {
        return;
    }

    try {
        // Deshabilitar botón mientras se procesa
        btnInscribirse.disabled = true;
        btnInscribirse.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';

        // Construir FormData para enviar al servidor
        const formData = new FormData();
        formData.append('id_evento', idEvento);
        
        const response = await fetch('../api/participar', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Verificar si SweetAlert está disponible
            if (typeof Swal === 'undefined') {
                await loadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
            }
            
            Swal.fire({
                icon: 'success',
                title: '¡Inscripción confirmada!',
                text: data.message || '¡Te has inscrito correctamente a este evento!',
                confirmButtonColor: '#28a745',
                showConfirmButton: false,
                timer: 2000,
                willClose: () => {
                    const params = new URLSearchParams(window.location.search);
                    const idCarrera = params.get('id');
                    if (idCarrera) {
                        cargarDetallesCarrera(idCarrera); // Recargar detalles para actualizar estado
                    }
                }
            });
            
            btnInscribirse.textContent = 'Ya estás inscrito';
            btnInscribirse.className = 'w-full bg-green-500 text-white py-3 rounded-lg font-semibold cursor-not-allowed';
            btnInscribirse.disabled = true;
        } else {
            throw new Error(data.message || 'Error al registrar participación');
        }
    } catch (error) {
        console.error('Error al registrar participación:', error);
        
        // Verificar si SweetAlert está disponible
        if (typeof Swal === 'undefined') {
            await loadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar tu inscripción',
            confirmButtonColor: '#d33'
        });
        
        btnInscribirse.disabled = false;
        btnInscribirse.innerHTML = 'Confirmar inscripción';
        btnInscribirse.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
    }
}

// Función auxiliar para cargar scripts dinámicamente
function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Función mejorada para cargar opciones de inscripción
function cargarOpcionesInscripcion(carrera) {
    const selectCategoria = document.getElementById('select-categoria');
    const selectTalla = document.getElementById('select-talla');

    // Simular carga de categorías
    selectCategoria.innerHTML = '';
 
        // Categorías por defecto
        const categorias = [
            { id: '15k', nombre: '15k', precio: '150000' },
            { id: '10k', nombre: '10k', precio: '140000' },
            { id: '5k', nombre: '5k', precio: '130000' }
        ];

        categorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = `${cat.nombre} - $${cat.precio} COP`;
            selectCategoria.appendChild(option);
        });

    // Tallas de playera
    selectTalla.innerHTML = '';
    const tallas = ['S', 'M', 'L', 'XL'];
    tallas.forEach(talla => {
        const option = document.createElement('option');
        option.value = talla.toLowerCase();
        option.textContent = talla;
        selectTalla.appendChild(option);
    });
}


// Función para cargar la ruta
async function cargarRuta(ipRuta) {
    try {
        const response = await fetch(`../api/rutas?id=${encodeURIComponent(ipRuta)}`);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();
        
        if (!data.success || !data.data) {
            throw new Error(data.message || 'No se encontraron datos de ruta');
        }

        const ruta = data.data;

        // Actualiza la UI con los datos
        document.getElementById('distancia-carrera').textContent = `${ruta.distancia.replace(/"/g, '')} km`;
        document.getElementById('tipo-ruta').textContent = ruta.nombre;

        // Verifica y muestra el mapa
        if (ruta.puntos && ruta.puntos.length >= 2) {
            mostrarMapa(ruta.puntos);
        } else {
            throw new Error('La ruta no tiene suficientes puntos para mostrar');
        }
    } catch (error) {
        document.getElementById('mapa-ruta').innerHTML = `
            <div class="p-4 bg-red-100 text-red-800 rounded">
                <p class="font-bold">Error al cargar el mapa</p>
                <p>${error.message}</p>
            </div>`;
    }
}

function mostrarMapa(puntos) {
    // Limpia el contenedor y crea el div para el mapa
    const mapaContainer = document.getElementById('mapa-ruta');
    mapaContainer.innerHTML = '<div id="leaflet-map" style="height: 400px; width: 100%;"></div>';

    // Crea el mapa centrado en la primera coordenada
    const map = L.map('leaflet-map').setView(puntos[0], 15);

    // Añade capa de tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Dibuja la ruta
    const polyline = L.polyline(puntos, {
        color: '#3498db',
        weight: 6,
        opacity: 0.8,
        lineJoin: 'round'
    }).addTo(map);

    // Añade marcadores para inicio y fin
    L.marker(puntos[0], {
        icon: L.divIcon({
            className: 'start-marker',
            html: '<div class="bg-blue-600 text-white rounded-full p-2">INICIO</div>'
        })
    }).addTo(map);

    L.marker(puntos[puntos.length - 1], {
        icon: L.divIcon({
            className: 'end-marker',
            html: '<div class="bg-red-600 text-white rounded-full p-2">FIN</div>'
        })
    }).addTo(map);

    // Ajusta el zoom para mostrar toda la ruta
    map.fitBounds(polyline.getBounds());
}