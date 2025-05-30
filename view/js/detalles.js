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
        const response = await fetch(`../controller/action/ajax_carreras.php?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const carrera = await response.json();
        console.log('Datos recibidos de la carrera:', carrera);
        
        if (!carrera) {
            throw new Error('No se encontró la carrera');
        }

        // Actualizar datos principales
        document.getElementById('titulo').textContent = carrera.nombre || 'Carrera sin nombre';
        document.getElementById('descripcion-carrera').textContent = carrera.descripcion || 'No hay descripción disponible';

        // Fecha y estado
        const fechaFormateada = formatearFecha(carrera.fecha);
        document.getElementById('fecha-carrera').textContent = fechaFormateada;

        const estado = determinarEstado(carrera.fecha);
        const estadoElement = document.getElementById('estado-carrera');
        estadoElement.textContent = estado.texto;
        estadoElement.className = `status ${estado.clase}`;

        // Ubicación - Usar dirección si ubicación no está disponible
        document.getElementById('ubicacion-carrera').textContent = carrera.ubicacion || carrera.direccion || 'Ubicación no disponible';

        // Imagen - Verificar si el campo existe
        const imagenCarrera = document.getElementById('carrera-imagen');
        if (carrera.imagen && carrera.imagen !== 'null' && carrera.imagen !== '') {
            imagenCarrera.src = carrera.imagen;
        } else {
            // Imagen por defecto
            imagenCarrera.src = '../assets/img/default_race.jpg';
        }

        // Tags
        const tagsContainer = document.getElementById('tags-carrera');
        tagsContainer.innerHTML = '';

        // Distancia puede estar como string o como número
        if (carrera.distancia) {
            const distanciaValor = typeof carrera.distancia === 'string' 
                ? carrera.distancia.replace(/"/g, '') 
                : carrera.distancia;
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">${distanciaValor} km</span>`;
        }
        
        // Verificar si hay información de categoría
        if (carrera.categoria && carrera.categoria.nombre) {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">${carrera.categoria.nombre}</span>`;
        } else if (carrera.categoria && typeof carrera.categoria === 'string') {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">${carrera.categoria}</span>`;
        }
        
        // Verificar si hay información de tipo
        if (carrera.tipo_carrera && carrera.tipo_carrera.nombre) {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">${carrera.tipo_carrera.nombre}</span>`;
        } else if (carrera.tipo && typeof carrera.tipo === 'string') {
            tagsContainer.innerHTML += `<span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">${carrera.tipo}</span>`;
        }

        // Detalles técnicos
        document.getElementById('distancia-carrera').textContent = carrera.distancia ? `${carrera.distancia} km` : '-- km';
        
        // Tipo de ruta - puede venir en diferentes formatos
        const tipoRuta = 
            (carrera.ruta && carrera.ruta.nombre) || 
            (carrera.tipo_ruta) || 
            (carrera.tipo_carrera && carrera.tipo_carrera.nombre) || 
            '--';
        document.getElementById('tipo-ruta').textContent = tipoRuta;
        
        // Elevación
        document.getElementById('elevacion-carrera').textContent = carrera.elevacion ? `${carrera.elevacion} m` : '-- m';

        // Patrocinador
        const patrocinadoresContainer = document.getElementById('patrocinadores');
        if (carrera.patrocinador) {
            patrocinadoresContainer.innerHTML = `
                <img src="${carrera.logo_patrocinador || 'https://via.placeholder.com/100x50?text=Patrocinador'}" 
                     alt="${carrera.patrocinador}" class="h-10 object-contain">
            `;
        } else {
            patrocinadoresContainer.innerHTML = '<p>No hay patrocinadores registrados</p>';
        }

        // Guardar el ID del evento - Manejar diferentes estructuras posibles
        window.idEvento = null;
        if (carrera.evento && carrera.evento.id) {
            window.idEvento = carrera.evento.id;
        } else if (carrera.id_evento) {
            window.idEvento = carrera.id_evento;
        } else if (carrera.evento && typeof carrera.evento === 'number') {
            window.idEvento = carrera.evento;
        } else if (carrera.evento_id) {
            window.idEvento = carrera.evento_id;
        }

        if (window.idEvento) {
            const params = new URLSearchParams(window.location.search);
            params.set('id', idCarrera);
            params.set('evento', window.idEvento);
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.replaceState({}, '', newUrl);
        }

        // Verificar si el ID de ruta está presente
        if (carrera.ruta && carrera.ruta.id) {
            const params = new URLSearchParams(window.location.search);
            params.set('idRuta', carrera.ruta.id);
            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.history.replaceState({}, '', newUrl);
            
            // Cargar los datos de la ruta
            cargarRuta(carrera.ruta.id);
        }

        // Resto del código para gestionar inscripciones...
        validarInscripcion(window.idEvento);
        cargarOpcionesInscripcion(carrera);

    } catch (error) {
        console.error('Error al cargar detalles:', error);
        document.getElementById('titulo').textContent = 'Error al cargar la carrera';
        document.getElementById('descripcion-carrera').textContent = error.message;
    }
}

// Nueva función para validar inscripción
async function validarInscripcion(idEvento) {
    if (!idEvento) return;
    
    const btnInscribirse = document.getElementById('btn-inscribirse');
    
    try {
        const checkResponse = await fetch(`../controller/action/ajax_participar.php?check=1&id_evento=${encodeURIComponent(idEvento)}`);
        const checkData = await checkResponse.json();
        const yaInscrito = checkData && checkData.exists;
        
        if (yaInscrito) {
            btnInscribirse.disabled = true;
            btnInscribirse.textContent = 'Ya estás inscrito';
            btnInscribirse.className = 'w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed';
        } else {
            btnInscribirse.disabled = false;
            btnInscribirse.textContent = 'Confirmar inscripción';
            btnInscribirse.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
            btnInscribirse.addEventListener('click', function() {
                registrarParticipacion(idEvento);
            });
        }
    } catch (e) {
        console.error('Error al verificar inscripción:', e);
        btnInscribirse.textContent = 'Confirmar inscripción';
        btnInscribirse.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
    }
}

// Función para registrar participación adaptada a ajax_participar.php
async function registrarParticipacion(idEvento) {
    const btnInscribirse = document.getElementById('btn-inscribirse');

    try {
        // Deshabilitar botón mientras se procesa
        btnInscribirse.disabled = true;
        btnInscribirse.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';

        // Simplificamos la solicitud para que coincida con lo que espera el backend
        const response = await fetch('../controller/action/ajax_participar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Solo enviamos id_evento como requiere ajax_participar.php
            body: `id_evento=${encodeURIComponent(idEvento)}`
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
        console.error('Error al registrar:', error);
        
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
    // Como el backend no requiere seleccionar categoría/talla actualmente,
    // podemos ocultar o simplificar este formulario
    const opcionesInscripcion = document.getElementById('opciones-inscripcion');
    if (opcionesInscripcion) {
        opcionesInscripcion.classList.add('hidden');
    }
    
    // Actualizar botón de inscripción para que sea más directo
    const btnInscribirse = document.getElementById('btn-inscribirse');
    if (btnInscribirse) {
        btnInscribirse.textContent = 'Inscribirme ahora';
        btnInscribirse.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold';
    }
}

// Función para cargar la ruta
async function cargarRuta(ipRuta) {
    try {
        const response = await fetch(`../controller/action/ajax_rutas.php?id=${encodeURIComponent(ipRuta)}`);
        
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
        console.error('Error:', error);
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