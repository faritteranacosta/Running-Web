<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recuperar contraseña — RunningWeb</title>
<link rel="icon" href="../public/assets/img/icon.ico" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../public/css/base.css">
<link rel="stylesheet" href="../public/css/login.css">
<link rel="stylesheet" href="../public/css/auth-card.css">
</head>
<body>

<div class="auth-shell">
  <div class="auth-card login-card">
    <span class="kicker"><i class="fas fa-key"></i> Recuperación de contraseña</span>
    <h2>¿Olvidaste tu contraseña?</h2>
    <p class="auth-lead">
      Ingresa tu correo y te enviamos un enlace para crear una nueva contraseña.
    </p>

    <form id="recuperar-form">
      <div class="input-box">
        <label for="recuperar-email"><i class="fas fa-envelope"></i> Correo electrónico</label>
        <input type="email" id="recuperar-email" placeholder="tucorreo@ejemplo.com" required>
      </div>

      <button type="submit" id="submit-btn" class="btn btn-primary btn-block">
        <span id="btn-text">Enviar enlace de recuperación</span>
        <i id="btn-spinner" class="fas fa-spinner fa-spin hidden"></i>
      </button>
    </form>

    <div id="recuperar-msg" class="auth-msg"></div>

    <div class="auth-back-wrap">
      <a href="iniciar_sesion.html" class="auth-back">
        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
      </a>
    </div>
  </div>
</div>

<?php require __DIR__ . '/components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../public/js/recuperacion.js"></script>
</body>
</html>
