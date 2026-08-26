<nav class="nav">
  <div class="wrap nav-row">
    <a href="index.html" class="brand">
      <img src="../public/assets/img/icon-black.jpg" alt="RunningWeb">
      <span>RunningWeb</span>
    </a>
    <div class="nav-links">
      <a href="eventos.php"<?php echo $activePage === 'eventos' ? ' class="active"' : ''; ?>><i class="fas fa-calendar-alt"></i> Eventos</a>
      <a href="carreras.php"<?php echo $activePage === 'carreras' ? ' class="active"' : ''; ?>><i class="fas fa-person-running"></i> Carreras</a>
      <a href="catalogo_productos.php"<?php echo $activePage === 'productos' ? ' class="active"' : ''; ?>><i class="fas fa-store"></i> Productos</a>
    </div>
    <div class="nav-cta nav-user">
      <?php if ($showCart): ?>
      <a href="carrito.php" class="nav-cart" aria-label="Carrito">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge">0</span>
      </a>
      <?php endif; ?>
      <a href="runner.php" class="nav-avatar">
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <div class="chip"><?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?></div>
      </a>
    </div>
    <button class="menu-btn" id="mobile-menu-button" aria-label="Menú" aria-expanded="false"><i class="fas fa-bars"></i></button>
  </div>
  <div class="mobile-menu" id="mobile-menu">
    <a href="eventos.php"><i class="fas fa-calendar-alt"></i> Eventos</a>
    <a href="carreras.php"><i class="fas fa-person-running"></i> Carreras</a>
    <a href="catalogo_productos.php"><i class="fas fa-store"></i> Productos</a>
    <?php if ($showCart): ?>
    <div class="nav-cta">
      <a href="carrito.php" class="btn btn-ghost btn-block"><i class="fas fa-shopping-cart"></i> Carrito</a>
    </div>
    <?php endif; ?>
  </div>
</nav>
