<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'vendedor') {
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
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <title>Panel de Vendedor | RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: all 0.3s;
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
        .dashboard-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .active-nav {
            background-color: #3b82f6;
            color: white;
        }
        .active-nav i {
            color: white;
        }
        .product-table th {
            position: sticky;
            top: 0;
            background-color: #f8fafc;
            z-index: 10;
        }

    </style>
</head>
<body class="bg-gray-100">
    <!-- Contenedor Principal -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-white shadow-lg flex flex-col h-full">
            <!-- Logo y Toggle -->
            <div class="p-4 flex items-center justify-between border-b">
                <div class="flex items-center">
                    <img src="assets/img/icon-black.jpg" alt="Logo" class="w-10 h-10 rounded-full">
                    <span class="ml-3 font-bold text-xl logo-text">RunningWeb</span>
                </div>
                <button id="toggle-sidebar" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Perfil del Administrador -->
            <div class="p-4 border-b flex items-center justify-center">
                <div class="relative">
                    <img src="assets/img/milei.png" alt="Perfil"
                        class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                </div>
                <div>
                    <input type="text" id="userId" value="<?php echo htmlspecialchars($id);?>" hidden>
                </div>
                <div class="ml-3">
                    <h3 class="font-semibold user-name"><?php echo htmlspecialchars($nombre);?> <?php echo htmlspecialchars($apellido);?></h3>
                    <p class="text-sm text-gray-500 user-role"><?php echo htmlspecialchars($rol);?></p>
                </div>
            </div>
            
            <!-- Menú de Navegación -->
            <nav class="flex-1 overflow-y-auto">
                <ul class="p-2">
                    <li>
                        <a href="vendedor.php" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-tachometer-alt text-blue-500"></i>
                            <span class="nav-text ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-shopping-cart text-blue-500"></i>
                            <span class="nav-text ml-3">Ventas</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">56</span>
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
                        <a href="productos.php" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
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
            
            <!-- Cerrar Sesión -->
            <div class="p-4 border-t">
                <a href="../controller/action/act_logout.php" class="flex items-center justify-center p-2 rounded-lg hover:bg-red-50 text-red-500">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text ml-3">Cerrar Sesión</span>
                </a>
            </div>
        </div>
        
        <!-- Contenido Principal -->
        <div class="main-content flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Panel de Vendedor</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                        </button>
                    </div>
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-question-circle text-xl"></i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Contenido -->
            <main class="p-6">
                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-dollar-sign text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Ventas Hoy</p>
                                <h3 class="text-2xl font-bold">$1,245</h3>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 12% vs ayer
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Órdenes</p>
                                <h3 class="text-2xl font-bold">18</h3>
                                <p class="text-sm text-green-500 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i> 3 nuevas
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-box-open text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Productos</p>
                                <h3 class="text-2xl font-bold">24</h3>
                                <p class="text-sm text-red-500 mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i> 2 bajos en stock
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-star text-purple-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Valoración</p>
                                <h3 class="text-2xl font-bold">4.7/5</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-comment-alt mr-1"></i> 56 reseñas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gráficos y Tablas -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Gráfico de Ventas -->
                    <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Ventas Mensuales</h2>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-sm">Mes</button>
                                <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm">Año</button>
                            </div>
                        </div>
                        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                            <p class="text-gray-400">Gráfico de ventas interactivo aparecerá aquí</p>
                        </div>
                    </div>
                    
                    <!-- Órdenes Recientes -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Órdenes Recientes</h2>
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                Ver todas
                            </a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 border-b">
                                <div>
                                    <p class="font-medium">#ORD-45612</p>
                                    <p class="text-sm text-gray-500">Hace 2 horas</p>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">Completada</span>
                            </div>
                            <div class="flex items-center justify-between p-3 border-b">
                                <div>
                                    <p class="font-medium">#ORD-45611</p>
                                    <p class="text-sm text-gray-500">Hace 5 horas</p>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">En proceso</span>
                            </div>
                            <div class="flex items-center justify-between p-3 border-b">
                                <div>
                                    <p class="font-medium">#ORD-45610</p>
                                    <p class="text-sm text-gray-500">Hace 1 día</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">Enviado</span>
                            </div>
                            <div class="flex items-center justify-between p-3">
                                <div>
                                    <p class="font-medium">#ORD-45609</p>
                                    <p class="text-sm text-gray-500">Hace 1 día</p>
                                </div>
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded">Pendiente</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Productos -->
                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Mis Productos</h2>
                        <a href= "productos.php" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Nuevo Producto
                        </a>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 product-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ventas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                        </tbody>
                    </table>
                    <div class="overflow-x-auto">
                        
                    </div>
                </div>
                
                <!-- Reseñas Recientes -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Reseñas Recientes</h2>
                        <a href="#" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            Ver todas
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 border rounded-lg">
                            <div class="flex items-center mb-2">
                                <img src="assets/img/user1.jpg" alt="Usuario" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <h4 class="font-medium">Carlos Méndez</h4>
                                    <div class="flex items-center">
                                        <div class="text-yellow-400">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">Hace 2 días</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600">
                                "Excelentes zapatillas, muy cómodas para correr largas distancias. El envío fue rápido y el producto llegó en perfectas condiciones."
                            </p>
                        </div>
                        <div class="p-4 border rounded-lg">
                            <div class="flex items-center mb-2">
                                <img src="assets/img/user2.jpg" alt="Usuario" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <h4 class="font-medium">Ana Rodríguez</h4>
                                    <div class="flex items-center">
                                        <div class="text-yellow-400">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">Hace 5 días</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600">
                                "El reloj GPS funciona muy bien, pero la batería podría durar más. En general buena compra, el vendedor respondió rápido mis dudas."
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="js/productosVendedor.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
            document.querySelector('.main-content').classList.toggle('ml-20');
        });        
    </script>
</body>
</html>