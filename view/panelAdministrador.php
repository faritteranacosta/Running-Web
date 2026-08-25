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
<title>Panel de administración — RunningWeb</title>
<link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/vendedor.css">
<link rel="stylesheet" href="css/productos.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="css/crear_carrera.css">
</head>
<body class="dash">

<div class="dash-shell">

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-head">
      <a href="index.html" class="sidebar-brand">
        <img src="assets/img/icon-black.jpg" alt="RunningWeb">
        <span class="hide-on-collapse">RunningWeb</span>
      </a>
      <button class="sidebar-toggle" id="toggle-sidebar" aria-label="Colapsar menú">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <div class="sidebar-user">
      <div class="avatar-chip"><?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?></div>
      <div class="hide-on-collapse">
        <h3><?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?></h3>
        <p><?php echo htmlspecialchars($rol); ?></p>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="#" onclick="showTab('dashboard', event); return false;" data-tab="dashboard">
        <i class="fas fa-gauge-high"></i> <span class="hide-on-collapse">Dashboard</span>
      </a>
      <a href="#" onclick="showTab('eventos', event); return false;" data-tab="eventos" class="active">
        <i class="fas fa-calendar-alt"></i> <span class="hide-on-collapse">Eventos</span>
      </a>
      <a href="#" onclick="showTab('carreras', event); return false;" data-tab="carreras">
        <i class="fas fa-person-running"></i> <span class="hide-on-collapse">Carreras</span>
      </a>
      <a href="#" onclick="showTab('usuarios', event); return false;" data-tab="usuarios">
        <i class="fas fa-users"></i> <span class="hide-on-collapse">Usuarios</span>
      </a>
      <a href="#" onclick="showTab('productos', event); return false;" data-tab="productos">
        <i class="fas fa-boxes"></i> <span class="hide-on-collapse">Productos</span>
      </a>
      <a href="#" onclick="showTab('reportes', event); return false;" data-tab="reportes">
        <i class="fas fa-chart-line"></i> <span class="hide-on-collapse">Reportes</span>
      </a>
      <a href="#" onclick="showTab('configuracion', event); return false;" data-tab="configuracion">
        <i class="fas fa-gear"></i> <span class="hide-on-collapse">Configuración</span>
      </a>
    </nav>

    <div class="sidebar-foot">
      <a href="../controller/action/act_logout.php">
        <i class="fas fa-arrow-right-from-bracket"></i> <span class="hide-on-collapse">Cerrar sesión</span>
      </a>
    </div>
  </aside>

  <div class="dash-main">
    <header class="dash-topbar">
      <h1 id="tab-title">Gestión de eventos</h1>
      <div class="topbar-actions">
        <button class="icon-btn" aria-label="Notificaciones">
          <i class="fas fa-bell"></i><span class="dot">3</span>
        </button>
        <button class="icon-btn" aria-label="Ayuda">
          <i class="fas fa-circle-question"></i>
        </button>
      </div>
    </header>

    <main class="dash-content">

      <!-- Dashboard -->
      <div id="dashboard" class="tab-content">
        <div class="admin-stats-grid">
          <div class="panel">
            <h2 class="admin-panel-title">Resumen general</h2>
            <div class="admin-summary-row"><span>Usuarios registrados</span><span class="num" id="nUsuarios">0</span></div>
            <div class="admin-summary-row"><span>Eventos activos</span><span class="num">18</span></div>
            <div class="admin-summary-row"><span>Carreras programadas</span><span class="num">32</span></div>
          </div>
          <div class="panel">
            <h2 class="admin-panel-title">Actividad reciente</h2>
            <div class="activity-row">
              <div class="activity-icon sky"><i class="fas fa-user-plus"></i></div>
              <div><p>25 nuevos usuarios esta semana</p><p class="when">Hace 2 horas</p></div>
            </div>
            <div class="activity-row">
              <div class="activity-icon green"><i class="fas fa-calendar-plus"></i></div>
              <div><p>Nuevo evento creado: "Maratón Primavera"</p><p class="when">Ayer</p></div>
            </div>
          </div>
          <div class="panel">
            <h2 class="admin-panel-title">Estadísticas rápidas</h2>
            <div class="chart-placeholder admin-chart-placeholder">Gráfico de estadísticas aparecerá aquí</div>
          </div>
        </div>
      </div>

      <!-- Gestión de Eventos -->
      <div id="eventos" class="tab-content active">
        <div class="tab-head">
          <h2>Gestión de eventos</h2>
          <button onclick="showEventForm()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo evento
          </button>
        </div>

        <div id="event-form" class="panel hidden">
          <h3 class="admin-panel-title">Crear nuevo evento</h3>
          <form onsubmit="manejarFormularioEvento(event)">
            <div class="event-form-grid">
              <div class="input-box">
                <label>Nombre del evento <span class="req">*</span></label>
                <input type="text" name="nombre" placeholder="Ej: Maratón Ciudad 2026" required>
              </div>
              <div class="input-box">
                <label>Tipo de evento <span class="req">*</span></label>
                <select name="tipo" required>
                  <option value="">Seleccionar tipo</option>
                  <option value="carrera">Carrera</option>
                  <option value="entrenamiento">Entrenamiento</option>
                  <option value="charla">Charla/Taller</option>
                  <option value="competencia">Competencia</option>
                </select>
              </div>
            </div>

            <div class="event-form-grid">
              <div class="input-box">
                <label>Fecha <span class="req">*</span></label>
                <input type="date" name="fecha" required>
              </div>
              <div class="input-box">
                <label>Hora <span class="req">*</span></label>
                <input type="time" name="hora" required>
              </div>
            </div>

            <div class="input-box">
              <label>Descripción <span class="req">*</span></label>
              <textarea name="descripcion" rows="4" placeholder="Descripción detallada del evento, requisitos, premios, etc." required></textarea>
            </div>

            <div class="input-box">
              <label>Ubicación (ID) <span class="req">*</span></label>
              <input type="number" name="ubicacion_id" placeholder="Ingresa el ID numérico de la ubicación" required>
              <p class="field-hint">Debe ser el ID numérico de una ubicación ya registrada.</p>
            </div>

            <div class="input-box">
              <label>Patrocinador (ID) <span class="req">*</span></label>
              <input type="number" name="id_patrocinador" placeholder="Ingresa el ID numérico del patrocinador" required>
              <p class="field-hint">Debe ser el ID numérico de un patrocinador ya registrado.</p>
            </div>

            <div class="form-actions">
              <button type="button" onclick="hideEventForm()" class="btn btn-ghost">Cancelar</button>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar evento</button>
            </div>
          </form>
        </div>

        <div id="eventos-table-container">
          <div class="empty-panel">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Cargando eventos...</p>
          </div>
        </div>
      </div>

      <!-- Gestión de Productos -->
      <div id="productos" class="tab-content">
        <div class="tab-head"><h2>Gestión de productos</h2></div>
        <div id="productos-table-container"></div>
      </div>

      <!-- Gestión de Usuarios -->
      <div id="usuarios" class="tab-content">
        <div class="tab-head"><h2>Gestión de usuarios</h2></div>
        <div id="usuarios-table-container"></div>
      </div>

      <!-- Gestión de Carreras -->
      <div id="carreras" class="tab-content">
        <div class="panel admin-carrera-panel">
          <form id="formCarrera" method="POST" class="form-carrera">
            <div class="form-container">
              <h2 class="form-title">Gestión de carreras</h2>

              <div class="form-section">
                <h3 class="section-title">Datos del evento</h3>
                <div class="form-grid">
                  <div class="form-group full-width">
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del evento" required>
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
                <h3 class="section-title">Datos de la carrera</h3>
                <div class="form-grid">
                  <div class="form-group">
                    <select id="categoriaSelect" name="idCategoria" required>
                      <option value="">Categoría</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <select id="tipoCarreraSelect" name="idTipoCarrera" required>
                      <option value="">Tipo de carrera</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="number" step="0.01" id="distancia" name="distancia" placeholder="Distancia (km)" required>
                  </div>
                  <div class="form-group ruta-field">
                    <div class="input-group">
                      <input type="number" id="idRuta" name="idRuta" placeholder="ID Ruta" required readonly>
                      <button type="button" id="btnCrearRuta" class="btn-ruta">Crear ruta</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-submit">Guardar carrera</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Reportes -->
      <div id="reportes" class="tab-content">
        <div class="tab-head"><h2>Reportes y estadísticas</h2></div>
        <div class="empty-panel">Funcionalidad en desarrollo</div>
      </div>

      <!-- Configuración -->
      <div id="configuracion" class="tab-content">
        <div class="tab-head"><h2>Configuración del sistema</h2></div>
        <div class="empty-panel">Funcionalidad en desarrollo</div>
      </div>

    </main>
  </div>
