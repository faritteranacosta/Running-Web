<?php
$requiredRole = 'admin';
require __DIR__ . '/components/session.php';

$pageTitle = 'Panel de administración — RunningWeb';
$pageStyles = [
    '../public/css/productos.css',
    '../public/css/admin.css',
    '../public/css/crear_carrera.css',
];
$pageScripts = [
    ['src' => '../public/js/administradorUsuarios.js'],
    ['src' => '../public/js/administradorProductos.js'],
    ['src' => '../public/js/administradorEventos.js'],
    ['src' => '../public/js/administradorCarreras.js'],
];
$topbarTitle = 'Dashboard';
$topbarTitleId = 'tab-title';

$navItems = [
    ['href' => '#', 'icon' => 'fas fa-gauge-high', 'label' => 'Dashboard', 'onclick' => "showTab('dashboard', event); return false;", 'dataTab' => 'dashboard'],
    ['href' => '#', 'icon' => 'fas fa-calendar-alt', 'label' => 'Eventos', 'onclick' => "showTab('eventos', event); return false;", 'dataTab' => 'eventos'],
    ['href' => '#', 'icon' => 'fas fa-person-running', 'label' => 'Carreras', 'onclick' => "showTab('carreras', event); return false;", 'dataTab' => 'carreras'],
    ['href' => '#', 'icon' => 'fas fa-users', 'label' => 'Usuarios', 'onclick' => "showTab('usuarios', event); return false;", 'dataTab' => 'usuarios'],
    ['href' => '#', 'icon' => 'fas fa-boxes', 'label' => 'Productos', 'onclick' => "showTab('productos', event); return false;", 'dataTab' => 'productos'],
    ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Reportes', 'onclick' => "showTab('reportes', event); return false;", 'dataTab' => 'reportes'],
    ['href' => '#', 'icon' => 'fas fa-gear', 'label' => 'Configuración', 'onclick' => "showTab('configuracion', event); return false;", 'dataTab' => 'configuracion'],
];

ob_start();
?>
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
<div id="eventos" class="tab-content">
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
<?php
$content = ob_get_clean();

$inlineScript = <<<'JS'
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
    showTab('dashboard');
  });
JS;

require __DIR__ . '/layouts/dashboard.php';
