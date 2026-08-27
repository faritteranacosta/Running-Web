# RunningWeb

**RunningWeb** es una plataforma diseñada para quienes están comenzando o quieren avanzar en el mundo del running. Si alguna vez te has preguntado:
- ¿Cómo empiezo a correr?
- ¿Dónde puedo entrenar cerca de mí?
- ¿Hay grupos o eventos de running en mi ciudad?

¡Estás en el lugar indicado! Nuestra comunidad te conecta con personas apasionadas por correr, ofrece información útil y te motiva a mantenerte en movimiento.

---

## Funcionalidades principales

- Registro y login con roles de **corredor**, **vendedor** y **admin**
- Catálogo de eventos y carreras, con inscripción y seguimiento de participación
- Panel de vendedor para publicar y administrar productos deportivos
- Panel de administración (eventos, carreras, usuarios, productos)
- Carrito de compras y detalle de producto
- Creación de rutas sobre mapa (Leaflet) para asociarlas a una carrera
- Recuperación y restablecimiento de contraseña por correo (PHPMailer)

---

## Tecnologías utilizadas

- HTML, CSS, JavaScript (sin frameworks de frontend)
- PHP 8.2 puro, arquitectura MVC + capa de API propia
- MySQL 8 para almacenamiento de datos
- Leaflet + OpenStreetMap para mapas y trazado de rutas
- Composer (PHPMailer)
- Docker / docker-compose para desarrollo local

---

## Arquitectura

El proyecto usa una arquitectura en capas, con un pequeño router propio en vez de un framework:

```
Vista (view/*.php)
   │  fetch('/api/...')
   ▼
api/index.php  ──▶  routes/api.php (mapa "MÉTODO /ruta" → habilitada)
   │
   ▼
controller/*.php   (un Controller por entidad: EventController, RaceController, etc.)
   │
   ▼
model/dao/*.php    (acceso a datos, PDO)
   │
   ▼
model/entidad/*.php (clases de dominio: Evento, Carrera, Usuario, Producto...)
```

- **`api/index.php`** es el único punto de entrada de la API. Resuelve la ruta contra `routes/api.php`, despacha al controller correspondiente y responde siempre en JSON con `jsonResponse()`.
- **Manejo de errores centralizado**: `api/index.php` envuelve todo el despacho en un único `try/catch`. Los errores de base de datos (`PDOException`) se registran con `error_log()` y al cliente solo le llega un mensaje genérico ("Ocurrió un error... inténtalo de nuevo más tarde"); los errores de validación propios (`Exception` con un mensaje pensado para el usuario) sí se muestran tal cual. Así nunca se filtran detalles internos de SQL al navegador.
- **Controllers** (`controller/*.php`) contienen la lógica de aplicación y hablan con los DAO. No conocen HTTP ni JSON — de eso se encarga `api/index.php`.
- **Vistas** (`view/*.php`) consumen la API por `fetch()`, igual que antes consumían los antiguos `ajax_*.php` (esa capa ya no existe).

### Layouts y componentes reutilizables

Para evitar duplicar `<head>`, navegación y footer en cada vista, hay dos capas de composición en `view/`:

```
view/layouts/
├── main.php        # Layout de páginas públicas (home, login, catálogo, eventos, carreras...)
└── dashboard.php    # Layout de páneles internos con sidebar (runner, vendedor, productos, admin)

view/components/
├── navbar.php       # Nav pública, recibe $activePage y $showCart
├── sidebar.php      # Sidebar de dashboard, recibe $navItems (array de links/tabs)
├── footer.php        # Footer del sitio
├── session.php       # Valida $requiredRole contra la sesión, o redirige a acceso_denegado.html
├── event-card.php     # Tarjeta de evento (usada en runner, eventos, home)
├── race-card.php      # Tarjeta de carrera
└── product-card.php   # Tarjeta de producto
```

Patrón típico de una vista pública:

