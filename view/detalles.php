<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Detalles de la carrera — RunningWeb';
$pageStyles = [
    'https://unpkg.com/leaflet/dist/leaflet.css',
    '../public/css/carreras.css',
    '../public/css/detalles.css',
];
$pageScripts = [
    ['src' => 'https://unpkg.com/leaflet/dist/leaflet.js'],
    ['src' => '../public/js/detalles.js'],
];
$activePage = 'carreras';
$showCart = false;

ob_start();
?>
<main>
  <section class="wrap detail-section">

    <div class="detail-hero-grid">
      <div class="detail-card">
        <img id="carrera-imagen" src="../public/assets/img/runner1.png" alt="Imagen de la carrera" class="detail-hero-img">
        <div class="detail-card-body">
          <div class="detail-top-row">
            <h1 id="titulo">Cargando...</h1>
            <span id="estado-carrera" class="status status-proxima">Próximo</span>
          </div>
          <div class="detail-meta-row">
            <i class="fas fa-calendar-day"></i>
            <span id="fecha-carrera">Fecha no disponible</span>
            <i class="fas fa-map-marker-alt sep"></i>
            <span id="ubicacion-carrera">Ubicación no disponible</span>
          </div>
          <p id="descripcion-carrera" class="detail-desc">Cargando descripción de la carrera...</p>
          <div id="tags-carrera" class="detail-tags"></div>
        </div>
      </div>

      <div class="detail-card enroll-card">
        <div class="enroll-head">
          <h2>Inscríbete ahora</h2>
          <div class="input-box">
            <label for="select-categoria">Categoría</label>
            <select id="select-categoria">
              <option value="">Cargando categorías...</option>
            </select>
          </div>
          <div class="input-box">
            <label for="select-talla">Talla de playera</label>
            <select id="select-talla">
              <option value="">Cargando tallas...</option>
            </select>
          </div>
          <button id="btn-inscribirse" class="btn btn-primary btn-block">
            <i class="fas fa-running"></i> Confirmar inscripción
          </button>
        </div>
        <div class="enroll-foot">
          <h3>Incluye:</h3>
          <ul id="beneficios-carrera">
            <li><i class="fas fa-tshirt"></i> Playera técnica</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="map-grid">
      <div class="detail-card">
        <div class="detail-card-body detail-card-body-tight">
          <h2><i class="fas fa-route"></i> Mapa de la ruta</h2>
        </div>
        <div class="map-container" id="mapa-ruta">
          <div class="map-container-placeholder">
            <p>Cargando mapa de la ruta...</p>
          </div>
        </div>
        <div class="tech-details-row">
          <div>
            <p class="label">Distancia</p>
            <p class="value" id="distancia-carrera">-- km</p>
          </div>
          <div>
            <p class="label">Tipo de ruta</p>
            <p class="value" id="tipo-ruta">--</p>
          </div>
          <div>
            <p class="label">Elevación</p>
            <p class="value" id="elevacion-carrera">-- m</p>
          </div>
        </div>
      </div>

      <div class="map-side-col">
        <div class="detail-card">
          <div class="detail-card-body">
            <h2><i class="fas fa-cloud-sun icon-amber"></i> Pronóstico del clima</h2>
            <div class="weather-row">
              <div class="weather-icon"><i id="icono-clima" class="fas fa-sun"></i></div>
              <div>
                <p class="weather-temp" id="temperatura">--°C</p>
                <p class="weather-cond" id="condicion-clima">Próximamente</p>
                <p class="weather-extra" id="detalles-clima">Viento: -- km/h • Humedad: --%</p>
              </div>
            </div>
          </div>
        </div>

        <div class="detail-card">
          <div class="detail-card-body">
            <h2><i class="fas fa-trophy icon-violet"></i> Premios</h2>
            <ul id="premios-carrera" class="premios-list">
              <li><i class="fas fa-medal"></i> <span>Próximamente</span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="detail-info-grid">
      <div class="detail-card accent">
        <div class="detail-card-body">
          <h2><i class="fas fa-clock"></i> Horarios</h2>
          <ul id="horarios-carrera" class="horarios-list">
            <li>Próximamente</li>
          </ul>
        </div>
      </div>

      <div class="detail-card accent">
        <div class="detail-card-body">
          <h2><i class="fas fa-handshake"></i> Patrocinadores</h2>
          <div id="patrocinadores" class="patrocinadores-box">
            <p>Cargando patrocinadores...</p>
          </div>
          <p class="patrocinadores-cta">¿Quieres ser patrocinador? <a href="#contacto">Contáctanos</a></p>
        </div>
      </div>

      <div class="detail-card accent">
        <div class="detail-card-body">
          <h2><i class="fas fa-clipboard-list"></i> Requisitos</h2>
          <ul id="requisitos-carrera" class="requisitos-list">
            <li><i class="fas fa-check"></i> <span>Próximamente</span></li>
          </ul>
        </div>
      </div>
    </div>

  </section>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
