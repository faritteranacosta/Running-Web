let products = [];
let currentPage = 1;
const itemsPerPage = 5;
const productList = document.getElementById('product-list');
const toggleFormBtn = document.getElementById('toggle-form');
const productForm = document.getElementById('product-form');
const saveProductBtn = document.getElementById('save-product');
const userId = document.getElementById('userId').value;

// Estado del formulario
let isFormOpen = true;

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    setupEventListeners();
});

// Configurar event listeners
function setupEventListeners() {
    // Toggle del formulario
    toggleFormBtn.addEventListener('click', toggleForm);
    
    // Enviar formulario
    productForm.addEventListener('submit', handleFormSubmit);
}

// Alternar visibilidad del formulario
function toggleForm() {
    isFormOpen = !isFormOpen;
    productForm.style.maxHeight = isFormOpen ? '1000px' : '70px';
    toggleFormBtn.innerHTML = isFormOpen ? '<i class="fas fa-chevron-up"></i>' : '<i class="fas fa-chevron-down"></i>';
}

// Manejar envío del formulario
function handleFormSubmit(e) {
    e.preventDefault();
    saveProduct();
}

// Cargar productos
function loadProducts() {
    showLoading(true);
    
    fetch(`../controller/action/ajax_productos.php?vendedor_id=${userId}`)
        .then(handleResponse)
        .then(data => {
            products = data;
            renderProducts();
            renderPagination();
            updateShowingInfo();
        })
        .catch(handleError)
        .finally(() => showLoading(false));
}

// Guardar producto (crear o actualizar)
function saveProduct() {
    const formData = new FormData(productForm);
    const productData = {
        name: formData.get('productName'),
        description: formData.get('productDescription'),
        price: parseFloat(formData.get('productPrice')),
        categoria: formData.get('productCategory'),
        stock: parseInt(formData.get('productStock')),
        image: formData.get('productImage'),
        vendedor_id: userId
    };

    if (!validateProduct(productData)) return;

    const isEdit = productForm.dataset.editId;
  
    const url = isEdit 
        ? `../controller/action/ajax_productos.php?id=${productForm.dataset.editId}`
        : '../controller/action/ajax_productos.php';
    const method = isEdit ? 'PUT' : 'POST';

    showLoading(true, saveProductBtn);

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(productData)
    })
    .then(handleResponse).then(data => {
        showSuccess(isEdit ? 'Producto actualizado' : 'Producto creado');
        loadProducts();
        clearForm();
        if (!isFormOpen) toggleForm();
    })
    .catch(handleError)
    .finally(() => showLoading(false, saveProductBtn));
    
}

// Validar producto
function validateProduct(product) {
    if (!product.name || product.name.length < 3) {
        showError('El nombre debe tener al menos 3 caracteres');
        return false;
    }
    if (!product.categoria) {
        showError('La categoría es requerida');
        return false;
    }
    if (!product.description || product.description.length < 10) {
        showError('La descripción debe tener al menos 10 caracteres');
        return false;
    }
    if (isNaN(product.price) || product.price <= 0) {
        showError('El precio debe ser un número positivo');
        return false;
    }
    if (isNaN(product.stock) || product.stock < 0) {
        showError('El stock debe ser un número positivo');
        return false;
    }
    if (!product.image) {
        showError('La URL de la imagen es requerida');
        return false;
    }
    return true;
}

// Editar producto
function editProduct(productId) {
    const product = products.find(p => p.id == productId);
    if (!product) return;

    document.getElementById('product-name').value = product.name;
    document.getElementById('product-description').value = product.description;
    document.getElementById('product-price').value = product.price;
    document.getElementById('product-stock').value = product.stock;
    document.getElementById('product-image').value = product.image;
    document.getElementById('product-category').value = product.categoria;

    productForm.dataset.editId = productId;
    saveProductBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Actualizar Producto';

    if (!isFormOpen) toggleForm();
    productForm.scrollIntoView({ behavior: 'smooth' });
}

// Eliminar producto
function deleteProduct(productId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`../controller/action/ajax_productos.php?id=${productId}`, {
                method: 'DELETE'
            })
            .then(handleResponse)
            .then(() => {
                showSuccess('Producto eliminado');
                loadProducts();
            })
            .catch(handleError);
        }
    });
}

