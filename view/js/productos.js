 // Datos iniciales de productos
let products = [];
// Variables de paginación
let currentPage = 1;
const itemsPerPage = 5; // Número de productos por página

// Función para actualizar la paginación
function updatePagination() {
    const totalItems = products.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Actualizar contadores
    document.getElementById('total-items').textContent = totalItems;
    document.getElementById('showing-from').textContent = ((currentPage - 1) * itemsPerPage) + 1;
    document.getElementById('showing-to').textContent = Math.min(currentPage * itemsPerPage, totalItems);
    
    // Actualizar botones de navegación
    document.getElementById('prev-btn').disabled = currentPage === 1;
    document.getElementById('next-btn').disabled = currentPage === totalPages;
    
    // Generar números de página
    const paginationControls = document.getElementById('pagination-controls');
    const pageNumberContainer = document.createElement('div');
    pageNumberContainer.className = 'flex space-x-2';
    
    // Limpiar números de página existentes (excepto los botones prev/next)
    while (paginationControls.children.length > 2) {
        paginationControls.removeChild(paginationControls.children[1]);
    }
    
    // Determinar rango de páginas a mostrar (máximo 5)
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    
    // Ajustar si estamos cerca del final
    if (endPage - startPage < 4 && startPage > 1) {
        startPage = Math.max(1, endPage - 4);
    }
    
    // Botón para la primera página si no está visible
    if (startPage > 1) {
        const firstPageBtn = document.createElement('button');
        firstPageBtn.className = `px-3 py-1 border border-gray-300 rounded-md ${1 === currentPage ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'}`;
        firstPageBtn.textContent = '1';
        firstPageBtn.onclick = () => goToPage(1);
        if (startPage > 2) {
            firstPageBtn.after(document.createTextNode('...'));
        }
        pageNumberContainer.appendChild(firstPageBtn);
    }
    
    // Botones para las páginas en el rango
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `px-3 py-1 border rounded-md ${i === currentPage ? 'bg-primary-600 text-white' : 'border-gray-300 text-gray-600 hover:bg-gray-100'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => goToPage(i);
        pageNumberContainer.appendChild(pageBtn);
    }
    
    // Botón para la última página si no está visible
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            pageNumberContainer.appendChild(document.createTextNode('...'));
        }
        const lastPageBtn = document.createElement('button');
        lastPageBtn.className = `px-3 py-1 border border-gray-300 rounded-md ${totalPages === currentPage ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'}`;
        lastPageBtn.textContent = totalPages;
        lastPageBtn.onclick = () => goToPage(totalPages);
        pageNumberContainer.appendChild(lastPageBtn);
    }
    
    // Insertar los números de página entre los botones prev/next
    paginationControls.insertBefore(pageNumberContainer, document.getElementById('next-btn'));
}

// Funciones de navegación
function goToPage(page) {
    currentPage = page;
    renderProducts();
    updatePagination();
}

function previousPage() {
    if (currentPage > 1) {
        goToPage(currentPage - 1);
    }
}

function nextPage() {
    const totalPages = Math.ceil(products.length / itemsPerPage);
    if (currentPage < totalPages) {
        goToPage(currentPage + 1);
    }
}


function cargarProductos() {
    fetch("../controller/action/ajax_productos.php")
        .then(response => response.json())
        .then(data => {
            // Suponiendo que data es un array de productos
            products = data.map(item => ({
                id: item.id,
                name: item.name,
                description: item.description,
                price: parseFloat(item.price),
                category: item.categoria,
                vendedor_id: item.vendedor_id,
                stock: parseInt(item.stock),
                image: item.imagenUrl,
                status: item.status || (
                    parseInt(item.stock) === 0 ? 'out-of-stock' :
                        parseInt(item.stock) < 10 ? 'low-stock' : 'active'
                )
            }));
            renderProducts();
        })
        .catch(error => {
            console.error("Error al cargar productos:", error);
        });
}

// Cargar productos usando AJAX (fetch)
document.addEventListener("DOMContentLoaded", () => {
    cargarProductos();
    renderProducts();
    updatePagination();
    setupEventListeners();
});

