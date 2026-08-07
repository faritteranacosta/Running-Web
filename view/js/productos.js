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
                <td colspan="6" class="table-empty">
                    No hay productos disponibles
                </td>
            </tr>
        `;
        return;
    }

    paginatedProducts.forEach(product => {
        const statusClass = {
            active: "active",
            "low-stock": "low-stock",
            "out-of-stock": "out-of-stock",
        }[product.status];

        const statusText = {
            active: "Disponible",
            "low-stock": "Bajo stock",
            "out-of-stock": "Agotado",
        }[product.status];

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="prow-main">
                    <img src="${product.image}" alt="${product.name}">
                    <div>
                        <div class="name">${product.name}</div>
                        <div class="desc">${product.description}</div>
                    </div>
                </div>
            </td>
            <td><span class="cat-pill">${product.categoria}</span></td>
            <td class="cell-num">$${product.price.toFixed(2)}</td>
            <td class="cell-num">${product.stock}</td>
            <td><span class="status-pill ${statusClass}">${statusText}</span></td>
            <td>
                <div class="row-actions">
                    <button onclick="editProduct('${product.id}')" class="act-edit" aria-label="Editar">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button onclick="deleteProduct('${product.id}')" class="act-delete" aria-label="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button class="act-view" aria-label="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </td>
        `;
        productList.appendChild(tr);
    });
}

// Paginación
function renderPagination() {
    const totalPages = Math.ceil(products.length / itemsPerPage);
    const paginationControls = document.getElementById('pagination-controls');

    paginationControls.innerHTML = '';

    const prevBtn = document.createElement('button');
    prevBtn.id = 'prev-btn';
    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevBtn.onclick = previousPage;
    prevBtn.disabled = currentPage === 1;
    paginationControls.appendChild(prevBtn);

    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = currentPage === i ? 'current' : '';
        pageBtn.textContent = i;
        pageBtn.onclick = () => goToPage(i);
        paginationControls.appendChild(pageBtn);
    }

    const nextBtn = document.createElement('button');
    nextBtn.id = 'next-btn';
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