</div>

<script>
  let ventanaRuta = null;
  document.getElementById('btnCrearRuta').addEventListener('click', function(e) {
    e.preventDefault();
    ventanaRuta = window.open(
      '../view/crear_ruta.php?from_form=1',
      'CrearRuta',
      `width=${screen.width},height=${screen.height},left=0,top=0,scrollbars=yes`
    );
  });

  window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'rutaCreada') {
      document.getElementById('idRuta').value = event.data.id_ruta;
      if (ventanaRuta && !ventanaRuta.closed) {
        ventanaRuta.close();
      }
    }
  });

  // Toggle sidebar
  document.getElementById('toggle-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  });

  // Mostrar/ocultar formulario de evento
  function showEventForm() {
    const form = document.getElementById('event-form');
    form.classList.remove('hidden');
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function hideEventForm() {
    document.getElementById('event-form').classList.add('hidden');
    document.querySelector('#event-form form').reset();
  }

  const tabTitles = {
    'dashboard': 'Dashboard',
    'eventos': 'Gestión de eventos',
    'carreras': 'Gestión de carreras',
    'usuarios': 'Gestión de usuarios',
    'productos': 'Gestión de productos',
    'reportes': 'Reportes y estadísticas',
    'configuracion': 'Configuración del sistema'
  };

  // Mostrar/ocultar pestañas
  function showTab(tabId, event) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    document.getElementById('tab-title').textContent = tabTitles[tabId];

    document.querySelectorAll('.sidebar-nav a').forEach(item => item.classList.remove('active'));
    if (event && event.currentTarget) {
      event.currentTarget.classList.add('active');
    } else {
      document.querySelector(`.sidebar-nav a[data-tab="${tabId}"]`)?.classList.add('active');
    }

    if (tabId === 'usuarios' && typeof mostrarUsuarios === 'function') {
      mostrarUsuarios();
    } else if (tabId === 'productos' && typeof mostrarProductos === 'function') {
      mostrarProductos();
    } else if (tabId === 'eventos' && typeof mostrarEventos === 'function') {
      mostrarEventos();
    } else if (tabId === 'dashboard' && typeof totalUsuarios === 'function') {
      totalUsuarios();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    showTab('eventos');
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/administradorUsuarios.js"></script>
<script src="js/administradorProductos.js"></script>
<script src="js/administradorEventos.js"></script>
<script src="js/administradorCarreras.js"></script>
</body>
</html>