// DOM Elements
const productList = document.getElementById("product-list");
const productForm = document.getElementById("product-form");
const menuBtn = document.getElementById("menu-btn");
const mobileMenu = document.getElementById("mobile-menu");
const toggleFormBtn = document.getElementById("toggle-form");
let editIndex = -1;

// Initialize
document.addEventListener("DOMContentLoaded", () => {
    renderProducts();
    setupEventListeners();
});

// Functions
function renderProducts() {
    productList.innerHTML = "";

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, products.length);
    const productsToShow = products.slice(startIndex, endIndex);

    productsToShow.forEach((product, index) => {
        const statusClass = {
            'active': 'bg-green-100 text-green-800',
            'low-stock': 'bg-yellow-100 text-yellow-800',
            'out-of-stock': 'bg-red-100 text-red-800'
        }[product.status || 'active'];

        const statusText = {
            'active': 'Disponible',
            'low-stock': 'Bajo stock',
            'out-of-stock': 'Agotado'
        }[product.status || 'active'];

        const tr = document.createElement("tr");
        tr.className = "hover:bg-gray-50 smooth-transition";
        tr.innerHTML = `
    <td class="py-4 px-6">
    <div class="flex items-center">
        <img src="${product.image}" alt="${product.name}" class="w-12 h-12 rounded-md object-cover mr-4">
        <div>
        <div class="font-medium text-gray-900">${product.name}</div>
        <div class="text-sm text-gray-500 line-clamp-1">${product.description}</div>
        </div>
    </div>
    </td>
    <td class="py-4 px-6">
    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">${product.category}</span>
    </td>
    <td class="py-4 px-6 font-medium">$${product.price.toFixed(2)}</td>
    <td class="py-4 px-6">${product.stock}</td>
    <td class="py-4 px-6">
    <span class="px-3 py-1 text-xs rounded-full ${statusClass}">${statusText}</span>
    </td>
    <td class="py-4 px-6 text-center">
    <div class="flex justify-center space-x-2">
        <button aria-label="Editar producto ${product.name}" class="text-yellow-500 hover:text-yellow-700" onclick="editProduct(${index})">
              <i class="fas fa-edit"></i>
        </button>
        <button onclick="deleteProduct(${index})" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full smooth-transition" title="Eliminar">
             <i class="fas fa-trash-alt"></i>
        </button>
        <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-full smooth-transition" title="Ver detalles">
            <i class="fas fa-eye"></i>
        </button>
    </div>
    </td>
`;
        productList.appendChild(tr);
    });
    
    // Actualizar paginación   
    updatePagination();

}

function editProduct(index) {
    const product = products[index];
    editIndex = index;

    // Rellenar el formulario
    document.getElementById("product-name").value = product.name;
    document.getElementById("product-description").value = product.description;
    document.getElementById("product-price").value = product.price;
    document.getElementById("product-category").value = product.category;
    document.getElementById("product-stock").value = product.stock;
    document.getElementById("product-image").value = product.image;

    // Cambiar el texto del botón
    const submitBtn = productForm.querySelector("button[type=submit]");
    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Actualizar Producto';
    submitBtn.classList.remove('bg-primary-600');
    submitBtn.classList.add('bg-secondary-600', 'hover:bg-secondary-700');

    // Scroll al formulario
    document.getElementById("product-form").scrollIntoView({ behavior: 'smooth' });
}