```php
<?php
$requiredRole = 'corredor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Carreras — RunningWeb';
$pageStyles = ['../public/css/carreras.css'];
$pageScripts = [['src' => '../public/js/carreras.js', 'type' => 'module']];
$activePage = 'carreras';

ob_start();
?>
<main> ... contenido específico de la página ... </main>
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/main.php';
```

Y el equivalente para un panel interno (sidebar + topbar):

```php
<?php
$requiredRole = 'vendedor';
require __DIR__ . '/components/session.php';

$pageTitle = 'Panel de vendedor — RunningWeb';
$topbarTitle = 'Panel de vendedor';
$navItems = [
    ['href' => 'vendedor.php', 'icon' => 'fas fa-gauge-high', 'label' => 'Dashboard', 'active' => true],
    // ...
];

ob_start();
?>
... contenido del panel ...
<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/dashboard.php';
```

`panelAdministrador.php` usa el mismo `layouts/dashboard.php`, pasando `$navItems` con `onclick`/`dataTab` en vez de `href`, porque ahí la navegación cambia pestañas por JS en vez de cargar otra página.

> **Nota:** `carrito.php`, `detalles.php` y `crear_ruta.php` todavía no están migrados a este sistema de layouts — siguen con su propio `<head>` y estilos con Tailwind CDN. Es la deuda técnica pendiente más grande del frontend.

---

## Estructura del proyecto

```
RunningWeb/
├── api/
│   └── index.php            # Único entry point de la API, despacha por rutas
├── routes/
│   └── api.php               # Mapa de rutas habilitadas ("MÉTODO /ruta" => true)
├── controller/                # Un controller por entidad (Auth, Event, Race, Product, User...)
├── model/
│   ├── dao/                   # Acceso a datos (PDO, CRUD)
│   └── entidad/                # Clases de dominio (Evento, Carrera, Usuario, Producto...)
├── view/
│   ├── layouts/                # main.php (público) y dashboard.php (paneles internos)
│   ├── components/              # navbar, sidebar, footer, session, *-card
│   ├── *.php / *.html           # Vistas de la aplicación
├── public/
│   ├── css/                    # Un CSS por página + base.css con los tokens compartidos
│   ├── js/                     # Un JS por página
│   └── assets/img/              # Imágenes e íconos
├── config/
│   └── config.json              # Credenciales de conexión a la base de datos
├── runningweb_v2-con-datos-de-prueba.sql   # Dump con datos de ejemplo
├── docker-compose.yml / Dockerfile          # Entorno local (PHP+Apache, MySQL, phpMyAdmin)
└── README.md
```

---

## Correr el proyecto en local (Docker)

No hace falta instalar PHP, Apache ni MySQL en tu máquina — todo corre en contenedores.

```bash
git clone https://github.com/faritteranacosta/Running-Web.git
cd Running-Web
docker compose up -d --build
docker compose exec web composer install
```

- Sitio: **http://localhost:8080/view/index.html**
- phpMyAdmin: **http://localhost:8081** (usuario `root`, contraseña `root`)

La base de datos `runningweb_v2` se crea e importa automáticamente con datos de prueba la primera vez que se levanta el contenedor `db` (vía `runningweb_v2-con-datos-de-prueba.sql`).

El código se monta como volumen, así que cualquier cambio en `view/`, `public/`, `controller/`, `model/`, etc. se refleja al instante con solo recargar el navegador — **no hace falta volver a construir la imagen**, salvo que cambies el `Dockerfile` (ej. agregar una extensión de PHP nueva).

Comandos útiles:

| Acción | Comando |
|---|---|
| Ver logs (incluye errores de PHP/SQL registrados con `error_log`) | `docker compose logs -f web` |
| Entrar a la terminal del contenedor PHP | `docker compose exec web bash` |
| Entrar a MySQL por consola | `docker compose exec db mysql -uroot -proot runningweb_v2` |
| Apagar todo | `docker compose down` |
| Apagar y borrar la base de datos (reset total) | `docker compose down -v` |

---

## ¡Empieza a correr con confianza!

Crea tu cuenta, explora la comunidad y descubre que el running puede cambiarte la vida.
**¡Bienvenido a RunningWeb!**
