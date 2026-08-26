<?php
$producto = $producto ?? [];
$productoImagen = $producto['image'] ?? 'assets/img/default_product.jpg';
$productoNombre = $producto['name'] ?? 'Producto';
$productoCategoria = $producto['categoria'] ?? 'Sin categoría';
$productoPrecio = $producto['price'] ?? null;
$productoDescripcion = $producto['description'] ?? null;
$productoStock = $producto['stock'] ?? null;
$productoDestacado = $producto['featured'] ?? false;
$productoValoracion = $producto['rating'] ?? null;
$productoResenas = $producto['reviews'] ?? null;
$productoAction = $producto['action'] ?? null;
?>
<div class="product-card">
  <div class="product-media">
    <img src="<?php echo htmlspecialchars($productoImagen, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($productoNombre, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($productoDestacado): ?>
    <span class="product-flag featured">DESTACADO</span>
    <?php elseif ($productoStock !== null && $productoStock <= 0): ?>
    <span class="product-flag out">AGOTADO</span>
    <?php endif; ?>
  </div>
  <div class="product-body">
    <div class="product-row">
      <div>
        <h3><?php echo htmlspecialchars($productoNombre, ENT_QUOTES, 'UTF-8'); ?></h3>
        <p class="product-cat"><?php echo htmlspecialchars($productoCategoria, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
      <?php if ($productoPrecio !== null): ?>
      <span class="product-price">$<?php echo htmlspecialchars((string) $productoPrecio, ENT_QUOTES, 'UTF-8'); ?></span>
      <?php endif; ?>
    </div>
    <?php if ($productoDescripcion !== null): ?>
    <p class="product-desc"><?php echo htmlspecialchars($productoDescripcion, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <?php if ($productoStock !== null): ?>
    <div class="product-row">
      <span class="product-stock"><?php echo htmlspecialchars((string) $productoStock, ENT_QUOTES, 'UTF-8'); ?> disponibles</span>
    </div>
    <?php endif; ?>
    <?php if ($productoValoracion !== null || $productoResenas !== null): ?>
    <div class="product-row">
      <span>
        <?php if ($productoValoracion !== null): ?>
        <span class="product-stars"><?php echo $productoValoracion; ?></span>
        <?php endif; ?>
        <?php if ($productoResenas !== null): ?>
        <span class="product-reviews"><?php echo htmlspecialchars((string) $productoResenas, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
      </span>
    </div>
    <?php endif; ?>
    <?php if ($productoAction !== null): ?>
    <?php echo $productoAction; ?>
    <?php endif; ?>
  </div>
</div>
