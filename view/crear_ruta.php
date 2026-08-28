<?php
$requiredRole = 'admin';
require __DIR__ . '/components/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Rutas Personalizadas — RunningWeb</title>
    <link rel="icon" href="../public/assets/img/icon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/crear_ruta.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Mapa de Rutas Personalizadas</h1>
            <p class="subtitle">Dibuja tu ruta y guarda tus recorridos favoritos</p>
        </header>

        <div class="stats">
            <h3>Estadísticas de la Ruta</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="point-count">0</div>
                    <div class="stat-label">Puntos en la ruta</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="distance">0 km</div>
                    <div class="stat-label">Distancia total</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="area">0 km²</div>
                    <div class="stat-label">Área cubierta</div>
                </div>
            </div>
        </div>

        <div class="map-container">
            <div id="map"></div>
        </div>

        <div class="controls">
            <button class="btn btn-primary" onclick="guardarRuta()">
                <i class="fas fa-save"></i> Guardar Ruta
            </button>
            <button class="btn btn-success" onclick="exportarRuta()">
                <i class="fas fa-download"></i> Exportar como GPX
            </button>
            <button class="btn btn-danger" onclick="limpiarRuta()">
                <i class="fas fa-trash-alt"></i> Limpiar Ruta
            </button>
        </div>

        <div class="instructions">
            <h3>Instrucciones</h3>
            <p>Haz clic en el mapa para agregar puntos a tu ruta. Puedes guardar tu ruta o limpiarla para empezar de
                nuevo.</p>
        </div>
    </div>

    <footer>
        <p>© 2026 RunningWeb | Mapa interactivo con Leaflet</p>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-gpx/gpx.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/togeojson@0.16.0/dist/togeojson.min.js"></script>
    <script>
        // Si esta ventana se abrió desde el formulario del panel de admin
        // (crear_ruta.php?from_form=1), se agrega un botón extra de "Guardar y Volver".
        const fromParent = new URLSearchParams(window.location.search).get('from_form') === '1';

        // Configuración inicial
        const map = L.map('map').setView([11.2408, -74.199], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Variables de estado
        let puntosRuta = [];
        let markers = [];
        let polyline = L.polyline([], {
            color: '#0EA5E9',
            weight: 5,
            opacity: 0.85,
            dashArray: '10, 10',
            lineJoin: 'round'
        }).addTo(map);

        // Función para calcular distancia entre puntos
        function calcularDistancia(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radio de la Tierra en km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // Función para calcular distancia total
        function calcularDistanciaTotal() {
            let distancia = 0;
            for (let i = 1; i < puntosRuta.length; i++) {
                distancia += calcularDistancia(
                    puntosRuta[i - 1][0], puntosRuta[i - 1][1],
                    puntosRuta[i][0], puntosRuta[i][1]
                );
            }
            return distancia;
        }

        // Actualizar estadísticas
        function actualizarEstadisticas() {
            document.getElementById('point-count').textContent = puntosRuta.length;

            if (puntosRuta.length > 1) {
                const distancia = calcularDistanciaTotal();
                document.getElementById('distance').textContent = distancia.toFixed(2) + ' km';

                if (puntosRuta.length > 2) {
                    let area = 0;
                    for (let i = 0; i < puntosRuta.length - 1; i++) {
                        area += (puntosRuta[i][1] * puntosRuta[i + 1][0] - puntosRuta[i + 1][1] * puntosRuta[i][0]);
                    }
                    area = Math.abs(area / 2) * 111 * 111;
                    document.getElementById('area').textContent = area.toFixed(2) + ' km²';
                } else {
                    document.getElementById('area').textContent = '0 km²';
                }
            } else {
                document.getElementById('distance').textContent = '0 km';
                document.getElementById('area').textContent = '0 km²';
            }
        }

        // Manejador de clics en el mapa
        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            puntosRuta.push([lat, lng]);

            polyline.setLatLngs(puntosRuta);

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [20, 20]
                })
            }).addTo(map)
                .bindPopup(`Punto ${puntosRuta.length}<br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`)
                .openPopup();

            markers.push(marker);
            actualizarEstadisticas();

            if (puntosRuta.length > 1) {
                map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
            }
        });

        async function guardarRuta() {
            if (typeof Swal === 'undefined') {
                await loadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
            }

            if (puntosRuta.length < 2) {
                await Swal.fire({
                    title: 'Ruta incompleta',
                    text: 'Necesitas al menos 2 puntos para guardar una ruta',
                    icon: 'warning'
                });
                return;
            }

            const { value: nombreRuta } = await Swal.fire({
                title: 'Guardar ruta',
                input: 'text',
                inputLabel: 'Nombre de la ruta',
                inputPlaceholder: 'Ej: Ruta al Siruma',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) return 'Debes ingresar un nombre';
                    if (value.length < 3) return 'El nombre es muy corto';
                }
            });

            if (!nombreRuta) return;

            try {
                const response = await fetch('../api/rutas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        usuario_id: <?php echo $id; ?>,
                        nombre: nombreRuta,
                        puntos: puntosRuta
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                if (data.success) {
                    await Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success'
                    });

                    if (window.opener) {
                        window.opener.postMessage({ type: 'rutaCreada', id_ruta: data.data.id }, '*');
                        window.close();
                    }

                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                await Swal.fire({
                    title: 'Error',
                    text: error.message || 'No se pudo guardar la ruta',
                    icon: 'error'
                });
            }
        }

        // Si la ventana se abrió desde el formulario del panel de admin,
        // se agrega un botón extra de "Guardar y Volver".
        document.addEventListener('DOMContentLoaded', function() {
            if (fromParent) {
                const controls = document.querySelector('.controls');
                const returnButton = document.createElement('button');
                returnButton.className = 'btn btn-return';
                returnButton.innerHTML = '<i class="fas fa-arrow-left"></i> Guardar y Volver';
                returnButton.onclick = guardarRuta;
                controls.insertBefore(returnButton, controls.firstChild);
            }
        });

        // Función para exportar a GPX
        function exportarRuta() {
            if (puntosRuta.length < 2) {
                Swal.fire({
                    title: 'Ruta incompleta',
                    text: 'Necesitas al menos 2 puntos para exportar una ruta',
                    icon: 'warning'
                });
                return;
            }

            let gpx = `
            <gpx version="1.1" creator="Mapa de Rutas" xmlns="http://www.topografix.com/GPX/1/1">
                <trk>
                    <name>Mi Ruta Personalizada</name>
                    <trkseg>`;

                        puntosRuta.forEach(point => {
                            gpx += `
                        <trkpt lat="${point[0]}" lon="${point[1]}"></trkpt>`;
                        });

                        gpx += `
                    </trkseg>
                </trk>
            </gpx>`;

            downloadFile(gpx, 'mi_ruta.gpx', 'application/gpx+xml');
        }

        // Función para limpiar la ruta
        async function limpiarRuta() {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción borrará todos los puntos de la ruta actual.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0EA5E9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                puntosRuta = [];
                polyline.setLatLngs([]);
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                actualizarEstadisticas();

                await Swal.fire(
                    '¡Ruta limpiada!',
                    'Puedes comenzar a dibujar una nueva ruta.',
                    'success'
                );
            }
        }

        function loadScript(src) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        function downloadFile(content, fileName, contentType) {
            const blob = new Blob([content], { type: contentType });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

    </script>

</body>
</html>
