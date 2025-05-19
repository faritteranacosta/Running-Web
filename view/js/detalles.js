// Toggle Mobile Menu
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
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
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const idCarrera = params.get('id');
            let idEvento = params.get('evento');

            // Si no hay idEvento en la URL, lo obtendremos después al cargar la carrera
            if (idCarrera) {
                cargarDetallesCarrera(idCarrera);
            } else {
                document.getElementById('titulo').textContent = 'Carrera no encontrada';
            }
        });

        async function cargarDetallesCarrera(idCarrera) {
            try {
                const response = await fetch(`../controller/action/ajax_carreras.php?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`);
                const carrera = await response.json();
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
                
                // Ubicación
                document.getElementById('ubicacion-carrera').textContent = carrera.ubicacion || 'Ubicación no disponible';
                
                // Imagen
                if (carrera.imagen) {
                    document.getElementById('carrera-imagen').src = carrera.imagen;
                }
                
                // Tags
                const tagsContainer = document.getElementById('tags-carrera');
                tagsContainer.innerHTML = '';
                
                if (carrera.distancia) {
                    tagsContainer.innerHTML += `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">${carrera.distancia} km</span>`;
                }
                if (carrera.categoria) {
                    tagsContainer.innerHTML += `<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">${carrera.categoria}</span>`;
                }
                if (carrera.tipo) {
                    tagsContainer.innerHTML += `<span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">${carrera.tipo}</span>`;
                }

                // Detalles técnicos
                document.getElementById('distancia-carrera').textContent = carrera.distancia ? `${carrera.distancia} km` : '-- km';
                document.getElementById('tipo-ruta').textContent = carrera.tipo_ruta || '--';
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
                
                // Guardar el id del evento globalmente para inscripción y para uso en la URL
                window.idEvento = carrera.id_evento || carrera.evento || carrera.evento_id || (carrera.evento && carrera.evento.id) || null;

                // Si el id_evento no estaba en la URL, lo agregamos ahora
                if (window.idEvento) {
                    const params = new URLSearchParams(window.location.search);
                    params.set('id', idCarrera);
                    params.set('evento', window.idEvento);
                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.history.replaceState({}, '', newUrl);
                }

                // Configurar botón de inscripción para usar SIEMPRE el idEvento correcto
                const btnInscribirse = document.getElementById('btn-inscribirse');
                btnInscribirse.addEventListener('click', function() {
                    // Usar window.idEvento, que siempre estará actualizado
                    registrarParticipacion(window.idEvento);
                });
                
                // Cargar categorías y tallas (simulado)
                cargarOpcionesInscripcion(carrera);

            } catch (error) {
                console.error('Error al cargar detalles:', error);
                document.getElementById('titulo').textContent = 'Error al cargar la carrera';
                document.getElementById('descripcion-carrera').textContent = error.message;
            }
        }

        // Función para cargar opciones de inscripción (simulado)
        function cargarOpcionesInscripcion(carrera) {
            const selectCategoria = document.getElementById('select-categoria');
            const selectTalla = document.getElementById('select-talla');
            
            // Simular carga de categorías
            selectCategoria.innerHTML = '';
            if (carrera.categorias && carrera.categorias.length > 0) {
                carrera.categorias.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = `${cat.nombre} - $${cat.precio}`;
                    selectCategoria.appendChild(option);
                });
            } else {
                // Categorías por defecto
                const categorias = [
                    { id: 'general', nombre: 'General', precio: '500' },
                    { id: 'estudiante', nombre: 'Estudiante', precio: '400' },
                    { id: 'vip', nombre: 'VIP', precio: '800' }
                ];
                
                categorias.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = `${cat.nombre} - $${cat.precio} MXN`;
                    selectCategoria.appendChild(option);
                });
            }
            
            // Tallas de playera
            selectTalla.innerHTML = '';
            const tallas = ['Chica', 'Mediana', 'Grande', 'Extra Grande'];
            tallas.forEach(talla => {
                const option = document.createElement('option');
                option.value = talla.toLowerCase();
                option.textContent = talla;
                selectTalla.appendChild(option);
            });
        }

        // Función para registrar participación
        async function registrarParticipacion(idEvento) {
            const selectCategoria = document.getElementById('select-categoria');
            const selectTalla = document.getElementById('select-talla');
            const btnInscribirse = document.getElementById('btn-inscribirse');
            
            if (!selectCategoria.value || !selectTalla.value) {
                alert('Por favor selecciona una categoría y talla');
                return;
            }
            
            try {
                btnInscribirse.disabled = true;
                btnInscribirse.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                
                const response = await fetch('../controller/action/ajax_participar.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_evento=${encodeURIComponent(idEvento)}&categoria=${encodeURIComponent(selectCategoria.value)}&talla=${encodeURIComponent(selectTalla.value)}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    btnInscribirse.textContent = 'Inscripción confirmada';
                    btnInscribirse.className = 'w-full bg-green-500 text-white py-3 rounded-lg font-semibold';
                } else {
                    throw new Error(data.message || 'Error al registrar participación');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
                btnInscribirse.disabled = false;
                btnInscribirse.innerHTML = '<i class="fas fa-running mr-2"></i> Confirmar inscripción';
            }
        }