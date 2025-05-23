let paginaActualProductos = 1;
const productosPorPagina = 20;

async function obtenerProductos(pagina = 1, porPagina = 20) {
    
   
    try {
        const response = await fetch(`../controller/action/ajax_productos_paginados.php?pagina=${pagina}&porPagina=${porPagina}`);
        if (!response.ok) {
            throw new Error('Error al obtener productos');
        }
        const data = await response.json();
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
        container.innerHTML = '<p class="text-gray-500">No hay productos registrados.</p>';
        return;
    }

    let rows = productos.map(producto => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">${producto.nombre}</td>
            <td class="px-6 py-4 whitespace-nowrap">${producto.descripcion}</td>
            <td class="px-6 py-4 whitespace-nowrap">${producto.precio}</td>
            <td class="px-6 py-4 whitespace-nowrap">${producto.stock}</td>
            <td class="px-6 py-4 whitespace-nowrap">${producto.categoria}</td>
        </tr>
    `).join('');

    const totalPaginas = Math.ceil(total / productosPorPagina);

    let paginacion = `
    <nav class="flex justify-center mt-6" aria-label="Paginación">
        <ul class="inline-flex -space-x-px text-sm">
            <li>
                <button ${paginaActualProductos === 1 ? 'disabled' : ''} onclick="cambiarPaginaProducto(${paginaActualProductos - 1})"
                    class="px-3 py-1 rounded-l border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActualProductos === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                    Anterior
                </button>
            </li>
            <li>
                <span class="px-3 py-1 border-t border-b border-gray-300 bg-white text-gray-700">
                    Página ${paginaActualProductos} de ${totalPaginas}
                </span>
            </li>
            <li>
                <button ${paginaActualProductos === totalPaginas ? 'disabled' : ''} onclick="cambiarPaginaProducto(${paginaActualProductos + 1})"
                    class="px-3 py-1 rounded-r border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActualProductos === totalPaginas ? 'opacity-50 cursor-not-allowed' : ''}">
                    Siguiente
                </button>
            </li>
        </ul>
    </nav>
    `;

    container.innerHTML = `
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                ${rows}
            </tbody>
        </table>
        ${paginacion}
    `;
}

async function mostrarProductos() {
    console.log('mostrarProductos ejecutado');
    const data = await obtenerProductos(paginaActualProductos, productosPorPagina);
    renderizarTablaProductos(data.productos, data.total);
}

window.cambiarPaginaProducto = function(nuevaPagina) {
    paginaActualProductos = nuevaPagina;
    mostrarProductos();
};

window.eliminarProducto = async function(idProducto) {
    if (!confirm('¿Estás seguro de que deseas eliminar este producto?')) return;
    try {
        const response = await fetch('../controller/action/ajax_productos_paginados.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idProducto })
        });
        const data = await response.json();
        if (data.success) {
            alert('Producto eliminado correctamente');
            mostrarProductos(); // Recarga la tabla
        } else {
            alert(data.error || 'Error al eliminar producto');
        }
    } catch (error) {
        alert('Error al eliminar producto');
    }
};
