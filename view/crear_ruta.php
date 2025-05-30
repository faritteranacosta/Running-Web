<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'admin') {
    header("Location: acceso_denegado.html");
    exit();
}else{
    $id = $_SESSION['ID_USUARIO'];
    $nombre = ucfirst($_SESSION['NOMBRE_USUARIO']);
    $apellido = ucfirst($_SESSION['APELLIDO_USUARIO']);
    $correo = $_SESSION['CORREO_USUARIO'];
    $sexo = $_SESSION['SEXO_USUARIO'];
    $rol = ucfirst($_SESSION['ROL_USUARIO']);
    $fecha_nacimiento = $_SESSION['FECHA_NACIMIENTO'];
    $fecha_registro = $_SESSION['FECHA_REGISTRO'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Rutas Personalizadas</title>
    <link rel="icon" href="./assets/img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4fc3f7;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .subtitle {
            font-weight: 300;
            opacity: 0.9;
        }

        .map-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            height: 600px;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .stats {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .stats h3 {
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .stat-item {
            background-color: var(--light-color);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            .map-container {
                height: 400px;
            }

            .btn {
                padding: 10px 18px;
                font-size: 0.9rem;
            }
        }

        /* Estilos para los marcadores personalizados */
        .custom-marker {
            background-color: var(--accent-color);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: block;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Estilo para el popup de marcadores */
        .leaflet-popup-content {
            font-family: 'Poppins', sans-serif;
        }

        /* Estilo para el tooltip de la ruta */
        .leaflet-tooltip {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 4px;
        }
    </style>
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
        <p>© 2023 Mapa Interactivo de Rutas | Desarrollado con Leaflet</p>
    </footer>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-gpx/gpx.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/togeojson@0.16.0/dist/togeojson.min.js"></script>
    <script>
        // Configuración inicial
        const map = L.map('map').setView([11.2408, -74.199], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);

        // Variables de estado
        let puntosRuta = [];
        let markers = [];
        let polyline = L.polyline([], {
            color: '#4a6fa5',
            weight: 5,
            opacity: 0.8,
            dashArray: '10, 10',
            lineJoin: 'round'
        }).addTo(map);

        /*async function obtenerUsuarioId() {
            console.log("Hola",<?php echo $id; ?>);
            return <?php echo $id; ?>;
        }*/

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
                const response = await fetch('../controller/action/ajax_guardarRuta.php', {
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

        // Modificar los botones de control
        document.addEventListener('DOMContentLoaded', function() {
            const controls = document.querySelector('.controls');
            
            if (fromParent) {
                // Añadir botón "Guardar y Volver" si viene del formulario padre
                const returnButton = document.createElement('button');
                returnButton.className = 'btn btn-return';
                returnButton.innerHTML = '<i class="fas fa-arrow-left"></i> Guardar y Volver';
                returnButton.onclick = guardarRuta;
                controls.insertBefore(returnButton, controls.firstChild);
            }

            // Asignar eventos a los botones existentes
            document.querySelector('.btn-primary').addEventListener('click', guardarRuta);
            document.querySelector('.btn-success').addEventListener('click', exportarRuta);
            document.querySelector('.btn-danger').addEventListener('click', limpiarRuta);
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
                confirmButtonColor: '#4a6fa5',
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

        document.querySelector('.btn-primary').addEventListener('click', guardarRuta);
        document.querySelector('.btn-success').addEventListener('click', exportarRuta);
        document.querySelector('.btn-danger').addEventListener('click', limpiarRuta);

    </script>
    
</body>

</html>