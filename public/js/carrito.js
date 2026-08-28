// Obtener el carrito de localStorage
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
// Elementos del DOM
const listaCarrito = document.getElementById('lista-carrito');
const subtotalElement = document.getElementById('subtotal');
const envioElement = document.getElementById('envio');
const totalElement = document.getElementById('total');
const btnPagar = document.getElementById('btn-pagar');

// Función para renderizar el carrito
function renderizarCarrito() {
    // Limpiar lista
    listaCarrito.innerHTML = '';

    if (carrito.length === 0) {
        listaCarrito.innerHTML = `
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <p>Tu carrito está vacío</p>
                <a href="catalogo_productos.php" class="btn btn-primary">Ir a productos</a>
            </div>
        `;

        // Actualizar totales
        subtotalElement.textContent = '$00.0';
        envioElement.textContent = '$00.0';
        totalElement.textContent = '$00.0';

        // Deshabilitar botón de pago
        btnPagar.disabled = true;
        return;
    }

    // Renderizar cada producto
    carrito.forEach(item => {
        const productoElement = document.createElement('div');
        productoElement.className = 'cart-item';
        productoElement.innerHTML = `
            <img src="${item.imagenUrl || '../public/assets/img/producto-default.jpg'}" alt="${item.name}">
            <div class="cart-item-info">
                <h3>${item.name}</h3>
                <p class="unit-price">$${item.price} c/u</p>
                <p class="cat">${item.categoria}</p>
            </div>
            <div class="qty-stepper">
                <button onclick="cambiarCantidad(${item.id}, -1)" aria-label="Restar">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="qty-value">${item.cantidad}</span>
                <button onclick="cambiarCantidad(${item.id}, 1)" aria-label="Sumar">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="cart-item-total">$${(item.price * item.cantidad).toFixed(2)}</div>
            <button onclick="eliminarDelCarrito(${item.id})" class="cart-item-remove" aria-label="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        `;
        listaCarrito.appendChild(productoElement);
    });

    // Calcular totales
    calcularTotales();
}

// Función para calcular subtotal, envío y total
function calcularTotales() {
    const subtotal = carrito.reduce((total, item) => total + (item.price * item.cantidad), 0);
    const envio = subtotal > 70000 ? 0 : 14000; 
    const total = subtotal + envio;
    
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    envioElement.textContent = `$${envio.toFixed(2)}`;
    totalElement.textContent = `$${total.toFixed(2)}`;
    
    // Habilitar botón de pago
    btnPagar.disabled = false;
}

// Función para cambiar cantidad de un producto
function cambiarCantidad(productoId, cambio) {
    const item = carrito.find(item => item.id === productoId);
    
    if (!item) return;
    
    item.cantidad += cambio;
    
    // Si la cantidad llega a 0, eliminar el producto
    if (item.cantidad <= 0) {
        eliminarDelCarrito(productoId);
        return;
    }
    
    // Actualizar carrito
    guardarCarrito();
    renderizarCarrito();
}

// Función para eliminar producto del carrito
function eliminarDelCarrito(productoId) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: "¿Estás seguro de que quieres eliminar este producto de tu carrito?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            carrito = carrito.filter(item => item.id !== productoId);
            guardarCarrito();
            renderizarCarrito();
            Swal.fire('Eliminado', 'El producto ha sido eliminado de tu carrito', 'success');
        }
    });
}

// Función para guardar carrito
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// Función para proceder al pago
function procederAlPago() {
    Swal.fire({
        title: '¿Proceder al pago?',
        text: "Serás redirigido a nuestro sistema de pago seguro",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Seguir comprando'
    }).then((result) => {
        if (result.isConfirmed) {
            // Simular proceso de pago
            Swal.fire({
                title: 'Redirigiendo...',
                text: 'A nuestro procesador de pagos seguro',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                // Limpiar carrito después del pago
                carrito = [];
                guardarCarrito();
                window.location.href = '#';
            });
        }
    });
}

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    renderizarCarrito();
    // Evento para el botón de pago
    btnPagar.addEventListener('click', procederAlPago);
});

// Hacer funciones accesibles globalmente
window.cambiarCantidad = cambiarCantidad;
window.eliminarDelCarrito = eliminarDelCarrito;