// Variables globales
let currentPage = 1;
const productsPerPage = 8;
let allProducts = [];
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

// Función para actualizar el contador del carrito
function actualizarContadorCarrito() {
  const totalItems = carrito.reduce((total, item) => total + item.cantidad, 0);
  const cartBadge = document.querySelector(".cart-badge");

  if (cartBadge) {
    cartBadge.textContent = totalItems;
    cartBadge.style.display = totalItems > 0 ? "flex" : "none";
  }
}

// Función para guardar el carrito en localStorage
function guardarCarrito() {
  localStorage.setItem("carrito", JSON.stringify(carrito));
}

// Función para agregar producto al carrito
function agregarAlCarrito(producto) {
  // Verificar si el producto ya está en el carrito
  const itemExistente = carrito.find((item) => item.id === producto.id);

  if (itemExistente) {
    // Si ya existe, aumentar la cantidad si hay stock disponible
    if (itemExistente.cantidad < producto.stock) {
      itemExistente.cantidad += 1;
      mostrarNotificacion(
        "¡Actualizado!",
        "Se ha aumentado la cantidad en tu carrito",
        "success"
      );
    } else {
      mostrarNotificacion(
        "Stock insuficiente",
        "No hay suficiente stock disponible",
        "warning"
      );
      return;
    }
  } else {
    // Si no existe, agregarlo al carrito
    carrito.push({
      id: producto.id,
      name: producto.name,
      price: producto.price,
      imagenUrl: producto.image,
      categoria: producto.categoria,
      cantidad: 1,
    });
    mostrarNotificacion(
      "¡Agregado!",
      "El producto se ha añadido al carrito",
      "success"
    );
  }

  // Actualizar localStorage y contador
  guardarCarrito();
  actualizarContadorCarrito();
}

// Función para mostrar notificación
function mostrarNotificacion(titulo, mensaje, tipo) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-center",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: tipo,
    title: mensaje,
  });
}

// Función para cargar productos con paginación
async function loadProducts(page = 1, category = "all") {
  try {
    currentPage = page;
    const response = await fetch("../controller/action/ajax_productos.php");
    const data = await response.json();
    allProducts = data;

    // Filtrar por categoría si es necesario
    const filteredProducts =
      category === "all"
        ? allProducts
        : allProducts.filter((p) => p.categoria.toLowerCase() === category);

    // Calcular índices para la paginación
    const startIndex = (page - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

    // Renderizar productos
    renderProducts(paginatedProducts);

    // Actualizar paginador
    updatePagination(filteredProducts.length);
  } catch (error) {
    console.error("Error al cargar productos:", error);
    document.getElementById("todos-productos").innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Error al cargar los productos. Por favor, inténtalo de nuevo.</p>
            </div>
        `;
  }
}

// Función para renderizar productos
function renderProducts(products) {
  const container = document.getElementById("todos-productos");

  if (products.length === 0) {
    container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">No se encontraron productos en esta categoría.</p>
            </div>
        `;
    return;
  }

  container.innerHTML = products
    .map(
      (producto) => `
        <div class="product-card bg-white rounded-xl shadow-md overflow-hidden">
            <div class="relative">
                <img src="${producto.image ? producto.image : 'assets/img/default_product.jpg'}" alt="${
        producto.name
      }" class="w-full h-48 object-cover">
                ${
                  producto.stock <= 0
                    ? `
                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    AGOTADO
                </span>`
                    : ""
                }
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg">${producto.name}</h3>
                <p class="text-gray-500 text-sm capitalize">${producto.categoria ? producto.categoria.toLowerCase() : 'Sin categoría'}</p>
                <p class="text-gray-600 mt-2 line-clamp-2">${
                  producto.description
                }</p>
                <div class="mt-3 flex justify-between items-center">
                    <span class="text-blue-600 font-bold">$${
                      producto.price
                    }</span>
                    <span class="text-gray-500 text-sm">${
                      producto.stock
                    } disponibles</span>
                </div>
                
                <button onclick="agregarAlCarrito(${JSON.stringify(
                  producto
                ).replace(/"/g, "&quot;")})" 
                    class="mt-3 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition 
                    ${
                      producto.stock <= 0 ? "opacity-50 cursor-not-allowed" : ""
                    }" 
                    ${producto.stock <= 0 ? "disabled" : ""}>
                    <i class="fas fa-cart-plus mr-2"></i> 
                    ${producto.stock <= 0 ? "Agotado" : "Añadir al carrito"}
                </button>
            </div>
        </div>
    `
    )
    .join("");
}

// Función para actualizar el paginador
function updatePagination(totalProducts) {
  const totalPages = Math.ceil(totalProducts / productsPerPage);
  const paginationContainer = document.querySelector(".pagination-container");

  if (!paginationContainer) return;

  let paginationHTML = `
        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 
            ${currentPage === 1 ? "opacity-50 cursor-not-allowed" : ""}"
            ${currentPage === 1 ? "disabled" : ""}
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
            ${startPage > 2 ? '<span class="px-2">...</span>' : ""}
        `;
  }

  // Botones de páginas
  for (let i = startPage; i <= endPage; i++) {
    paginationHTML += `
            <button class="px-3 py-1 ${
              currentPage === i
                ? "bg-blue-500 text-white"
                : "border border-gray-300"
            } rounded-lg hover:bg-gray-100"
                onclick="loadProducts(${i})">
                ${i}
            </button>
        `;
  }

  // Botón para la última página si no está visible
  if (endPage < totalPages) {
    paginationHTML += `
            ${endPage < totalPages - 1 ? '<span class="px-2">...</span>' : ""}
            <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100"
                onclick="loadProducts(${totalPages})">
                ${totalPages}
            </button>
        `;
  }

  // Botón siguiente
  paginationHTML += `
        <button class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100 
            ${
              currentPage === totalPages ? "opacity-50 cursor-not-allowed" : ""
            }"
            ${currentPage === totalPages ? "disabled" : ""}
            onclick="loadProducts(${currentPage + 1})">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

  paginationContainer.innerHTML = paginationHTML;
}

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
  // Cargar productos iniciales
  loadProducts();
  actualizarContadorCarrito();

  // Filtros por categoría
  document.querySelectorAll(".category-filter").forEach((button) => {
    button.addEventListener("click", function () {
      document.querySelectorAll(".category-filter").forEach((btn) => {
        btn.classList.remove("active");
      });
      this.classList.add("active");

      const category = this.dataset.category;
      loadProducts(1, category);
    });
  });

  // Barra de búsqueda
  const searchInput = document.querySelector('input[type="text"]');
  searchInput.addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const productCards = document.querySelectorAll(".product-card");

    productCards.forEach((card) => {
      const title = card.querySelector("h3").textContent.toLowerCase();
      const description = card
        .querySelector("p.text-gray-600")
        .textContent.toLowerCase();

      if (title.includes(searchTerm) || description.includes(searchTerm)) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });
});

// Toggle Mobile Menu
document
  .getElementById("mobile-menu-button")
  .addEventListener("click", function () {
    const menu = document.getElementById("mobile-menu");
    menu.classList.toggle("hidden");
  });

// Hacer funciones accesibles globalmente
window.agregarAlCarrito = agregarAlCarrito;
window.loadProducts = loadProducts;
