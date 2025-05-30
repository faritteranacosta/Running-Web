<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'admin') {
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
    <link rel="shortcut icon" href="assets/img/icon.ico" type="image/x-icon">
    <title>Panel de Administración - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/crear_carrera.css">
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
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .form-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-gray-50 flex">
    <!-- Sidebar (mantiene el código existente) -->
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
            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg transform transition-all duration-300 hover:scale-105 border-2 border-white relative overflow-hidden mr-3">
                    <span class="text-lg font-extrabold" style="text-shadow: 0px 1px 2px rgba(0,0,0,0.2);">
                        <?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?>
                    </span>
                    <div class="absolute inset-0 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute -inset-1 bg-gradient-to-br from-blue-400 to-purple-500 opacity-30 blur-sm"></div>
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
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('carreras', event)">
                        <i class="fas fa-running text-blue-500"></i>
                        <span class="nav-text ml-3">Carreras</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('usuarios', event)">
                        <i class="fas fa-users text-blue-500"></i>
                        <span class="nav-text ml-3">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 text-gray-700" onclick="showTab('productos', event)">
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
            <a href="../controller/action/act_logout.php" class="flex items-center justify-center p-2 rounded-lg hover:bg-red-50 text-red-500">
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
            <!-- Dashboard (mantiene el código existente) -->
            <div id="dashboard" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold mb-4">Resumen General</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span>Usuarios registrados</span>
                                <span id="nUsuarios" class="font-bold">0</span>
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
                    <button onclick="showEventForm()" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-1"></i> Nuevo Evento
                    </button>
                </div>

                <!-- Formulario de Evento (oculto inicialmente) -->
                <div id="event-form" class="bg-white rounded-xl shadow-md p-6 mb-8 form-card hidden">
                    <h3 class="text-xl font-semibold mb-4">Crear Nuevo Evento</h3>
                    <form onsubmit="manejarFormularioEvento(event)" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nombre del Evento *</label>
                                <input type="text" name="nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ej: Maratón Ciudad 2023" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tipo de Evento *</label>
                                <select name="tipo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="carrera">Carrera</option>
                                    <option value="entrenamiento">Entrenamiento</option>
                                    <option value="charla">Charla/Taller</option>
                                    <option value="competencia">Competencia</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Fecha *</label>
                                <input type="date" name="fecha" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Hora *</label>
                                <input type="time" name="hora" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Descripción *</label>
                            <textarea name="descripcion" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical" rows="4" placeholder="Descripción detallada del evento, requisitos, premios, etc." required></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Ubicación (ID) *</label>
                            <input type="number" name="ubicacion_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ingrese el ID numérico de la ubicación" required>
                            <p class="text-sm text-gray-500 mt-1">Debe ser el ID numérico de la ubicación</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Patrocinador (ID) *</label>
                            <input type="number" name="id_patrocinador" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ingrese el ID numérico del patrocinador" required>
                            <p class="text-sm text-gray-500 mt-1">Debe ser el ID numérico del patrocinador</p>
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" onclick="hideEventForm()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-1"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Eventos -->
                <div id="eventos-table-container">
                    <!-- La tabla se cargará aquí dinámicamente -->
                    <div class="bg-white rounded-xl shadow-md p-8 text-center">
                        <div class="loading mx-auto mb-4"></div>
                        <p class="text-gray-500">Cargando eventos...</p>
                    </div>
                </div>
            </div>

            <!-- Otras secciones (mantienen el código existente) -->
            <div id="productos" class="tab-content">
                <div id="productos-table-container" class="flex justify-center mt-8"></div>
            </div>

            <div id="usuarios" class="tab-content">
                <div id="usuarios-table-container" class="flex justify-center mt-8"></div>
            </div>


            <div id="carreras" class="tab-content">
                <div class="form-container">
                    <h2 class="form-title">GESTIÓN DE CARRERAS</h2>

                    <form id="formCarrera" method="POST" class="form-carrera">
                        <div class="form-section">
                            <h3 class="section-title">DATOS DEL EVENTO</h3>

                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del Evento" required>
                                </div>

                                <div class="form-group">
                                    <input type="date" id="fecha" name="fecha" required>
                                </div>

                                <div class="form-group">
                                    <input type="time" id="hora" name="hora" required>
                                </div>

                                <div class="form-group full-width">
                                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción" required></textarea>
                                </div>

                                <div class="form-group full-width">
                                    <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">DATOS DE LA CARRERA</h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <select id="categoriaSelect" name="idCategoria" required>
                                        <option value="">Categoría</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <select id="tipoCarreraSelect" name="idTipoCarrera" required>
                                        <option value="">Tipo de Carrera</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="number" step="0.01" id="distancia" name="distancia" placeholder="Distancia (km)" required>
                                </div>

                                <div class="form-group ruta-field">
                                    <div class="input-group">
                                        <input type="number" id="idRuta" name="idRuta" placeholder="ID Ruta" required readonly>
                                        <button type="button" id="btnCrearRuta" class="btn-ruta">CREAR RUTA</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">GUARDAR CARRERA</button>
                        </div>
                    </form>
                </div>

                <script>
                    let ventanaRuta = null;

                    document.getElementById('btnCrearRuta').addEventListener('click', function(e) {
                        e.preventDefault();

                        const width = Math.min(window.screen.availWidth, 1200);
                        const height = window.screen.availHeight;
                        const left = (window.screen.width - width) / 2;

                        const ventanaRuta = window.open(
                            '../view/crear_ruta.php?from_form=1',
                            'CrearRuta',
                            `width=${screen.width},height=${screen.height},left=0,top=0,scrollbars=yes`
                        );
                    });

                    // Agrega el listener solo una vez
                    window.addEventListener('message', function(event) {
                        if (event.data && event.data.type === 'rutaCreada') {
                            document.getElementById('idRuta').value = event.data.id_ruta;
                            if (ventanaRuta && !ventanaRuta.closed) {
                                ventanaRuta.close();
                            }
                        }
                    });
                </script>
            </div>

            <!-- Reportes -->
            <div id="reportes" class="tab-content">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Reportes y Estadísticas</h2>
                </div>
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <p class="text-gray-500">Funcionalidad en desarrollo</p>
                </div>
            </div>

            <!-- Configuración -->
            <div id="configuracion" class="tab-content">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Configuración del Sistema</h2>
                </div>
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <p class="text-gray-500">Funcionalidad en desarrollo</p>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Toggle Sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
            document.querySelector('.main-content').classList.toggle('ml-20');
        });

        // Mostrar/ocultar pestañas
        function showTab(tabId, event) {
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
            // Solo si event existe (es decir, si viene de un click)
            if (event && event.currentTarget) {
                event.currentTarget.classList.add('active-nav');
            }

            // Llamar a las funciones específicas según la pestaña
            if (tabId === 'usuarios') {
                if (typeof mostrarUsuarios === 'function') {
                    mostrarUsuarios();
                }
            } else if (tabId === 'productos') {
                if (typeof mostrarProductos === 'function') {
                    mostrarProductos();
                }

            } else if (tabId === 'eventos') {
                if (typeof mostrarEventos === 'function') {
                    mostrarEventos();

                } else if (tabId === 'carreras') {
                    if (typeof mostrarCarreras === 'function') {
                        mostrarCarreras();
                    }
                }
            }else if(tabId === 'dashboard') {
                totalUsuarios();
            }

            // Mostrar/ocultar formulario de evento
            function showEventForm() {
                const form = document.getElementById('event-form');
                form.classList.remove('hidden');
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            function hideEventForm() {
                document.getElementById('event-form').classList.add('hidden');
                // Limpiar el formulario
                document.querySelector('#event-form form').reset();
            }

            document.addEventListener('DOMContentLoaded', function() {
                showTab('dashboard');
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/administradorUsuarios.js"></script>
    <script src="js/administradorProductos.js"></script>
    <script src="js/administradorEventos.js"></script>
    <script src="js/administradorCarreras.js"></script>
</body>

</html>