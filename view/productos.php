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
<title>Gestión de productos — RunningWeb</title>
<link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/vendedor.css">
<link rel="stylesheet" href="css/productos.css">
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
      <input type="text" id="userId" value="<?php echo htmlspecialchars($id); ?>" hidden>
      <div class="hide-on-collapse">
        <h3><?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?></h3>
        <p><?php echo htmlspecialchars($rol); ?></p>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="vendedor.php">
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
      <a href="productos.php" class="active">
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
      <h1><i class="fas fa-boxes icon-accent"></i>Gestión de productos</h1>
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

      <section class="page-banner">
        <div class="page-banner-inner">
          <h2><i class="fas fa-box-open"></i> Gestión de productos deportivos</h2>
          <p>Administra fácilmente tu catálogo de productos para runners. Agrega, edita y organiza todo en un solo lugar.</p>
        </div>
      </section>

      <section class="panel panel-products">
        <div class="panel-form-head">
          <h3><i class="fas fa-circle-plus"></i> Agregar nuevo producto</h3>
          <button id="toggle-form" class="toggle-form-btn" aria-label="Colapsar formulario">
            <i class="fas fa-chevron-up"></i>
          </button>
        </div>

        <form id="product-form" novalidate>
          <div class="form-grid-2">
            <div class="input-box">
              <label for="product-name">Nombre del producto <span class="req">*</span></label>
              <input id="product-name" name="productName" type="text" placeholder="Ej: Zapatillas de running" required>
            </div>
            <div class="input-box">
              <label for="product-category">Categoría <span class="req">*</span></label>
              <select id="product-category" name="productCategory" required>
                <option disabled selected value="">Selecciona una categoría</option>
                <option value="zapatillas">Zapatillas</option>
                <option value="ropa">Ropa deportiva</option>
                <option value="accesorios">Accesorios</option>
                <option value="nutricion">Nutrición</option>
                <option value="tecnologia">Tecnología</option>
              </select>
            </div>
          </div>

          <div class="input-box">
            <label for="product-description">Descripción <span class="req">*</span></label>
            <textarea id="product-description" name="productDescription" rows="4" placeholder="Descripción detallada del producto" required></textarea>
          </div>

          <div class="form-grid-3">
            <div class="input-box with-prefix">
              <label for="product-price">Precio (COP) <span class="req">*</span></label>
              <span class="prefix">$</span>
              <input id="product-price" name="productPrice" type="number" min="0" step="0.01" placeholder="0.00" required>
            </div>
            <div class="input-box">
              <label for="product-stock">Stock disponible <span class="req">*</span></label>
              <input id="product-stock" name="productStock" type="number" min="0" placeholder="0" required>
            </div>
            <div class="input-box">
              <label for="product-image">Imagen del producto <span class="req">*</span></label>
              <input id="product-image" name="productImage" type="url" placeholder="URL de la imagen" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" onclick="clearForm()" class="btn btn-ghost">
              <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="submit" id="save-product" class="btn btn-primary">
              <i class="fas fa-save"></i> Guardar producto
            </button>
          </div>
        </form>
      </section>

      <section class="panel prod-table-panel">
        <div class="list-head">
          <h3><i class="fas fa-boxes-stacked"></i> Productos publicados</h3>
          <div class="list-tools">
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" placeholder="Buscar productos...">
            </div>
            <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filtrar</button>
          </div>
        </div>

        <div class="table-wrap">
          <table class="ptable">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th class="th-center">Acciones</th>
              </tr>
            </thead>
            <tbody id="product-list">
              <tr><td colspan="6" class="table-empty">Cargando productos…</td></tr>
            </tbody>
          </table>
        </div>

        <div class="list-footer">
          <div>
            Mostrando <span id="showing-from">1</span> a <span id="showing-to">5</span> de
            <span id="total-items">0</span> productos
          </div>
          <div class="pagination-controls" id="pagination-controls">
            <button onclick="previousPage()" id="prev-btn"><i class="fas fa-chevron-left"></i></button>
            <button onclick="nextPage()" id="next-btn"><i class="fas fa-chevron-right"></i></button>
          </div>
        </div>
      </section>

    </main>

    <footer id="contacto">
      <div class="wrap">
        <div class="foot-grid">
          <div>
            <div class="foot-brand">
              <img src="assets/img/icon-black.jpg" alt="RunningWeb">
              <span>RunningWeb</span>
            </div>
            <p class="foot-desc">La plataforma para la comunidad de runners. Conecta, entrena y mejora tu rendimiento.</p>
          </div>
          <div class="foot-col">
            <h4>Enlaces</h4>
            <a href="index.html">Inicio</a>
            <a href="index.html#features">Plataforma</a>
            <a href="index.html#community">Comunidad</a>
          </div>
          <div class="foot-col">
            <h4>Contacto</h4>
            <a href="mailto:webrunning008@gmail.com"><i class="fas fa-envelope"></i> webrunning008@gmail.com</a>
            <a href="tel:+1234567890"><i class="fas fa-phone"></i> +1 234 567 890</a>
            <div class="foot-social">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
        </div>
        <div class="foot-bottom">
          <p>© 2026 RunningWeb. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</div>

<script src="js/productos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('toggle-sidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  });
</script>
</body>
</html>