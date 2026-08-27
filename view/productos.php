<?php
$requiredRole = 'vendedor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Gestión de productos — RunningWeb';
$pageStyles = ['../public/css/productos.css'];
$pageScripts = [['src' => '../public/js/productos.js']];
$topbarTitle = '<i class="fas fa-boxes icon-accent"></i>Gestión de productos';
$showFooter = true;

$sidebarExtra = '<input type="text" id="userId" value="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" hidden>';

$navItems = [
    ['href' => 'vendedor.php', 'icon' => 'fas fa-gauge-high', 'label' => 'Dashboard'],
    ['href' => '#', 'icon' => 'fas fa-cart-shopping', 'label' => 'Ventas', 'badge' => 56],
    ['href' => '#', 'icon' => 'fas fa-users', 'label' => 'Clientes'],
    ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Estadísticas'],
    ['href' => '#', 'icon' => 'fas fa-tags', 'label' => 'Promociones'],
    ['href' => 'productos.php', 'icon' => 'fas fa-store', 'label' => 'Mi tienda', 'active' => true],
    ['href' => '#', 'icon' => 'fas fa-gear', 'label' => 'Configuración'],
];

ob_start();
?>
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
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/dashboard.php';
