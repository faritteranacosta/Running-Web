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
<title>Panel de vendedor — RunningWeb</title>
<link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/vendedor.css">
</head>
<body class="dash">

<div class="dash-shell">

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-head">
      <a href="#" class="sidebar-brand">
        <img src="assets/img/icon-black.jpg" alt="RunningWeb">
        <span class="hide-on-collapse">RunningWeb</span>
      </a>
      <button class="sidebar-toggle" id="toggle-sidebar" aria-label="Colapsar menú">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <div class="sidebar-user">
      <div class="avatar-chip"><?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?></div>
      <input type="text" id="userId" value="<?php echo htmlspecialchars($id); ?>" hidden>
      <div class="hide-on-collapse">
        <h3><?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?></h3>
        <p><?php echo htmlspecialchars($rol); ?></p>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="vendedor.php" class="active">
        <i class="fas fa-gauge-high"></i> <span class="hide-on-collapse">Dashboard</span>
      </a>
      <a href="#">
        <i class="fas fa-cart-shopping"></i> <span class="hide-on-collapse">Ventas</span>
        <span class="nav-badge hide-on-collapse">56</span>
      </a>
      <a href="#">
        <i class="fas fa-users"></i> <span class="hide-on-collapse">Clientes</span>
      </a>
      <a href="#">
        <i class="fas fa-chart-line"></i> <span class="hide-on-collapse">Estadísticas</span>
      </a>
      <a href="#">
        <i class="fas fa-tags"></i> <span class="hide-on-collapse">Promociones</span>
      </a>
      <a href="productos.php">
        <i class="fas fa-store"></i> <span class="hide-on-collapse">Mi tienda</span>
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
      <h1>Panel de vendedor</h1>
      <div class="topbar-actions">
        <button class="icon-btn" aria-label="Notificaciones">
          <i class="fas fa-bell"></i><span class="dot">5</span>
        </button>
        <button class="icon-btn" aria-label="Ayuda">
          <i class="fas fa-circle-question"></i>
        </button>
      </div>
    </header>

    <main class="dash-content">

      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-icon sky"><i class="fas fa-dollar-sign"></i></div>
          <div>
            <p class="lbl">Ventas hoy</p>
            <h3 class="num">$1,245</h3>
            <p class="delta up"><i class="fas fa-arrow-up"></i> 12% vs ayer</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green"><i class="fas fa-cart-shopping"></i></div>
          <div>
            <p class="lbl">Órdenes</p>
            <h3 class="num">18</h3>
            <p class="delta up"><i class="fas fa-arrow-up"></i> 3 nuevas</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon amber"><i class="fas fa-box-open"></i></div>
          <div>
            <p class="lbl">Productos</p>
            <h3 class="num">24</h3>
            <p class="delta warn"><i class="fas fa-triangle-exclamation"></i> 2 bajos en stock</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon violet"><i class="fas fa-star"></i></div>
          <div>
            <p class="lbl">Valoración</p>
            <h3 class="num">4.7/5</h3>
            <p class="delta cell-muted"><i class="fas fa-comment"></i> 56 reseñas</p>
          </div>
        </div>
      </div>

      <div class="panel-grid">
        <div class="panel">
          <div class="panel-head">
            <h2>Ventas mensuales</h2>
            <div class="chip-toggle">
              <button class="on">Mes</button>
              <button>Año</button>
            </div>
          </div>
          <div class="chart-placeholder">Gráfico de ventas interactivo aparecerá aquí</div>
        </div>

        <div class="panel">
          <div class="panel-head">
            <h2>Órdenes recientes</h2>
            <a href="#" class="link">Ver todas</a>
          </div>
          <div class="order-row">
            <div><p class="id">#ORD-45612</p><p class="when">Hace 2 horas</p></div>
            <span class="status-pill done">Completada</span>
          </div>
          <div class="order-row">
            <div><p class="id">#ORD-45611</p><p class="when">Hace 5 horas</p></div>
            <span class="status-pill progress">En proceso</span>
          </div>
          <div class="order-row">
            <div><p class="id">#ORD-45610</p><p class="when">Hace 1 día</p></div>
            <span class="status-pill shipped">Enviado</span>
          </div>
          <div class="order-row">
            <div><p class="id">#ORD-45609</p><p class="when">Hace 1 día</p></div>
            <span class="status-pill pending">Pendiente</span>
          </div>
        </div>
      </div>

      <div class="panel panel-products">
        <div class="panel-head">
          <h2>Mis productos</h2>
          <a href="productos.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo producto
          </a>
        </div>
        <div class="table-wrap">
          <table class="product-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Ventas</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="7" class="table-empty">Cargando productos…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head">
          <h2>Reseñas recientes</h2>
          <a href="#" class="link">Ver todas</a>
        </div>

        <div class="review">
          <div class="review-top">
            <img src="assets/img/user1.jpg" alt="Carlos Méndez">
            <div>
              <h4>Carlos Méndez</h4>
              <span class="review-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
              </span>
              <span class="review-when">Hace 2 días</span>
            </div>
          </div>
          <p>"Excelentes zapatillas, muy cómodas para correr largas distancias. El envío fue rápido y el producto llegó en perfectas condiciones."</p>
        </div>

        <div class="review">
          <div class="review-top">
            <img src="assets/img/user2.jpg" alt="Ana Rodríguez">
            <div>
              <h4>Ana Rodríguez</h4>
              <span class="review-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
              </span>
              <span class="review-when">Hace 5 días</span>
            </div>
          </div>
          <p>"El reloj GPS funciona muy bien, pero la batería podría durar más. En general buena compra, el vendedor respondió rápido mis dudas."</p>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="js/productosVendedor.js"></script>
<script>
  document.getElementById('toggle-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  });

  // Toggle de "Mes" / "Año" en el panel de ventas (visual, sin datos reales aún)
  document.querySelectorAll('.chip-toggle button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.chip-toggle button').forEach(b => b.classList.remove('on'));
      btn.classList.add('on');
    });
  });
</script>
</body>
</html>