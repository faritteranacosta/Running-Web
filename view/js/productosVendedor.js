document.addEventListener("DOMContentLoaded", function() {
    const userId = document.getElementById("userId").value; 
    
    fetch(`../controller/action/ajax_productos.php?vendedor_id=${userId}`, {
        method: "GET",
        headers: {
            Accept: "application/json",
        },
    })
    .then(async (response) => {
        if (!response.ok) {
            const error = await response.json().catch(() => null);
            throw new Error(error?.message || `Error HTTP: ${response.status}`);
        }

        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("La respuesta no es JSON válido");
        }

        return response.json();
    })
    .then((productos) => {
        const tableBody = document.querySelector(".product-table tbody");
        tableBody.innerHTML = "";

        if (!productos || !Array.isArray(productos) || productos.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No hay productos disponibles
                    </td>
                </tr>
            `;
            return;
        }

        productos.forEach((producto) => {
            // Determinar el estado basado en el stock
            let estadoClass, estadoTexto;
            if (producto.stock > 5) {
                estadoClass = "bg-green-100 text-green-800";
                estadoTexto = "Disponible";
            } else if (producto.stock > 0) {
                estadoClass = "bg-yellow-100 text-yellow-800";
                estadoTexto = "Poco stock";
            } else {
                estadoClass = "bg-red-100 text-red-800";
                estadoTexto = "Agotado";
            }

            // Determinar clase para el stock
            let stockClass = "px-2 inline-flex text-xs leading-5 font-semibold rounded-full ";
            stockClass += producto.stock > 5 ? "bg-green-100 text-green-800" : 
                          producto.stock > 0 ? "bg-yellow-100 text-yellow-800" : 
                          "bg-red-100 text-red-800";

            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-md object-cover" src="${producto.imagenUrl || 'assets/img/default-product.png'}" alt="${producto.name}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${producto.name}</div>
                            <div class="text-sm text-gray-500">#PRD-${producto.id.toString().padStart(3, '0')}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${producto.categoria || 'Sin categoría'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$${producto.price.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="${stockClass}">${producto.stock}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0</td> <!-- Aquí irían las ventas si las tuvieras -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${estadoClass}">${estadoTexto}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <!-- Eliminados los botones de acción como solicitaste -->
                </td>
            `;
            tableBody.appendChild(row);
        });
    })
    .catch((error) => {
        console.error("Error:", error);
        const tableBody = document.querySelector(".product-table tbody");
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-red-500">
                    Error al cargar los productos: ${error.message}
                </td>
            </tr>
        `;
    });
});