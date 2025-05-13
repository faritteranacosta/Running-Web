<?php
require_once '../../model/mdb/mdbDetalle.php';

$id_evento = isset($_GET['id_evento']) ? $_GET['id_evento'] : null;
if ($id_evento !== null) {
    $detalles = obtenerDetallesPorId($id_evento);
} else {
    $detalles = array();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/detalles.css" />
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <title>Detalles carrera</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li>
                    <a href="index.html">
                        Running Web
                    </a>
                </li>
                <li>
                    <a href="index.html">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="eventos.html">
                        Eventos
                    </a>
                </li>
                <li>
                    <a href="carreras.html">
                        Carreras
                    </a>
                </li>
                <li>
                    <a href="#">
                        Productos
                    </a>
                </li>
            </ul>

            <div class="profile-image">
                <img src="assets/img/milei.png" alt="Perfil">
            </div>
        </nav>
    </header>
    <div>
        <h1 id="ts">
            Carreras
        </h1>
    </div>
    <section>
        <div class="event-card">
            <img src="assets/img/runner1.png" alt="Corredores en una carrera">
            <div class="event-info">
                <h2 id="titulo">
                    <?= isset($detalles[0]) ? $detalles[0]->getNombreEvento() : 'Evento no encontrado'; ?>
                </h2>
                <p class="status">Próximo</p>
                <strong class="date">
                    <?= isset($detalles[0]) ? $detalles[0]->getFechaEvento() : '--/--/----'; ?>
                </strong>
                <p class="description">
                    <?= isset($detalles[0]) ? $detalles[0]->getDescripcionEvento() : 'No hay descripción disponible.'; ?>
                </p>
            </div>
        </div>

    </section>

    <div class="card">
        <div class="header">
            <p class="sponsor"> 
                <?= isset($detalles[0]) ? $detalles[0]->getPatrocinador() : 'Sin patrocinador'; ?> </p>
            <p class="team">Chibolo Team</p>
        </div>

        <div class="details">
            <div class="event-info">
                <h3>Fecha:</h3>
                <p><?= isset($detalles[0]) ? $detalles[0]->getFechaEvento() : '--/--/----'; ?></p>

                <h3>Hora:</h3>
                <p><?= isset($detalles[0]) ? $detalles[0]->getHoraEvento() : '--:--'; ?></p>

                <h3>Distancia:</h3>
                <p><?= isset($detalles[0]) ? $detalles[0]->getDistancia() : '--'; ?></p>

                <h3>Categoría:</h3>
                <p><?= isset($detalles[0]) ? $detalles[0]->getCategoria() : '--'; ?></p>

                <h3>Punto de encuentro:</h3>
                <p>Plaza central</p>
            </div>

            <div>
                <h2 class="title">Ruta</h2>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d73246.70058470008!2d-74.20349410494914!3d11.183079934784795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8ef4f129819389d3%3A0x9f9e3a171b198b5!2sMinca%2C%20Santa%20Marta%2C%20Magdalena!5e0!3m2!1ses!2sco!4v1741571486486!5m2!1ses!2sco"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>

            </div>
        </div>

        <div class="logistics">
            <h2>Logística</h2>
            <div class="services">
                <div class="service-item">Hidratación</div>
                <div class="service-item">Asistencia médica</div>
                <div class="service-item">N° Corredor</div>
            </div>
        </div>

        <div class="weather">
            <h2>Pronóstico de clima</h2>
            <div class="forecast">
                <p>Soleado 33° C</p>
            </div>
        </div>

        <div class="action">
            <button class="participate-btn">Participar</button>
        </div>
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