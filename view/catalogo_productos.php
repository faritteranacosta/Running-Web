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

  $pageTitle = 'Catálogo de productos — RunningWeb';
  $pageStyles = ['css/catalogo_productos.css'];
  $pageScripts = [['src' => 'js/catalogo.js']];
  $activePage = 'productos';
  $showCart = true;

  ob_start();
?>
<main>
  <section class="wrap catalog-hero">
    <h1><i class="fas fa-store"></i>Catálogo de productos</h1>
    <p>Encuentra los mejores productos para runners y mejora tu rendimiento.</p>

    <div class="search-row">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Buscar productos...">
    </div>

    <div class="filter-row">
      <button class="filter-chip active" data-category="all">Todos</button>
      <button class="filter-chip" data-category="zapatillas">Zapatillas</button>
      <button class="filter-chip" data-category="ropa">Ropa</button>
      <button class="filter-chip" data-category="accesorios">Accesorios</button>
      <button class="filter-chip" data-category="nutricion">Nutrición</button>
    </div>
  </section>

  <section class="wrap catalog-section">
    <div class="catalog-section-head">
      <h2><i class="fas fa-list-ul"></i> Todos los productos</h2>
      <div class="sort-row">
        <span>Ordenar por:</span>
        <select>
          <option>Relevancia</option>
          <option>Precio (menor a mayor)</option>
          <option>Precio (mayor a menor)</option>
          <option>Mejor valorados</option>
          <option>Novedades</option>
        </select>
      </div>
    </div>

    <div class="products-grid" id="todos-productos">
      <div class="catalog-empty">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Cargando productos...</p>
      </div>
    </div>

    <div class="pagination-container"></div>
  </section>

  <section class="wrap catalog-section">
    <div class="catalog-section-head">
      <h2><i class="fas fa-star star"></i> Productos destacados</h2>
    </div>
    <div class="products-grid" id="productos-destacados">
      <div class="product-card">
        <div class="product-media">
          <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Zapatillas Running Pro">
          <span class="product-flag featured">DESTACADO</span>
        </div>
        <div class="product-body">
          <div class="product-row">
            <div>
              <h3>Zapatillas Running Pro</h3>
              <p class="product-cat">Calzado</p>
            </div>
            <span class="product-price">$89.99</span>
          </div>
          <div class="product-row">
            <span>
              <span class="product-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
              </span>
              <span class="product-reviews">(142)</span>
            </span>
          </div>
          <button class="add-cart-btn"><i class="fas fa-cart-plus"></i> Añadir al carrito</button>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';