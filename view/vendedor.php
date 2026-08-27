<?php
$requiredRole = 'vendedor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Panel de vendedor — RunningWeb';
$pageScripts = [['src' => '../public/js/productosVendedor.js']];
$topbarTitle = 'Panel de vendedor';

$sidebarExtra = '<input type="text" id="userId" value="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" hidden>';

$navItems = [
    ['href' => 'vendedor.php', 'icon' => 'fas fa-gauge-high', 'label' => 'Dashboard', 'active' => true],
    ['href' => '#', 'icon' => 'fas fa-cart-shopping', 'label' => 'Ventas', 'badge' => 56],
    ['href' => '#', 'icon' => 'fas fa-users', 'label' => 'Clientes'],
    ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Estadísticas'],
    ['href' => '#', 'icon' => 'fas fa-tags', 'label' => 'Promociones'],
    ['href' => 'productos.php', 'icon' => 'fas fa-store', 'label' => 'Mi tienda'],
    ['href' => '#', 'icon' => 'fas fa-gear', 'label' => 'Configuración'],
];

ob_start();
?>
<div class="topbar-actions">
  <button class="icon-btn" aria-label="Notificaciones">
    <i class="fas fa-bell"></i><span class="dot">5</span>
  </button>
  <button class="icon-btn" aria-label="Ayuda">
    <i class="fas fa-circle-question"></i>
  </button>
</div>
<?php
$topbarActions = ob_get_clean();

ob_start();
?>
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
      <img src="../public/assets/img/user1.jpg" alt="Carlos Méndez">
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
      <img src="../public/assets/img/user2.jpg" alt="Ana Rodríguez">
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
<?php
$content = ob_get_clean();

$inlineScript = <<<'JS'
  // Toggle de "Mes" / "Año" en el panel de ventas (visual, sin datos reales aún)
  document.querySelectorAll('.chip-toggle button').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.chip-toggle button').forEach(b => b.classList.remove('on'));
      btn.classList.add('on');
    });
  });
JS;

require __DIR__ . '/layouts/dashboard.php';
