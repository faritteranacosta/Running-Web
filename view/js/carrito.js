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
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
                <p>Tu carrito está vacío</p>
                <a href="catalogo_productos.html" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Ir a productos
                </a>
            </div>
        `;
        
        // Actualizar totales
        subtotalElement.textContent = '$0.00';
        envioElement.textContent = '$0.00';
        totalElement.textContent = '$0.00';
        
        // Deshabilitar botón de pago
        btnPagar.disabled = true;
        return;
    }
    
    // Renderizar cada producto
    carrito.forEach(item => {
        const productoElement = document.createElement('div');
        productoElement.className = 'p-4 border-b border-gray-200 flex items-center';
        productoElement.innerHTML = `
            <img src="${item.imagenUrl || 'assets/img/producto-default.jpg'}" 
                 alt="${item.name}" 
                 class="w-16 h-16 rounded-md object-cover mr-4">
            <div class="flex-1">
                <h3 class="font-medium">${item.name}</h3>
                <p class="text-gray-600">$${item.price} c/u</p>
                <p class="text-sm text-gray-500">${item.categoria}</p>
            </div>
            <div class="flex items-center">
                <button onclick="cambiarCantidad(${item.id}, -1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l-md">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <span class="w-10 h-8 flex items-center justify-center border-t border-b border-gray-300">
                    ${item.cantidad}
                </span>
                <button onclick="cambiarCantidad(${item.id}, 1)" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r-md">
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>
            <div class="ml-4 text-right w-20">
                <p class="font-medium">$${(item.price * item.cantidad).toFixed(2)}</p>
            </div>
            <button onclick="eliminarDelCarrito(${item.id})" class="ml-4 text-red-500 hover:text-red-700">
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
    const envio = subtotal > 70000 ? 0 : 14000; // Envío gratis para compras > $70000
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