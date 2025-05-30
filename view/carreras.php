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
    <title>Carreras - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/carreras.css">
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

        .event-card {
            transition: all 0.3s;
            background-size: cover;
            background-position: center;
        }

        .event-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .contenedor {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="index.html" class="flex items-center space-x-3">
                        <img src="assets/img/icon-black.jpg" alt="Logo" class="w-12 h-12 rounded-full border-2 border-white">
                        <h1 class="text-2xl font-bold">RunningWeb</h1>
                    </a>

                    <nav class="hidden md:flex space-x-1">
                        <a href="carreras.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-home mr-2"></i> Inicio
                        </a>
                        <a href="eventos.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-calendar-alt mr-2"></i> Eventos
                        </a>
                        <a href="carreras.php" class="px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                            <i class="fas fa-running mr-2"></i> Carreras
                        </a>
                        <a href="catalogo_productos.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-store mr-2"></i> Productos
                        </a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="runner.php" class="flex items-center gap-10">
                        <p><?php echo htmlspecialchars($nombre) ?> </p>
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg transform transition-all duration-300 hover:scale-105 border-2 border-white relative overflow-hidden mr-3">
                            <span class="text-lg font-extrabold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.2);">
                                <?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?>
                            </span>
                            <div class="absolute inset-0 bg-white opacity-10 rounded-full"></div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-blue-400 to-purple-500 opacity-30 blur-sm"></div>
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
                <a href="carreras.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                    <i class="fas fa-running mr-2"></i> Carreras
                </a>
                <a href="catalogo_productos.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-store mr-2"></i> Productos
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-running text-blue-500 mr-3"></i>
                Carreras Disponibles
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Encuentra las mejores carreras para demostrar tu potencial y superar tus límites
            </p>
        </div>

        <section class="mb-16">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-star text-yellow-400 mr-2"></i>
                Carrera Destacada
            </h2>
            <div class="event-card bg-white rounded-xl shadow-lg overflow-hidden card-hover" id="carrera-destacada">
                <!-- Contenido dinámico desde carreras.js -->
                <div class="flex items-center justify-center h-64 bg-gray-100">
                    <p class="text-gray-500">Cargando carrera destacada...</p>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-list-ul text-blue-500 mr-2"></i>
                Más Carreras
            </h2>
            <div class="contenedor" id="lista-carreras">
                <!-- Contenido dinámico desde carreras.js -->
                <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                    <p class="text-gray-500">Cargando carreras...</p>
                </div>
            </div>
        </section>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="js/carreras.js"></script>
    <script>
        // Toggle Mobile Menu
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>