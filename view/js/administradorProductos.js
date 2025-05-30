let paginaActualProductos = 1;
const productosPorPagina = 20;

async function obtenerProductos(pagina = 1, porPagina = 20) {
    
   
    try {
        const response = await fetch(`../controller/action/ajax_productos_paginados.php?pagina=${pagina}&porPagina=${porPagina}`);
        if (!response.ok) {
            throw new Error('Error al obtener productos');
        }
        const data = await response.json();
        console.log('Datos obtenidos:', data);
        return data; // { productos: [...], total: 123 }
    } catch (error) {
        console.error('Error:', error);
        return { productos: [], total: 0 };
    }
}

function renderizarTablaProductos(productos, total) {
    const container = document.getElementById('productos-table-container');
    if (!container) return;

    if (!productos || productos.length === 0) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12 bg-white rounded-lg shadow-md">
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m-8-4l8 4"></path>
                </svg>
                <p class="text-xl font-medium text-gray-600">No hay productos registrados</p>
                <p class="text-gray-500 mt-2">Los productos que agregues aparecerán aquí</p>
            </div>`;
        return;
    }

    let rows = productos.map(producto => `
        <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-md flex items-center justify-center text-gray-500 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m-8-4l8 4"></path>
                        </svg>
                    </div>
                    <div class="font-medium text-gray-800">${producto.nombre}</div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-gray-600 max-w-xs truncate">${producto.descripcion}</div>
            </td>
            <td class="px-6 py-4">
                <div class="font-medium text-gray-900">$${parseFloat(producto.precio).toFixed(2)}</div>
            </td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    parseInt(producto.stock) > 10 ? 'bg-green-100 text-green-800' : 
                    parseInt(producto.stock) > 5 ? 'bg-yellow-100 text-yellow-800' : 
                    'bg-red-100 text-red-800'
                }">
                    ${producto.stock} unidades
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                    ${producto.categoria}
                </span>
            </td>
        </tr>
    `).join('');

    const totalPaginas = Math.ceil(total / productosPorPagina);

    let paginacion = `
    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-b-lg mt-4" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700">
                Mostrando <span class="font-medium">${(paginaActualProductos - 1) * productosPorPagina + 1}</span> a 
                <span class="font-medium">${Math.min(paginaActualProductos * productosPorPagina, total)}</span> de 
                <span class="font-medium">${total}</span> productos
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end">
            <button ${paginaActualProductos === 1 ? 'disabled' : ''} onclick="cambiarPaginaProducto(${paginaActualProductos - 1})"
                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold ${
                    paginaActualProductos === 1 
                    ? 'text-gray-300 cursor-not-allowed' 
                    : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'
                }">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Anterior
            </button>
            <button ${paginaActualProductos === totalPaginas ? 'disabled' : ''} onclick="cambiarPaginaProducto(${paginaActualProductos + 1})"
                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold ${
                    paginaActualProductos === totalPaginas 
                    ? 'text-gray-300 cursor-not-allowed' 
                    : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'
                }">
                Siguiente
                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </nav>
    `;

    container.innerHTML = `
        <div class="bg-white overflow-hidden shadow-md rounded-lg border border-gray-200 h-full flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 flex-shrink-0">
                <h2 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Productos
                </h2>
                <p class="text-blue-100 text-sm mt-1">Gestión de inventario</p>
            </div>
            <div class="overflow-x-auto flex-grow">
                <table class="w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Descripción</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Precio</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Categoría</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        ${rows}
                    </tbody>
                </table>
            </div>
            <div class="pagination-container bg-gray-50 px-6 py-4 border-t border-gray-200 flex-shrink-0">
                ${paginacion}
            </div>
        </div>
    `;
}

async function mostrarProductos() {
    const data = await obtenerProductos(paginaActualProductos, productosPorPagina);
    renderizarTablaProductos(data.productos, data.total);
}

window.cambiarPaginaProducto = function(nuevaPagina) {
    paginaActualProductos = nuevaPagina;
    mostrarProductos();
};

window.eliminarProducto = async function(idProducto) {
    try {
        
        // Confirmación con SweetAlert en lugar de confirm()
        const confirmResult = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });
        
        if (!confirmResult.isConfirmed) return;
        
        const response = await fetch('../controller/action/ajax_productos_paginados.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idProducto })
        });
        const data = await response.json();
        
        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'Producto eliminado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
            mostrarProductos(); // Recarga la tabla
        } else {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Error al eliminar producto'
            });
        }
    } catch (error) {
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al eliminar producto'
        });
    }
};
