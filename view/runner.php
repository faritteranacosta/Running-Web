<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Perfil del corredor — RunningWeb';
$pageStyles = ['../public/css/runner.css'];
$pageScripts = [['src' => '../public/js/runner.js']];
$topbarTitle = 'Mi perfil de corredor';

$navItems = [
    ['href' => 'runner.php', 'icon' => 'fas fa-gauge-high', 'label' => 'Dashboard', 'active' => true, 'badge' => 24],
    ['href' => 'catalogo_productos.php', 'icon' => 'fas fa-boxes', 'label' => 'Productos'],
    ['href' => '#', 'icon' => 'fas fa-user-friends', 'label' => 'Equipos'],
    ['href' => 'carreras.php', 'icon' => 'fas fa-person-running', 'label' => 'Carreras'],
    ['href' => 'eventos.php', 'icon' => 'fas fa-calendar-alt', 'label' => 'Eventos'],
    ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Estadísticas'],
    ['href' => '#', 'icon' => 'fas fa-gear', 'label' => 'Configuración'],
];

ob_start();
?>
<div class="topbar-actions">
  <button class="icon-btn" aria-label="Notificaciones">
    <i class="fas fa-bell"></i><span class="dot">3</span>
  </button>
  <button class="icon-btn" aria-label="Mensajes">
    <i class="fas fa-envelope"></i><span class="dot">5</span>
  </button>
</div>
<?php
$topbarActions = ob_get_clean();

ob_start();
?>
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon sky"><i class="fas fa-medal"></i></div>
    <div>
      <p class="lbl">Carreras</p>
      <h3 class="num" id="total-races">12</h3>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-road"></i></div>
    <div>
      <p class="lbl">KM totales</p>
      <h3 class="num" id="total-km">156</h3>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="fas fa-stopwatch"></i></div>
    <div>
      <p class="lbl">Mejor tiempo</p>
      <h3 class="num" id="best-time">42:15</h3>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon violet"><i class="fas fa-trophy"></i></div>
    <div>
      <p class="lbl">Logros</p>
      <h3 class="num" id="total-achievements">8/15</h3>
    </div>
  </div>
</div>

<div class="panel-grid">
  <div class="panel profile-card">
    <h2 class="profile-card-title">Información personal</h2>
    <div class="profile-avatar-wrap">
      <img id="profile-avatar" src="../public/assets/img/milei.png" alt="Foto de perfil">
      <button class="link-btn"><i class="fas fa-camera"></i> Cambiar foto</button>
    </div>
    <div class="profile-fields">
      <div>
        <p class="flabel">Nombre completo</p>
        <p class="fval" id="full-name"><?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?></p>
      </div>
      <div>
        <p class="flabel">Correo electrónico</p>
        <p class="fval" id="user-email"><?php echo htmlspecialchars($correo); ?></p>
      </div>
      <div>
        <p class="flabel">Fecha de nacimiento</p>
        <p class="fval" id="birth-date"><?php echo htmlspecialchars($fecha_nacimiento); ?></p>
      </div>
      <div>
        <p class="flabel">Fecha de registro</p>
        <p class="fval" id="register-date"><?php echo htmlspecialchars($fecha_registro); ?></p>
      </div>
      <div>
        <p class="flabel">Nivel</p>
        <div class="level-row">
          <span class="fval" id="user-level">Principiante</span>
          <div class="level-bar"><div class="level-bar-fill" id="level-progress" style="--pct:45%"></div></div>
        </div>
      </div>
    </div>
    <button class="btn btn-primary btn-block">Editar perfil</button>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2>Mis carreras programadas</h2>
      <button class="link-btn"><i class="fas fa-plus"></i> Agregar carrera</button>
    </div>
    <div class="table-wrap">
      <table class="races-table">
        <thead>
          <tr>
            <th>Carrera</th>
            <th>Fecha</th>
            <th>Distancia (km)</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="races-table-body">
          <tr><td colspan="5" class="empty-msg">Cargando tus carreras…</td></tr>
        </tbody>
      </table>
    </div>

    <div class="panel-head panel-head-spaced">
      <h2>Próximos eventos</h2>
    </div>
    <div class="events-grid" id="events-container">
      <p class="empty-msg">Cargando eventos…</p>
    </div>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Productos recomendados</h2>
    <a href="catalogo_productos.php" class="link">Ver todos <i class="fas fa-chevron-right"></i></a>
  </div>
  <div class="products-grid" id="products-container">
    <p class="empty-msg">Muy pronto vas a ver aquí recomendaciones según tu nivel y tus carreras.</p>
  </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/dashboard.php';
