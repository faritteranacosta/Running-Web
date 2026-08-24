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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carreras — RunningWeb</title>
<link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/carreras.css">
</head>
<body>

<nav class="nav">
  <div class="wrap nav-row">
    <a href="index.html" class="brand">
      <img src="assets/img/icon-black.jpg" alt="RunningWeb">
      <span>RunningWeb</span>
    </a>
    <div class="nav-links">
      <a href="runner.php"><i class="fas fa-home"></i> Inicio</a>
      <a href="eventos.php"><i class="fas fa-calendar-alt"></i> Eventos</a>
      <a href="carreras.php" class="active"><i class="fas fa-person-running"></i> Carreras</a>
      <a href="catalogo_productos.php"><i class="fas fa-store"></i> Productos</a>
    </div>
    <div class="nav-cta nav-user">
      <a href="runner.php" class="nav-avatar">
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <div class="chip"><?php echo strtoupper(mb_substr($nombre, 0, 1, 'UTF-8')); ?></div>
      </a>
    </div>
    <button class="menu-btn" id="mobile-menu-button" aria-label="Menú" aria-expanded="false"><i class="fas fa-bars"></i></button>
  </div>
  <div class="mobile-menu" id="mobile-menu">
    <a href="runner.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="eventos.php"><i class="fas fa-calendar-alt"></i> Eventos</a>
    <a href="carreras.php"><i class="fas fa-person-running"></i> Carreras</a>
    <a href="catalogo_productos.php"><i class="fas fa-store"></i> Productos</a>
  </div>
</nav>

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

<footer id="contacto">
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="foot-brand">
          <img src="assets/img/icon-black.jpg" alt="RunningWeb">
          <span>RunningWeb</span>
        </div>
        <p class="foot-desc">La plataforma para la comunidad de runners. Conecta, entrena y mejora tu rendimiento.</p>
      </div>
      <div class="foot-col">
        <h4>Enlaces</h4>
        <a href="runner.php">Inicio</a>
        <a href="eventos.php">Eventos</a>
        <a href="carreras.php">Carreras</a>
      </div>
      <div class="foot-col">
        <h4>Contacto</h4>
        <a href="mailto:webrunning008@gmail.com"><i class="fas fa-envelope"></i> webrunning008@gmail.com</a>
        <a href="tel:+1234567890"><i class="fas fa-phone"></i> +1 234 567 890</a>
        <div class="foot-social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
    <div class="foot-bottom">
      <p>© 2026 RunningWeb. Todos los derechos reservados.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="js/carreras.js"></script>
<script>
  const menuBtn = document.getElementById("mobile-menu-button");
  const mobileMenu = document.getElementById("mobile-menu");
  menuBtn.addEventListener("click", function () {
    const isOpen = mobileMenu.classList.toggle("open");
    menuBtn.setAttribute("aria-expanded", isOpen);
  });
</script>
</body>
</html>