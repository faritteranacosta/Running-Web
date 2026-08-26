document.addEventListener("DOMContentLoaded", function() {
    const userId = document.getElementById("userId").value;

    fetch(`../api/productos?vendedor_id=${userId}`, {
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
                    <td colspan="7" class="table-empty">
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
                estadoClass = "ok";
                estadoTexto = "Disponible";
            } else if (producto.stock > 0) {
                estadoClass = "low";
                estadoTexto = "Poco stock";
            } else {
                estadoClass = "out";
                estadoTexto = "Agotado";
            }

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <div class="prod-cell">
                        <img src="${producto.imagenUrl || '../public/assets/img/default-product.png'}" alt="${producto.name}">
                        <div>
                            <div class="name">${producto.name}</div>
                            <div class="sku">#PRD-${producto.id.toString().padStart(3, '0')}</div>
                        </div>
                    </div>
                </td>
                <td class="cell-muted">${producto.categoria || 'Sin categoría'}</td>
                <td class="cell-num">$${producto.price.toFixed(2)}</td>
                <td><span class="stock-pill ${estadoClass}">${producto.stock}</span></td>
                <td class="cell-num cell-muted">0</td> <!-- Aquí irían las ventas si las tuvieras -->
                <td><span class="stock-pill ${estadoClass}">${estadoTexto}</span></td>
                <td class="cell-muted">
                    <!-- Eliminados los botones de acción como solicitaste -->
                </td>
            `;
            tableBody.appendChild(row);
        });
    })
    .catch((error) => {
        const tableBody = document.querySelector(".product-table tbody");
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="table-error">
                    Error al cargar los productos: ${error.message}
                </td>
            </tr>
        `;
    });
});