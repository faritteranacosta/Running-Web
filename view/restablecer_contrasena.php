<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nueva contraseña — RunningWeb</title>
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
    <span class="kicker"><i class="fas fa-lock"></i> Nueva contraseña</span>
    <h2>Establece tu nueva contraseña</h2>

    <form id="nueva-password-form">
      <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

      <div class="input-box input-box-relative">
        <label for="password"><i class="fas fa-lock"></i> Nueva contraseña</label>
        <input type="password" id="password" name="password"
               placeholder="Mínimo 8 caracteres" required
               pattern=".{8,}" title="La contraseña debe tener al menos 8 caracteres">
        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
      </div>

      <div class="input-box input-box-relative">
        <label for="confirm-password"><i class="fas fa-lock"></i> Confirmar contraseña</label>
        <input type="password" id="confirm-password" name="confirm-password"
               placeholder="Repite tu contraseña" required>
        <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm-password')"></i>
      </div>

      <button type="submit" id="submit-btn" class="btn btn-primary btn-block">
        <span id="btn-text">Establecer nueva contraseña</span>
        <i id="btn-spinner" class="fas fa-spinner fa-spin hidden"></i>
      </button>
    </form>

    <div id="form-message" class="auth-msg"></div>
  </div>
</div>

<?php require __DIR__ . '/components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../public/js/restablecer.js"></script>
</body>
</html>
