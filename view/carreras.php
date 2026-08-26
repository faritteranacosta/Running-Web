<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Carreras — RunningWeb';
$pageStyles = ['css/carreras.css'];
$pageScripts = [['src' => 'js/carreras.js', 'type' => 'module']];
$activePage = 'carreras';
$showCart = false;

ob_start();
?>
<main>
  <section class="wrap carreras-hero">
    <h1><i class="fas fa-person-running"></i>Carreras disponibles</h1>
    <p>Encuentra las mejores carreras para demostrar tu potencial y superar tus límites.</p>
  </section>

  <section class="wrap carreras-section">
    <div class="carreras-section-head">
      <h2><i class="fas fa-star star"></i> Carrera destacada</h2>
    </div>
    <div class="featured-race" id="carrera-destacada">
      <div class="featured-loading">
        <p><i class="fas fa-spinner fa-spin"></i> Cargando carrera destacada...</p>
      </div>
    </div>
  </section>

  <section class="wrap carreras-section">
    <div class="carreras-section-head">
      <h2><i class="fas fa-list-ul"></i> Más carreras</h2>
    </div>
    <div class="contenedor" id="lista-carreras">
      <div class="catalog-empty">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Cargando carreras...</p>
      </div>
    </div>
  </section>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';