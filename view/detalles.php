<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'corredor') {
    header("Location: acceso_denegado.html");
    exit();
} else {
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
    <title>Detalles de Carrera - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.2);
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-proximo {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-hoy {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-finalizado {
            background-color: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .map-container {
            height: 400px;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .detail-card {
            border-left: 4px solid #0ea5e9;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="index.html" class="flex items-center space-x-3">
                        <img src="assets/img/icon-black.jpg" alt="Logo"
                            class="w-12 h-12 rounded-full border-2 border-white">
                        <h1 class="text-2xl font-bold">RunningWeb</h1>
                    </a>

                    <nav class="hidden md:flex space-x-1">
                        <a href="carreras.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-home mr-2"></i> Inicio
                        </a>
                        <a href="eventos.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-calendar-alt mr-2"></i> Eventos
                        </a>
                        <a href="carreras.php"
                            class="px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                            <i class="fas fa-running mr-2"></i> Carreras
                        </a>
                        <a href="productos.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-store mr-2"></i> Productos
                        </a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="runner.php" class="flex items-center gap-10">
                        <p><?php echo htmlspecialchars($nombre) ?> </p>
                        <div
                            class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg transform transition-all duration-300 hover:scale-105 border-2 border-white relative overflow-hidden mr-3">
                            <span class="text-lg font-extrabold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.2);">
                                <?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?>
                            </span>
                            <div class="absolute inset-0 bg-white opacity-10 rounded-full"></div>
                            <div
                                class="absolute -inset-1 bg-gradient-to-br from-blue-400 to-purple-500 opacity-30 blur-sm">
                            </div>
                        </div>
                    </a>
                    <button class="md:hidden text-white focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
            </div>

            <div class="md:hidden hidden mt-4 pb-2" id="mobile-menu">
                <a href="carreras.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="eventos.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-calendar-alt mr-2"></i> Eventos
                </a>
                <a href="carreras.php"
                    class="block px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                    <i class="fas fa-running mr-2"></i> Carreras
                </a>
                <a href="productos.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-store mr-2"></i> Productos
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex flex-col md:flex-row gap-8 mb-12">
            <div class="md:w-2/3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <img id="carrera-imagen" src="assets/img/runner1.png" alt="Imagen de la carrera"
                        class="w-full h-64 md:h-80 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h1 id="titulo" class="text-3xl font-bold text-gray-800">Cargando...</h1>
                            <span id="estado-carrera" class="status status-proximo">Próximo</span>
                        </div>
                        <div class="flex items-center text-gray-500 mb-4">
                            <i class="fas fa-calendar-day mr-2"></i>
                            <span id="fecha-carrera" class="mr-4">Fecha no disponible</span>
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span id="ubicacion-carrera">Ubicación no disponible</span>
                        </div>
                        <p id="descripcion-carrera" class="text-gray-700 mb-6">
                            Cargando descripción de la carrera...
                        </p>
                        <div id="tags-carrera" class="flex flex-wrap gap-2 mb-6">
                            <!-- Tags se llenarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-1/3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-4">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Inscríbete ahora</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Categoría</label>
                                <select id="select-categoria"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Cargando categorías...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Talla de playera</label>
                                <select id="select-talla"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Cargando tallas...</option>
                                </select>
                            </div>
                            <button id="btn-inscribirse"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-300">
                                <i class="fas fa-running mr-2"></i> Confirmar inscripción
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Incluye:</h3>
                        <ul id="beneficios-carrera" class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-tshirt text-blue-500 mr-2"></i>
                                Playera técnica
                            </li>
                            <!-- Otros beneficios se cargarán dinámicamente -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="md:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-route text-blue-500 mr-2"></i>
                        Mapa de la Ruta
                    </h2>
                </div>
                <div class="map-container" id="mapa-ruta">
                    <div class="h-full flex items-center justify-center bg-gray-100 text-gray-500">
                        <p>Cargando mapa de la ruta...</p>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200">
                    <div class="flex flex-wrap justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Distancia</h3>
                            <p id="distancia-carrera" class="text-lg font-semibold">-- km</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tipo de ruta</h3>
                            <p id="tipo-ruta" class="text-lg font-semibold">--</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Elevación</h3>
                            <p id="elevacion-carrera" class="text-lg font-semibold">-- m</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-cloud-sun text-yellow-500 mr-2"></i>
                            Pronóstico del clima
                        </h2>
                        <div class="flex items-center">
                            <div class="text-5xl mr-4 text-blue-500">
                                <i id="icono-clima" class="fas fa-sun"></i>
                            </div>
                            <div>
                                <p id="temperatura" class="text-2xl font-bold">--°C</p>
                                <p id="condicion-clima" class="text-gray-600">Cargando...</p>
                                <p id="detalles-clima" class="text-sm text-gray-500">Viento: -- km/h • Humedad: --%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            <i class="fas fa-trophy text-purple-500 mr-2"></i>
                            Premios
                        </h2>
                        <ul id="premios-carrera" class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-medal text-yellow-500 mt-1 mr-2"></i>
                                <div>
                                    <p class="font-medium">Cargando premios...</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-md overflow-hidden detail-card">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        Horarios
                    </h2>
                    <ul id="horarios-carrera" class="space-y-4">
                        <li>
                            <p class="font-medium text-gray-700">Cargando horarios...</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden detail-card">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-handshake text-blue-500 mr-2"></i>
                        Patrocinadores
                    </h2>
                    <div id="patrocinadores" class="flex flex-wrap gap-4">
                        <p>Cargando patrocinadores...</p>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        ¿Quieres ser patrocinador? <a href="#" class="text-blue-600 hover:underline">Contáctanos</a>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden detail-card">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>
                        Requisitos
                    </h2>
                    <ul id="requisitos-carrera" class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Cargando requisitos...</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-auto" id="contacto">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <div class="md:w-1/3">
                    <div class="flex items-center space-x-3">
                        <img alt="Logo RunningWeb" class="w-10 h-10 rounded-full object-cover"
                            src="assets/img/icon-black.jpg" />
                        <h2 class="text-xl font-bold text-primary-600">RunningWeb</h2>
                    </div>
                    <p class="mt-2 text-gray-600">
                        La plataforma líder para la comunidad de runners. Conecta, entrena y mejora tu rendimiento.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 md:w-1/2">
                    <div class="text-center md:text-left">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Enlaces</h3>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="text-gray-600 hover:text-primary-600 block smooth-transition">Inicio</a>
                            <a href="#" class="text-gray-600 hover:text-primary-600 block smooth-transition">Eventos</a>
                            <a href="#"
                                class="text-gray-600 hover:text-primary-600 block smooth-transition">Comunidad</a>
                            <a href="#contacto"
                                class="text-gray-600 hover:text-primary-600 block smooth-transition">Contacto</a>
                        </div>
                    </div>

                    <div class="text-center md:text-left">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Contacto</h3>
                        <div class="mt-4 space-y-2">
                            <a href="webrunning008@gmail.com"
                                class="text-gray-600 hover:text-primary-600 block smooth-transition">
                                <i class="fas fa-envelope mr-2"></i> webrunning008@gmail.com
                            </a>
                            <a href="tel:+1234567890"
                                class="text-gray-600 hover:text-primary-600 block smooth-transition">
                                <i class="fas fa-phone mr-2"></i> +1 234 567 890
                            </a>

                            <div class="flex justify-center md:justify-start space-x-4 mt-3">
                                <a href="#" class="text-gray-600 hover:text-primary-600 smooth-transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-primary-600 smooth-transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-primary-600 smooth-transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-500 text-sm">
                <p>© 2024 RunningWeb. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="js/detalles.js"></script>
</body>

</html>