function deleteProduct(index) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: `Estás a punto de eliminar "${products[index].name}". Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const productId = products[index].id;
            fetch('../controller/action/ajax_productos.php', {
                method: 'delete',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'delete', id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        products.splice(index, 1);
                        renderProducts();
                        if (editIndex === index) {
                            clearForm();
                        }
                        Swal.fire(
                            'Eliminado!',
                            'El producto ha sido eliminado.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el producto.',
                            'error'
                        );
                    }
                })
                .catch(() => {
                    Swal.fire(
                        'Error',
                        'Ocurrió un error al eliminar el producto.',
                        'error'
                    );
                });
        }
    });
}

function clearForm() {
    productForm.reset();
    editIndex = -1;
    const submitBtn = productForm.querySelector("button[type=submit]");
    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Guardar Producto';
    submitBtn.classList.remove('bg-secondary-600', 'hover:bg-secondary-700');
    submitBtn.classList.add('bg-primary-600', 'hover:bg-primary-700');
}

function setupEventListeners() {
    // Mobile menu toggle
    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
    });

    // Form toggle
    toggleFormBtn.addEventListener("click", () => {
        const form = productForm;
        form.classList.toggle("hidden");
        toggleFormBtn.innerHTML = form.classList.contains("hidden") ?
            '<i class="fas fa-chevron-down"></i>' : '<i class="fas fa-chevron-up"></i>';
    });

    // Form Actualizar y agregar 
    productForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const name = productForm["productName"].value.trim();
        const description = productForm["productDescription"].value.trim();
        const price = parseFloat(productForm["productPrice"].value);
        const category = productForm["productCategory"].value;
        const stock = parseInt(productForm["productStock"].value);
        const image = productForm["productImage"].value.trim();

        // Validación...
        if (!name || !description || isNaN(price) || price < 0 || !category || isNaN(stock) || stock < 0 || !image) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor completa todos los campos correctamente',
                confirmButtonColor: '#ef4444'
            });
            return;
        }
        // Si es edición
        if (editIndex >= 0) {
            const productId = products[editIndex].id;
            fetch('../controller/action/ajax_productos.php', {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id: productId,
                    name: name,
                    description: description,
                    price: price,
                    vendedor_id: products[editIndex].vendedor_id, // Cambia esto por el id real del vendedor/logueado si lo tienes
                    category: category,
                    stock: stock,
                    image: image
                })
               
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualiza el producto en el array local y vuelve a renderizar
                        products[editIndex] = {
                            ...products[editIndex],
                            name,
                            description,
                            price,
                            category,
                            stock,
                            image
                        };
                        renderProducts();
                        clearForm();
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado!',
                            text: 'El producto ha sido actualizado',
                            confirmButtonColor: '#22c55e'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg || 'No se pudo actualizar el producto',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al actualizar el producto.',
                        confirmButtonColor: '#ef4444'
                    });
                });
            return;
        } else {
            // ...dentro del else...
            fetch('../controller/action/ajax_productos.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    name: name,
                    description: description,
                    price: price,
                    vendedor_id: 17, // Cambia esto por el id real del vendedor/logueado si lo tienes
                    category: category,
                    stock: stock,
                    image: image
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Opcional: recargar productos desde el servidor o agregar el nuevo producto al array local
                        Swal.fire({
                            icon: 'success',
                            title: 'Agregado!',
                            text: 'Nuevo producto agregado',
                            confirmButtonColor: '#22c55e'
                        });
                        // Recargar productos desde el servidor para mantener sincronizado
                        cargarProductos();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg || 'No se pudo agregar el producto',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al agregar el producto.',
                        confirmButtonColor: '#ef4444'
                    });
                });
        }

        // Determinar estado
        let status = 'active';
        if (stock === 0) status = 'out-of-stock';
        else if (stock < 10) status = 'low-stock';

        const productData = {
            id: editIndex >= 0 ? products[editIndex].id : Date.now(),
            name,
            description,
            price,
            category: category,
            stock,
            image,
            status
        };

        if (editIndex >= 0) {
            products[editIndex] = productData;
            Swal.fire({
                icon: 'success',
                title: 'Actualizado!',
                text: 'El producto ha sido actualizado',
                confirmButtonColor: '#22c55e'
            });
        } else {
            products.unshift(productData);
            Swal.fire({
                icon: 'success',
                title: 'Agregado!',
                text: 'Nuevo producto agregado',
                confirmButtonColor: '#22c55e'
            });
        }

        renderProducts();
        clearForm();
    });
}

// Make functions available globally
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.clearForm = clearForm;