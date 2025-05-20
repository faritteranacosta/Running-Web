<?php
session_start();
if (!isset($_SESSION['ROL_USUARIO']) || $_SESSION['ROL_USUARIO'] !== 'corredor') {
    header("Location: acceso_denegado.html");
    exit();
}else{
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
    <title>Catálogo de Productos - RunningWeb</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/catalogo_productos.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #8b5cf6 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.2);
        }

        .product-card {
            transition: all 0.3s;
        }

        .product-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .category-filter.active {
            background-color: #0ea5e9;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <a href="index.html" class="flex items-center space-x-3">
                        <img src="assets/img/icon-black.jpg" alt="Logo"
                            class="w-12 h-12 rounded-full border-2 border-white">
                        <h1 class="text-2xl font-bold">RunningWeb</h1>
                    </a>

                    <nav class="hidden md:flex space-x-1">
                        <a href="index.html" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-home mr-2"></i> Inicio
                        </a>
                        <a href="eventos.html" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-calendar-alt mr-2"></i> Eventos
                        </a>
                        <a href="carreras.html" class="px-4 py-2 rounded-lg nav-link smooth-transition">
                            <i class="fas fa-running mr-2"></i> Carreras
                        </a>
                        <a href="catalogo_productos.html"
                            class="px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                            <i class="fas fa-store mr-2"></i> Productos
                        </a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="runner.php" class="flex items-center gap-10">
                        <p><?php echo htmlspecialchars($nombre)?> </p><img src="assets/img/milei.png" alt="Perfil" class="w-10 h-10 rounded-full border-2 border-white">
                    </a>
                    <div class="relative">                       
                        <a href="carrito.html" class="relative">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span
                                class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                                style="display: none;">0</span>
                        </a> 
                    </div>
                    <button class="md:hidden text-white focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden hidden mt-4 pb-2" id="mobile-menu">
                <a href="index.html" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-home mr-2"></i> Inicio
                </a>
                <a href="eventos.html" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-calendar-alt mr-2"></i> Eventos
                </a>
                <a href="carreras.html" class="block px-4 py-2 rounded-lg nav-link smooth-transition">
                    <i class="fas fa-running mr-2"></i> Carreras
                </a>
                <a href="catalogo_productos.html"
                    class="block px-4 py-2 rounded-lg nav-link smooth-transition bg-white bg-opacity-20">
                    <i class="fas fa-store mr-2"></i> Productos
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
        <!-- Título y Filtros -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-store text-blue-500 mr-3"></i>
                Catálogo de Productos
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                Encuentra los mejores productos para runners y mejora tu rendimiento
            </p>

            <!-- Filtros por Categoría -->
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                <button
                    class="category-filter px-4 py-2 rounded-full border border-gray-300 hover:bg-blue-500 hover:text-white smooth-transition active"
                    data-category="all">
                    Todos
                </button>
                <button
                    class="category-filter px-4 py-2 rounded-full border border-gray-300 hover:bg-blue-500 hover:text-white smooth-transition"
                    data-category="zapatillas">
                    Zapatillas
                </button>
                <button
                    class="category-filter px-4 py-2 rounded-full border border-gray-300 hover:bg-blue-500 hover:text-white smooth-transition"
                    data-category="ropa">
                    Ropa
                </button>
                <button
                    class="category-filter px-4 py-2 rounded-full border border-gray-300 hover:bg-blue-500 hover:text-white smooth-transition"
                    data-category="accesorios">
                    Accesorios
                </button>
                <button
                    class="category-filter px-4 py-2 rounded-full border border-gray-300 hover:bg-blue-500 hover:text-white smooth-transition"
                    data-category="nutricion">
                    Nutrición
                </button>
            </div>

            <!-- Barra de Búsqueda -->
            <div class="max-w-md mx-auto relative">
                <input type="text" placeholder="Buscar productos..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Productos Destacados -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-star text-yellow-400 mr-2"></i>
                Productos Destacados
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="productos-destacados">
                <!-- Producto Destacado 1 -->
                <div class="product-card bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
                            alt="Zapatillas Running Pro" class="w-full h-48 object-cover">
                        <span
                            class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">DESTACADO</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg">Zapatillas Running Pro</h3>
                                <p class="text-gray-500 text-sm">Calzado</p>
                            </div>
                            <span class="text-blue-600 font-bold">$89.99</span>
                        </div>
                        <div class="mt-2 flex items-center">
                            <div class="text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm ml-2">(142)</span>
                        </div>
                        <button class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                            <i class="fas fa-cart-plus mr-2"></i> Añadir al carrito
                        </button>
                    </div>
                </div>

                <!-- Más productos destacados... -->
            </div>
        </section>

        <!-- Todos los Productos -->
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list-ul text-blue-500 mr-2"></i>
                    Todos los Productos
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select
                        class="border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Relevancia</option>
                        <option>Precio (menor a mayor)</option>
                        <option>Precio (mayor a menor)</option>
                        <option>Mejor valorados</option>
                        <option>Novedades</option>
                    </select>
                </div>
            </div>

            <div class="products-grid" id="todos-productos">
                <!-- Los productos se cargarán aquí dinámicamente desde el backend -->
                <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
                    <p class="text-gray-500">Cargando productos...</p>
                </div>
            </div>

            <!-- Paginación -->
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">

                </nav>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center space-x-3">
                        <img src="assets/img/icon-black.jpg" alt="Logo" class="w-10 h-10 rounded-full">
                        <h2 class="text-xl font-bold text-blue-600">RunningWeb</h2>
                    </div>
                    <p class="mt-2 text-gray-600 max-w-md">
                        La plataforma líder para runners. Conecta, entrena y supera tus límites.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-8 md:gap-16">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Enlaces</h3>
                        <div class="mt-4 space-y-2">
                            <a href="index.html" class="text-gray-600 hover:text-blue-600 block">Inicio</a>
                            <a href="eventos.html" class="text-gray-600 hover:text-blue-600 block">Eventos</a>
                            <a href="carreras.html" class="text-gray-600 hover:text-blue-600 block">Carreras</a>
                            <a href="catalogo_productos.html"
                                class="text-gray-600 hover:text-blue-600 block">Productos</a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Contacto</h3>
                        <div class="mt-4 space-y-2">
                            <a href="mailto:info@runningweb.com" class="text-gray-600 hover:text-blue-600 block">
                                <i class="fas fa-envelope mr-2"></i> info@runningweb.com
                            </a>
                            <a href="tel:+1234567890" class="text-gray-600 hover:text-blue-600 block">
                                <i class="fas fa-phone mr-2"></i> +123 456 7890
                            </a>
                            <div class="flex space-x-4 mt-3">
                                <a href="#" class="text-gray-600 hover:text-blue-600">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-blue-600">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-blue-600">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-500 text-sm">
                <p>© 2024 RunningWeb. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variables globales
        let currentPage = 1;
        const productsPerPage = 8;
        let allProducts = [];
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            const totalItems = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartBadge = document.querySelector('.cart-badge');

            if (cartBadge) {
                cartBadge.textContent = totalItems;
                cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
            }
        }

        // Función para guardar el carrito en localStorage
        function guardarCarrito() {
            localStorage.setItem('carrito', JSON.stringify(carrito));
        }

        // Función para agregar producto al carrito
        function agregarAlCarrito(producto) {
            // Verificar si el producto ya está en el carrito
            const itemExistente = carrito.find(item => item.id === producto.id);

            if (itemExistente) {
                // Si ya existe, aumentar la cantidad si hay stock disponible
                if (itemExistente.cantidad < producto.stock) {
                    itemExistente.cantidad += 1;
                    mostrarNotificacion('¡Actualizado!', 'Se ha aumentado la cantidad en tu carrito', 'success');
                } else {
                    mostrarNotificacion('Stock insuficiente', 'No hay suficiente stock disponible', 'warning');
                    return;
                }
            } else {
                // Si no existe, agregarlo al carrito
                carrito.push({
                    id: producto.id,
                    name: producto.name,
                    price: producto.price,
                    imagenUrl: producto.imagenUrl,
                    categoria: producto.categoria,
                    cantidad: 1
                });
                mostrarNotificacion('¡Agregado!', 'El producto se ha añadido al carrito', 'success');
            }

            // Actualizar localStorage y contador
            guardarCarrito();
            actualizarContadorCarrito();
        }

        // Función para mostrar notificación
        function mostrarNotificacion(titulo, mensaje, tipo) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: tipo,
                title: mensaje
            });
        }

        // Función para cargar productos con paginación
        async function loadProducts(page = 1, category = 'all') {
            try {
                currentPage = page;
                const response = await fetch('../controller/action/ajax_productos.php');
                const data = await response.json();
                allProducts = data;

                // Filtrar por categoría si es necesario
                const filteredProducts = category === 'all'
                    ? allProducts
                    : allProducts.filter(p => p.categoria.toLowerCase() === category);

                // Calcular índices para la paginación
                const startIndex = (page - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
                const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

                // Renderizar productos
                renderProducts(paginatedProducts);

                // Actualizar paginador
                updatePagination(filteredProducts.length);

            } catch (error) {
                console.error('Error al cargar productos:', error);
                document.getElementById('todos-productos').innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Error al cargar los productos. Por favor, inténtalo de nuevo.</p>
            </div>
        `;
            }
        }

        // Función para renderizar productos
        function renderProducts(products) {
            const container = document.getElementById('todos-productos');

            if (products.length === 0) {
                container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">No se encontraron productos en esta categoría.</p>
            </div>
        `;
                return;
            }

            container.innerHTML = products.map(producto => `
        <div class="product-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="relative">
                <img src="${producto.imagenUrl}" alt="${producto.name}" class="w-full h-48 object-cover">
                ${producto.stock <= 0 ? `
                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    AGOTADO
                </span>` : ''}
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg">${producto.name}</h3>
                <p class="text-gray-500 text-sm capitalize">${producto.categoria.toLowerCase()}</p>
                <p class="text-gray-600 mt-2 line-clamp-2">${producto.description}</p>
                <div class="mt-3 flex justify-between items-center">
                    <span class="text-blue-600 font-bold">$${producto.price}</span>
                    <span class="text-gray-500 text-sm">${producto.stock} disponibles</span>
                </div>
                
                <button onclick="agregarAlCarrito(${JSON.stringify(producto).replace(/"/g, '&quot;')})" 
                    class="mt-3 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition 
                    ${producto.stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''}" 
                    ${producto.stock <= 0 ? 'disabled' : ''}>
                    <i class="fas fa-cart-plus mr-2"></i> 
                    ${producto.stock <= 0 ? 'Agotado' : 'Añadir al carrito'}
                </button>
            </div>
        </div>
    `).join('');
        }

        // Función para actualizar el paginador
        function updatePagination(totalProducts) {
            const totalPages = Math.ceil(totalProducts / productsPerPage);
            const paginationContainer = document.querySelector('.pagination-container');

            if (!paginationContainer) return;

            let paginationHTML = `
        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 
            ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
            ${currentPage === 1 ? 'disabled' : ''}
            onclick="loadProducts(${currentPage - 1})">
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

            // Mostrar máximo 5 páginas alrededor de la actual
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            // Ajustar si estamos cerca del inicio o final
            if (currentPage <= 3) {
                endPage = Math.min(5, totalPages);
            }
            if (currentPage >= totalPages - 2) {
                startPage = Math.max(totalPages - 4, 1);
            }

            // Botón para la primera página si no está visible
            if (startPage > 1) {
                paginationHTML += `
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100"
                onclick="loadProducts(1)">
                1
            </button>
            ${startPage > 2 ? '<span class="px-2">...</span>' : ''}
        `;
            }

            // Botones de páginas
            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
            <button class="px-3 py-1 ${currentPage === i ? 'bg-blue-500 text-white' : 'border border-gray-300'} rounded-lg hover:bg-gray-100"
                onclick="loadProducts(${i})">
                ${i}
            </button>
        `;
            }

            // Botón para la última página si no está visible
            if (endPage < totalPages) {
                paginationHTML += `
            ${endPage < totalPages - 1 ? '<span class="px-2">...</span>' : ''}
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100"
                onclick="loadProducts(${totalPages})">
                ${totalPages}
            </button>
        `;
            }

            // Botón siguiente
            paginationHTML += `
        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 
            ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
            ${currentPage === totalPages ? 'disabled' : ''}
            onclick="loadProducts(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

            paginationContainer.innerHTML = paginationHTML;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            // Cargar productos iniciales
            loadProducts();
            actualizarContadorCarrito();

            // Filtros por categoría
            document.querySelectorAll('.category-filter').forEach(button => {
                button.addEventListener('click', function () {
                    document.querySelectorAll('.category-filter').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');

                    const category = this.dataset.category;
                    loadProducts(1, category);
                });
            });

            // Barra de búsqueda
            const searchInput = document.querySelector('input[type="text"]');
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const productCards = document.querySelectorAll('.product-card');

                productCards.forEach(card => {
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const description = card.querySelector('p.text-gray-600').textContent.toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Toggle Mobile Menu
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Hacer funciones accesibles globalmente
        window.agregarAlCarrito = agregarAlCarrito;
        window.loadProducts = loadProducts;
    </script>
</body>

</html>