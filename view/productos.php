<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'vendedor') {
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
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <title>Gestión de Productos - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar-collapsed {
            width: 80px;
        }

        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .user-name,
        .sidebar-collapsed .user-role,
        .sidebar-collapsed .logo-text {
            display: none;
        }

        .main-content {
            transition: margin-left 0.3s ease;
            flex: 1;
            overflow-y: auto;
            height: 100vh;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .active-nav {
            background-color: #3b82f6;
            color: white;
        }

        .active-nav i {
            color: white;
        }

        .nav-item {
            position: relative;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
        }

        .form-container {
            transition: all 0.3s ease;
            max-height: 1000px;
            overflow: hidden;
        }

        .form-container.collapsed {
            max-height: 70px;
        }

        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="sidebar bg-white shadow-lg flex flex-col h-full">
            <div class="p-4 flex items-center justify-between border-b">
                <div class="flex items-center">
                    <img src="assets/img/icon-black.jpg" alt="Logo" class="w-10 h-10 rounded-full">
                    <span class="ml-3 font-bold text-xl logo-text">RunningWeb</span>
                </div>
                <button id="toggle-sidebar" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="p-4 border-b flex items-center justify-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg transform transition-all duration-300 hover:scale-105 border-2 border-white relative overflow-hidden mr-3">
                    <span class="text-lg font-extrabold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.2);">
                        <?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?>
                    </span>
                    <div class="absolute inset-0 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -inset-1 bg-gradient-to-br from-blue-400 to-purple-500 opacity-30 blur-sm"></div>
                </div>
                <div>
                    <input type="text" id="userId" value="<?php echo htmlspecialchars($id); ?>" hidden>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold user-name"><?php echo htmlspecialchars($nombre); ?>
                        <?php echo htmlspecialchars($apellido); ?></h3>
                    <p class="text-sm text-gray-500 user-role"><?php echo htmlspecialchars($rol); ?></p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto">
                <ul class="p-2">
                    <li>
                        <a href="vendedor.php" class="flex items-center p-3 rounded-lg">
                            <i class="fas fa-tachometer-alt text-blue-500"></i>
                            <span class="nav-text ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-shopping-cart text-blue-500"></i>
                            <span class="nav-text ml-3">Ventas</span>
                            <span
                                class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">56</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-users text-blue-500"></i>
                            <span class="nav-text ml-3">Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            <span class="nav-text ml-3">Estadísticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-tags text-blue-500"></i>
                            <span class="nav-text ml-3">Promociones</span>
                        </a>
                    </li>
                    <li>
                        <a href="productos.php" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-store text-blue-500"></i>
                            <span class="nav-text ml-3">Mi Tienda</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-cog text-blue-500"></i>
                            <span class="nav-text ml-3">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t">
                <a href="../controller/action/act_logout.php"
                    class="flex items-center justify-center p-2 rounded-lg hover:bg-red-50 text-red-500">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text ml-3">Cerrar Sesión</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-boxes text-blue-500 mr-2"></i>
                    Gestión de Productos
                </h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span
                                class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-question-circle text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>

            <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
                <section class="gradient-bg text-white rounded-xl shadow-lg p-8 mb-10 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-heading font-bold mb-4 flex items-center">
                            <i class="fas fa-box-open mr-4"></i>
                            Gestión de Productos Deportivos
                        </h2>
                        <p class="text-lg md:text-xl max-w-2xl">
                            Administra fácilmente tu catálogo de productos para runners. Agrega, edita y organiza todo
                            en un
                            solo lugar.
                        </p>
                    </div>
                </section>

                <section class="bg-white rounded-xl shadow-md p-6 mb-12 card-hover">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-plus-circle mr-2 text-primary-600"></i>
                            Agregar nuevo producto
                        </h3>
                        <button id="toggle-form" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>

                    <form class="space-y-6" id="product-form" novalidate="">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="product-name">
                                    Nombre del producto <span class="text-primary-600">*</span>
                                </label>
                                <input
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                    id="product-name" name="productName" placeholder="Ej: Zapatillas de running"
                                    required type="text" />
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="product-category">
                                    Categoría <span class="text-primary-600">*</span>
                                </label>
                                <select
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition appearance-none"
                                    id="product-category" name="productCategory" required>
                                    <option disabled selected value="">Selecciona una categoría</option>
                                    <option value="zapatillas">Zapatillas</option>
                                    <option value="ropa">Ropa deportiva</option>
                                    <option value="accesorios">Accesorios</option>
                                    <option value="nutricion">Nutrición</option>
                                    <option value="tecnologia">Tecnología</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2" for="product-description">
                                Descripción <span class="text-primary-600">*</span>
                            </label>
                            <textarea
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 resize-y focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                id="product-description" name="productDescription"
                                placeholder="Descripción detallada del producto" required rows="4"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="product-price">
                                    Precio (COP) <span class="text-primary-600">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-8 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                        id="product-price" min="0" name="productPrice" placeholder="0.00" required
                                        step="0.01" type="number" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="product-stock">
                                    Stock disponible <span class="text-primary-600">*</span>
                                </label>
                                <input
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                    id="product-stock" min="0" name="productStock" placeholder="0" required
                                    type="number" />
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="product-image">
                                    Imagen del producto <span class="text-primary-600">*</span>
                                </label>
                                <input
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition"
                                    id="product-image" name="productImage" placeholder="URL de la imagen" required
                                    type="url" />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-2">
                            <button type="button" onclick="clearForm()"
                                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 smooth-transition">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </button>
                            <button type="submit " id="save-product"
                                class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium smooth-transition">
                                <i class="fas fa-save mr-2"></i> Guardar Producto
                            </button>
                        </div>
                    </form>
                </section>

                <section class="mb-16 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-boxes mr-2 text-primary-600"></i>
                            Productos publicados
                        </h3>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" placeholder="Buscar productos..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 smooth-transition">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button
                                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg smooth-transition">
                                <i class="fas fa-filter mr-1"></i> Filtrar
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-primary-600 text-white">
                                    <tr>
                                        <th class="py-4 px-6 text-left rounded-tl-xl">Producto</th>
                                        <th class="py-4 px-6 text-left">Categoría</th>
                                        <th class="py-4 px-6 text-left">Precio</th>
                                        <th class="py-4 px-6 text-left">Stock</th>
                                        <th class="py-4 px-6 text-left">Estado</th>
                                        <th class="py-4 px-6 text-center rounded-tr-xl">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="product-list">
                                    <!-- Los productos se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    Mostrando <span id="showing-from" class="font-medium">1</span> a
                                    <span id="showing-to" class="font-medium">5</span> de
                                    <span id="total-items" class="font-medium">0</span> productos
                                </div>
                                <div class="flex space-x-2" id="pagination-controls">
                                    <button onclick="previousPage()"
                                        class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50"
                                        id="prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button onclick="nextPage()"
                                        class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 disabled:opacity-50"
                                        id="next-btn">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
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
                                La plataforma líder para la comunidad de runners. Conecta, entrena y mejora tu
                                rendimiento.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8 md:w-1/2">
                            <div class="text-center md:text-left">
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Enlaces</h3>
                                <div class="mt-4 space-y-2">
                                    <a href="#"
                                        class="text-gray-600 hover:text-primary-600 block smooth-transition">Inicio</a>
                                    <a href="#"
                                        class="text-gray-600 hover:text-primary-600 block smooth-transition">Eventos</a>
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
        </div>
    </div>

    <script src="js/productos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>