// Renderizar productos
function renderProducts() {
    productList.innerHTML = '';

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, products.length);
    const paginatedProducts = products.slice(startIndex, endIndex);

    if (paginatedProducts.length === 0) {
        productList.innerHTML = `
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-500">
                    No hay productos disponibles
                </td>
            </tr>
        `;
        return;
    }

    paginatedProducts.forEach(product => {
        const statusClass = {
            active: "bg-green-100 text-green-800",
            "low-stock": "bg-yellow-100 text-yellow-800",
            "out-of-stock": "bg-red-100 text-red-800",
        }[product.status];

        const statusText = {
            active: "Disponible",
            "low-stock": "Bajo stock",
            "out-of-stock": "Agotado",
        }[product.status];

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50';
        tr.innerHTML = `
            <td class="py-4 px-6">
                <div class="flex items-center">
                    <img src="${product.image}" alt="${product.name}" 
                         class="w-12 h-12 rounded-md object-cover mr-4">
                    <div>
                        <div class="font-medium text-gray-900">${product.name}</div>
                        <div class="text-sm text-gray-500 line-clamp-1">
                            ${product.description}
                        </div>
                    </div>
                </div>
            </td>
            <td class="py-4 px-6">
                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                    ${product.categoria}
                </span>
            </td>
            <td class="py-4 px-6 font-medium">$${product.price.toFixed(2)}</td>
            <td class="py-4 px-6">${product.stock}</td>
            <td class="py-4 px-6">
                <span class="px-3 py-1 text-xs rounded-full ${statusClass}">
                    ${statusText}
                </span>
            </td>
            <td class="py-4 px-6 text-center">
                <div class="flex justify-center space-x-2">
                    <button onclick="editProduct('${product.id}')" 
                            class="p-2 text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 rounded-full">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteProduct('${product.id}')" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-full">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </td>
        `;
        productList.appendChild(tr);
    });
}

// Paginación
// Paginación - Versión corregida
function renderPagination() {
    const totalPages = Math.ceil(products.length / itemsPerPage);
    const paginationControls = document.getElementById('pagination-controls');
    
    // Limpiar todos los controles existentes
    paginationControls.innerHTML = '';

    // Crear botón Anterior
    const prevBtn = document.createElement('button');
    prevBtn.id = 'prev-btn';
    prevBtn.className = 'px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100';
    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevBtn.onclick = previousPage;
    prevBtn.disabled = currentPage === 1;
    paginationControls.appendChild(prevBtn);

    // Agregar números de página
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `px-3 py-1 rounded-md ${currentPage === i 
            ? 'bg-primary-600 text-white' 
            : 'border border-gray-300 text-gray-600 hover:bg-gray-100'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => goToPage(i);
        paginationControls.appendChild(pageBtn);
    }

    // Crear botón Siguiente
    const nextBtn = document.createElement('button');
    nextBtn.id = 'next-btn';
    nextBtn.className = 'px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100';
    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
    nextBtn.onclick = nextPage;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    paginationControls.appendChild(nextBtn);
}

function goToPage(page) {
    currentPage = page;
    renderProducts();
    renderPagination();
    updateShowingInfo();
}

function previousPage() {
    if (currentPage > 1) goToPage(currentPage - 1);
}

function nextPage() {
    if (currentPage < Math.ceil(products.length / itemsPerPage)) {
        goToPage(currentPage + 1);
    }
}

function updateShowingInfo() {
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, products.length);
    
    document.getElementById('showing-from').textContent = startIndex;
    document.getElementById('showing-to').textContent = endIndex;
    document.getElementById('total-items').textContent = products.length;
}

// Helpers
function clearForm() {
    productForm.reset();
    delete productForm.dataset.editId;
    saveProductBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Guardar Producto';
}

function handleResponse(response) {
    if (!response.ok) {
        return response.json().then(err => {
            throw new Error(err.msg || 'Error en la solicitud');
        });
    }
    return response.json();
}

function handleError(error) {
    console.error('Error:', error);
    showError(error.message || 'Error en la operación');
}

function showLoading(show, element = null) {
    if (!element) return;
    element.disabled = show;
    if (show) {
        const originalText = element.innerHTML;
        element.dataset.originalText = originalText;
        element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
    } else {
        element.innerHTML = element.dataset.originalText;
    }
}

function showSuccess(message) {
    Swal.fire({
        title: 'Éxito',
        text: message,
        icon: 'success',
        confirmButtonColor: '#0ea5e9',
        timer: 2000
    });
}

function showError(message) {
    Swal.fire({
        title: 'Error',
        text: message,
        icon: 'error',
        confirmButtonColor: '#0ea5e9'
    });
}