<?php
session_start();

if (isset($_SESSION['nombre']) && isset($_SESSION['apellido']) && isset($_SESSION['correo_electronico'])) {
    $apellido = $_SESSION['apellido'];
    $nombre = $_SESSION['nombre'];
    $correo = $_SESSION['correo_electronico'];
} else {
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./view/css/registrarse.css" />
    <link rel="stylesheet" type="text/css" href="./view/css/footer.css">
    <link rel="stylesheet" type="text/css" href="./view/css/style.css" />
    <link rel="icon" href="./view/assets/img/icon.ico" type="image/x-icon">
    <title>Inicio de sesion</title>
</head>

<body>
    <header>

        <nav>
            <ul>
                <li>
                    <a href="./index.html">
                        <div class="contain-logo">
                            <img class="logo" src="./view/assets/img/icon-black.jpg" alt="icono">
                            <h2>Running Web</h1>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/index.html">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="./index.php?action=mostrarRegistro">
                        Registrarse
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="event-card">
        <div class="event-info">
            <h2>Inicia Sesión</h2>
            <p class="status">Empezar en el running puede ser un desafío</p>
            <p class="description">
                Si estás comenzando en el mundo del running, es normal sentirte un poco perdido: ¿cómo empezar?, ¿dónde
                entrenar?, ¿cómo encontrar grupos y eventos cerca de ti? Nuestra plataforma está diseñada para ayudarte
                a resolver estas dudas y conectar con una comunidad apasionada por correr. ¡Inicia sesión y empieza a
                correr con confianza! </p>
        </div>
        <img src="./view/assets/img/runner9.png" alt="Imagen del evento">
    </div>

    <div class="form-container">
        <h2>Ingresa tus datos</h2>
        <form method="post" action="index.php?action=cambiarContrasena" >
            <div class="form-group">
                <input type="hidden" name="correo_electronico"
                    value="<?php echo htmlspecialchars($_SESSION['correo_electronico']); ?>">
            </div>
            <div class="form-group">
                <label for="current-password">Contraseña Actual</label>
                <input type="password" id="current-password" name="current-password" required>
            </div>
            <div class="form-group">
                <label for="new-password">Nueva Contraseña</label>
                <input type="password" id="new-password" name="new-password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmar Contraseña</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <button type="submit" class="btn">Guardar Cambios</button>
        </form>
    </div>


    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <h3>Running web</h3>
            </div>
            <div class="footer-links">

                <div class="footer-section">
                    <h4>Contacto</h4>
                    <ul>
                        <li>Email: info@runningweb.com</li>
                        <li>Teléfono: +123 456 7890</li>
                        <li>Dirección: Calle Corredores 123</li>
                    </ul>
                </div>
            </div>
            <div class="footer-social">
                <h4>Síguenos</h4>
                <div class="social-icons">
                    <a href="#"><i class="facebook-icon"></i></a>
                    <a href="#"><i class="linkedin-icon"></i></a>
                    <a href="#"><i class="youtube-icon"></i></a>
                    <a href="#"><i class="instagram-icon"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Running web. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>