<?php
$evento = $evento ?? [];
$eventoImagen = $evento['imagen'] ?? 'assets/img/runner9.png';
$eventoNombre = $evento['nombre'] ?? 'Nombre no disponible';
$eventoDescripcion = $evento['descripcion'] ?? 'Descripción no disponible';
$eventoFecha = $evento['fechaFormateada'] ?? 'Fecha por definir';
$eventoHora = $evento['hora'] ?? 'Hora no especificada';
$eventoTipo = $evento['tipo'] ?? 'Tipo no especificado';
$eventoDireccion = $evento['direccion'] ?? 'Dirección no especificada';
$eventoEstado = $evento['estado'] ?? '';
$eventoClaseEstado = $evento['claseEstado'] ?? '';
?>
<div class="event-card">
  <div class="event-card-media">
    <img src="<?php echo htmlspecialchars($eventoImagen, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($eventoNombre, ENT_QUOTES, 'UTF-8'); ?>">
  </div>
  <div class="event-card-body">
    <div class="event-card-top">
      <h3><?php echo htmlspecialchars($eventoNombre, ENT_QUOTES, 'UTF-8'); ?></h3>
      <span class="status <?php echo htmlspecialchars($eventoClaseEstado, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($eventoEstado, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <p class="event-card-desc"><?php echo htmlspecialchars($eventoDescripcion, ENT_QUOTES, 'UTF-8'); ?></p>
    <div class="event-card-meta">
      <div><i class="fas fa-calendar-day"></i> <span><?php echo htmlspecialchars($eventoFecha, ENT_QUOTES, 'UTF-8'); ?></span></div>
      <div><i class="far fa-clock"></i> <span><?php echo htmlspecialchars($eventoHora, ENT_QUOTES, 'UTF-8'); ?></span></div>
      <div><i class="fas fa-running"></i> <span><?php echo htmlspecialchars($eventoTipo, ENT_QUOTES, 'UTF-8'); ?></span></div>
      <div><i class="fas fa-map-marker-alt"></i> <span><?php echo htmlspecialchars($eventoDireccion, ENT_QUOTES, 'UTF-8'); ?></span></div>
    </div>
    <button class="event-card-cta"><i class="fas fa-info-circle"></i> Ver detalles</button>
  </div>
</div>
