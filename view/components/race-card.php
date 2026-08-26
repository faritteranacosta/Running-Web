<?php
$carrera = $carrera ?? [];
$carreraImagen = $carrera['imagen'] ?? 'assets/img/runner5.png';
$carreraNombre = $carrera['nombre'] ?? 'Nombre no disponible';
$carreraDistancia = $carrera['distancia'] ?? 'Distancia no especificada';
$carreraDescripcion = $carrera['descripcion'] ?? 'Descripción no disponible';
$carreraFecha = $carrera['fechaFormateada'] ?? 'Fecha por definir';
$carreraUbicacion = $carrera['ubicacion'] ?? 'Ubicación por definir';
$carreraCategoria = $carrera['categoria'] ?? 'Categoría no especificada';
$carreraEstado = $carrera['estado'] ?? 'Por definir';
$carreraClaseEstado = $carrera['claseEstado'] ?? 'status-pendiente';
$carreraAccion = $carrera['accion'] ?? '<button class="race-cta disabled" disabled>Próximamente</button>';
?>
<div class="event-card">
  <div class="race-card-media">
    <img src="<?php echo htmlspecialchars($carreraImagen, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($carreraNombre, ENT_QUOTES, 'UTF-8'); ?>">
    <span class="status <?php echo htmlspecialchars($carreraClaseEstado, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($carreraEstado, ENT_QUOTES, 'UTF-8'); ?></span>
  </div>
  <div class="race-card-body">
    <h3><?php echo htmlspecialchars($carreraNombre, ENT_QUOTES, 'UTF-8'); ?></h3>
    <span class="race-card-distance"><i class="fas fa-ruler-horizontal"></i> <?php echo htmlspecialchars((string) $carreraDistancia, ENT_QUOTES, 'UTF-8'); ?></span>
    <p class="race-card-desc"><?php echo htmlspecialchars($carreraDescripcion, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="race-card-meta">
      <div><i class="fas fa-calendar-day"></i> <span><?php echo htmlspecialchars($carreraFecha, ENT_QUOTES, 'UTF-8'); ?></span></div>
      <div><i class="fas fa-map-marker-alt"></i> <span><?php echo htmlspecialchars($carreraUbicacion, ENT_QUOTES, 'UTF-8'); ?></span></div>
      <div><i class="fas fa-tag"></i> <span class="cap"><?php echo htmlspecialchars($carreraCategoria, ENT_QUOTES, 'UTF-8'); ?></span></div>
    </div>
    <?php echo $carreraAccion; ?>
  </div>
</div>
