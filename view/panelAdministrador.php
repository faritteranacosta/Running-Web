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
    <link rel="shortcut icon" href="assets/img/icon.ico" type="image/x-icon">
    <title>Panel de Administración - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: all 0.3s ease;
            height: 100vh;
            display: flex;
            flex-direction: column;
            width: 260px;
        }
        .sidebar-collapsed {
            width: 70px;
        }
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .user-name,
        .sidebar-collapsed .user-role,
        .sidebar-collapsed .logo-text {
            display: none;
        }
        .sidebar-collapsed .notification-badge {
            position: absolute;
            right: 5px;
            top: 5px;
        }
        .main-content {
            transition: margin-left 0.3s ease;
            flex: 1;
            overflow-y: auto;
            height: 100vh;
        }
        .active-nav {
            background-color: #0ea5e9;
            color: white;
        }
        .active-nav i {
            color: white;
        }
        .nav-item {
            position: relative;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .form-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex">
    <!-- Sidebar -->
    <div class="sidebar bg-white shadow-lg">
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
                <span class="notification-badge bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
            </div>
            <div class="ml-3">
                <h3 class="font-semibold user-name">Admin RunningPro</h3>
                <p class="text-sm text-gray-500 user-role">Administrador</p>
            </div>
        </div>

        <!-- Menú de Navegación -->
        <nav class="flex-1 overflow-y-auto">
            <ul class="p-2">
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('dashboard')">
                        <i class="fas fa-tachometer-alt text-blue-500"></i>
                        <span class="nav-text ml-3">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg active-nav" onclick="showTab('eventos')">
                        <i class="fas fa-calendar-alt text-blue-500"></i>
                        <span class="nav-text ml-3">Eventos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('carreras')">
                        <i class="fas fa-running text-blue-500"></i>
                        <span class="nav-text ml-3">Carreras</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('usuarios')">
                        <i class="fas fa-users text-blue-500"></i>
                        <span class="nav-text ml-3">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('productos')">
                        <i class="fas fa-boxes text-blue-500"></i>
                        <span class="nav-text ml-3">Productos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('reportes')">
                        <i class="fas fa-chart-line text-blue-500"></i>
                        <span class="nav-text ml-3">Reportes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('configuracion')">
                        <i class="fas fa-cog text-blue-500"></i>
                        <span class="nav-text ml-3">Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Cerrar Sesión -->
        <div class="p-4 border-t">
            <a href="logout.html" class="flex items-center justify-center p-2 rounded-lg hover:bg-red-50 text-red-500">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text ml-3">Cerrar Sesión</span>
            </a>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800" id="tab-title">Panel de Administración</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                    </button>
                </div>
                <div class="relative">
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-question-circle text-xl"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Contenido Dinámico -->
        <main class="p-6">
            <!-- Dashboard -->
            <div id="dashboard" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Resumen General</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span>Usuarios registrados</span>
                                <span class="font-bold">1,245</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Eventos activos</span>
                                <span class="font-bold">18</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Carreras programadas</span>
                                <span class="font-bold">32</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Actividad Reciente</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-user-plus text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm">25 nuevos usuarios esta semana</p>
                                    <p class="text-xs text-gray-500">Hace 2 horas</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-calendar-plus text-green-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm">Nuevo evento creado: "Maratón Primavera 2023"</p>
                                    <p class="text-xs text-gray-500">Ayer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Estadísticas Rápidas</h3>
                        <div class="h-48 flex items-center justify-center">
                            <p class="text-gray-400">Gráfico de estadísticas aparecerá aquí</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Eventos -->
            <div id="eventos" class="tab-content active">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Gestión de Eventos</h2>
                    <button onclick="showEventForm()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                        <i class="fas fa-plus mr-1"></i> Nuevo Evento
                    </button>
                </div>

                <!-- Formulario de Evento (oculto inicialmente) -->
                <div id="event-form" class="bg-white rounded-xl shadow-md p-6 mb-8 form-card hidden">
                    <h3 class="text-xl font-semibold mb-4">Crear Nuevo Evento</h3>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nombre del Evento *</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Maratón Ciudad 2023" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tipo de Evento *</label>
                                <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="carrera">Carrera</option>
                                    <option value="entrenamiento">Entrenamiento</option>
                                    <option value="charla">Charla/Taller</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Fecha de Inicio *</label>
                                <input type="date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Fecha de Fin</label>
                                <input type="date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Hora *</label>
                                <input type="time" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Descripción *</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Descripción detallada del evento" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Ubicación *</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Dirección o lugar del evento" required>
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="hideEventForm()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg">
                                Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Eventos -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium">Maratón Primavera 2023</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Carrera</td>
                                    <td class="px-6 py-4 whitespace-nowrap">15 Oct 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Parque Central</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2">Editar</button>
                                        <button class="text-red-500 hover:text-red-700">Eliminar</button>
                                    </td>
                                </tr>
                                <!-- Más eventos... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Otras secciones (Carreras, Usuarios, etc.) -->
            <div id="carreras" class="tab-content">
                <!-- Contenido de gestión de carreras -->
            </div>
            
            <div id="usuarios" class="tab-content">
                <!-- Contenido de gestión de usuarios -->
            </div>
            
            <!-- ... otras pestañas ... -->
            
        </main>
    </div>

    <script>
        // Toggle Sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
            document.querySelector('.main-content').classList.toggle('ml-20');
        });

        // Mostrar/ocultar pestañas
        function showTab(tabId) {
            // Ocultar todas las pestañas
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Mostrar la pestaña seleccionada
            document.getElementById(tabId).classList.add('active');
            
            // Actualizar título
            const tabTitles = {
                'dashboard': 'Dashboard',
                'eventos': 'Gestión de Eventos',
                'carreras': 'Gestión de Carreras',
                'usuarios': 'Gestión de Usuarios',
                'productos': 'Gestión de Productos',
                'reportes': 'Reportes y Estadísticas',
                'configuracion': 'Configuración del Sistema'
            };
            document.getElementById('tab-title').textContent = tabTitles[tabId];
            
            // Actualizar menú activo
            document.querySelectorAll('.nav-item a').forEach(item => {
                item.classList.remove('active-nav');
            });
            event.currentTarget.classList.add('active-nav');
        }

        // Mostrar/ocultar formulario de evento
        function showEventForm() {
            document.getElementById('event-form').classList.remove('hidden');
        }
        
        function hideEventForm() {
            document.getElementById('event-form').classList.add('hidden');
        }

        // Inicializar con la pestaña de eventos visible
        document.addEventListener('DOMContentLoaded', function() {
            showTab('eventos');
        });
    </script>
</body>
</html>