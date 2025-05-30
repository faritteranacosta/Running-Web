<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'corredor') {
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
    <title>Carrito de Compras - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
                        <a href="carrito.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-home mr-2"></i> Inicio
                        </a>
                        <a href="eventos.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-calendar-alt mr-2"></i> Eventos
                        </a>
                        <a href="carreras.php" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-running mr-2"></i> Carreras
                        </a>
                        <a href="catalogo_productos.php"
                            class="px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                            <i class="fas fa-store mr-2"></i> Productos
                        </a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="runner.php" class="flex items-center gap-10"  >
                        <p><?php echo htmlspecialchars($nombre)?> </p><img src="assets/img/milei.png" alt="Perfil" class="w-10 h-10 rounded-full border-2 border-white">
                    </a>
                    <div class="relative">
                        <a href="carrito.php" class="relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                                <span
                                    class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                                    style="display: none;">0
                                </span>
                        </a>
                    </div>
                    <button class="md:hidden text-white focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
            </div>

            <div class="md:hidden hidden mt-4 pb-2" id="mobile-menu">
                <a href="catalogo_productos.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="eventos.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-calendar-alt mr-2"></i> Eventos
                </a>
                <a href="carreras.php" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-running mr-2"></i> Carreras
                </a>
                <a href="catalogo_productos.php"
                    class="block px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                    <i class="fas fa-store mr-2"></i> Productos
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-shopping-cart text-blue-500 mr-2"></i>
                Mi Carrito
            </h1>
            <a href="catalogo_productos.php" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-1"></i> Seguir comprando
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">Productos seleccionados</h2>
                    </div>
                    
                    <div id="lista-carrito">

                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
                            <p>Tu carrito está vacío</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <div>
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-4">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">Resumen del pedido</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-medium">$00.0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Envío:</span>
                                <span id="envio" class="font-medium">$00.0</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4 flex justify-between">
                                <span class="text-lg font-semibold">Total:</span>
                                <span id="total" class="text-lg font-bold text-blue-600">$0.00</span>
                            </div>
                        </div>
                        
                        <button id="btn-pagar" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition duration-300">
                            Proceder al pago
                        </button>
                        
                        <p class="mt-4 text-sm text-gray-500 text-center">
                            <i class="fas fa-lock mr-1"></i> Pago seguro con encriptación SSL
                        </p>
                    </div>
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

    <script src="js/carrito.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>