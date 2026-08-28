<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Mi carrito — RunningWeb';
$pageStyles = ['../public/css/carrito.css'];
$pageScripts = [['src' => '../public/js/carrito.js']];
$activePage = 'productos';
$showCart = true;

ob_start();
?>
<main>
  <section class="wrap cart-hero">
    <div class="cart-hero-row">
      <h1><i class="fas fa-shopping-cart"></i> Mi carrito</h1>
      <a href="catalogo_productos.php" class="back-link"><i class="fas fa-arrow-left"></i> Seguir comprando</a>
    </div>
  </section>

  <section class="wrap cart-section">
    <div class="cart-grid">
      <div class="cart-panel">
        <div class="cart-panel-head">
          <h2>Productos seleccionados</h2>
        </div>
        <div id="lista-carrito">
          <div class="cart-empty">
            <i class="fas fa-shopping-cart"></i>
            <p>Tu carrito está vacío</p>
          </div>
        </div>
      </div>

      <div class="cart-panel sticky">
        <div class="cart-panel-head">
          <h2>Resumen del pedido</h2>
        </div>
        <div class="summary-body">
          <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">$00.0</span>
          </div>
          <div class="summary-row">
            <span>Envío</span>
            <span id="envio">$00.0</span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span id="total" class="amount">$0.00</span>
          </div>

          <button id="btn-pagar" class="btn btn-primary btn-block">Proceder al pago</button>

          <p class="summary-secure"><i class="fas fa-lock"></i> Pago seguro con encriptación SSL</p>
        </div>
      </div>
    </div>
  </section>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
