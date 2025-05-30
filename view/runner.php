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
    <title>Perfil del Corredor | RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <style>
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-proximo {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .status-en-curso {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-finalizado {
            background-color: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

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

        .main-content {
            transition: margin-left 0.3s;
        }

        .active-nav {
            background-color: #3b82f6;
            color: white;
        }

        .active-nav i {
            color: white;
        }

        .stats-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
                <div class="ml-3">
                    <h3 class="font-semibold user-name"><?php echo htmlspecialchars($nombre); ?>
                        <?php echo htmlspecialchars($apellido); ?></h3>
                    <p class="text-sm text-gray-500 user-role"><?php echo htmlspecialchars($rol); ?></p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto">
                <ul class="p-2">
                    <li class="nav-item">
                        <a href="#" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-tachometer-alt text-blue-500"></i>
                            <span class="nav-text ml-3">Dashboard</span>
                            <span
                                class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full notification-badge">24</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="catalogo_productos.php"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-boxes text-blue-500"></i>
                            <span class="nav-text ml-3">Productos</span>
                        </a>
                    <li class="nav-item">
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-user-friends text-blue-500"></i>
                            <span class="nav-text ml-3">Equipos</span>
                        </a>
                    <li class="nav-item">
                        <a href="carreras.php" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-running text-blue-500"></i>
                            <span class="nav-text ml-3">Carreras</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="eventos.php" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                            <span class="nav-text ml-3">Eventos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-chart-line text-blue-500"></i>
                            <span class="nav-text ml-3">Estadísticas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700">
                            <i class="fas fa-cog text-blue-500"></i>
                            <span class="nav-text ml-3">Configuración</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t">
                <a href="../controller/action/act_logout.php" class="flex items-center justify-center p-2 rounded-lg hover:bg-red-50 text-red-500">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text ml-3">Cerrar Sesión</span>
                </a>
            </div>
        </div>

        <div class="main-content flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Mi Perfil de Corredor</h1>
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
                            <i class="fas fa-envelope text-xl"></i>
                            <span
                                class="absolute top-0 right-0 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                        </button>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="stats-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-medal text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Carreras</p>
                                <h3 id="total-races" class="text-2xl font-bold">12</h3>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-road text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">KM Totales</p>
                                <h3 id="total-km" class="text-2xl font-bold">156</h3>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-stopwatch text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Mejor Tiempo</p>
                                <h3 id="best-time" class="text-2xl font-bold">42:15</h3>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-trophy text-purple-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Logros</p>
                                <h3 id="total-achievements" class="text-2xl font-bold">8/15</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow lg:col-span-1">
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Información Personal</h2>
                        <div class="flex flex-col items-center mb-4">
                            <img id="profile-avatar" src="assets/img/milei.png" alt="Perfil"
                                class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 mb-4">
                            <button class="text-blue-500 text-sm hover:text-blue-700">
                                <i class="fas fa-camera mr-1"></i> Cambiar foto
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Nombre completo</p>
                                <p id="full-name" class="font-medium"><?php echo htmlspecialchars($nombre); ?>
                                    <?php echo htmlspecialchars($apellido); ?> </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Correo electrónico</p>
                                <p id="user-email" class="font-medium"><?php echo htmlspecialchars($correo); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha de nacimiento</p>
                                <p id="register-date" class="font-medium">
                                    <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha de registro</p>
                                <p id="register-date" class="font-medium">
                                    <?php echo htmlspecialchars($fecha_registro); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nivel</p>
                                <div class="flex items-center">
                                    <span id="user-level" class="font-medium mr-2">Principiante</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div id="level-progress" class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: 45%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                            Editar Perfil
                        </button>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Mis Carreras Programadas</h2>
                            <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i> Agregar carrera
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Carrera</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Distancia(KM)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="races-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Las carreras se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <h2 class="text-xl font-bold mt-8 mb-4 text-gray-800">Próximos Eventos</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="events-container">
                            <!-- Los eventos se cargarán aquí dinámicamente -->
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Productos Recomendados</h2>
                        <a href="catalogo_productos.php" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            Ver todos <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6" id="products-container">
                        <!-- Los productos se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/runner.js"></script>
</body>

</html>