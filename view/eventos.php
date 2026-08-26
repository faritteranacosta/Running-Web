<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

  $pageTitle = 'Eventos — RunningWeb';
  $pageStyles = ['css/eventos.css'];
  $pageScripts = [['src' => 'js/eventos.js']];
  $activePage = 'eventos';
  $showCart = false;

  ob_start();
?>
<main>
  <section class="wrap eventos-hero">
    <h1><i class="fas fa-calendar-alt"></i>Próximos eventos</h1>
    <p>Descubre eventos, charlas y actividades para mejorar tu rendimiento y conectar con otros runners.</p>
  </section>

  <section class="wrap eventos-section">
    <div class="eventos-section-head">
      <h2><i class="fas fa-star star"></i> Evento destacado</h2>
    </div>
    <div class="featured-event" id="evento-destacado">
      <div class="featured-loading">
        <p><i class="fas fa-spinner fa-spin"></i> Cargando evento destacado...</p>
      </div>
    </div>
  </section>

  <section class="wrap eventos-section">
    <div class="eventos-section-head">
      <h2><i class="fas fa-list-ul"></i> Más eventos</h2>
    </div>
    <div class="contenedor" id="lista-eventos">
      <div class="catalog-empty">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Cargando eventos...</p>
      </div>
    </div>
  </section>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';