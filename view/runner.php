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
<title>Perfil del corredor — RunningWeb</title>
<link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/vendedor.css">
<link rel="stylesheet" href="css/runner.css">
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
      <a href="runner.php" class="active">
        <i class="fas fa-gauge-high"></i> <span class="hide-on-collapse">Dashboard</span>
        <span class="nav-badge hide-on-collapse">24</span>
      </a>
      <a href="catalogo_productos.php">
        <i class="fas fa-boxes"></i> <span class="hide-on-collapse">Productos</span>
      </a>
      <a href="#">
        <i class="fas fa-user-friends"></i> <span class="hide-on-collapse">Equipos</span>
      </a>
      <a href="carreras.php">
        <i class="fas fa-person-running"></i> <span class="hide-on-collapse">Carreras</span>
      </a>
      <a href="eventos.php">
        <i class="fas fa-calendar-alt"></i> <span class="hide-on-collapse">Eventos</span>
      </a>
      <a href="#">
        <i class="fas fa-chart-line"></i> <span class="hide-on-collapse">Estadísticas</span>
      </a>
      <a href="#">
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
      <h1>Mi perfil de corredor</h1>
      <div class="topbar-actions">
        <button class="icon-btn" aria-label="Notificaciones">
          <i class="fas fa-bell"></i><span class="dot">3</span>
        </button>
        <button class="icon-btn" aria-label="Mensajes">
          <i class="fas fa-envelope"></i><span class="dot">5</span>
        </button>
      </div>
    </header>

    <main class="dash-content">

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
            <img id="profile-avatar" src="assets/img/milei.png" alt="Foto de perfil">
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

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/runner.js"></script>
<script>
  document.getElementById('toggle-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  });
</script>
</body>
</html>