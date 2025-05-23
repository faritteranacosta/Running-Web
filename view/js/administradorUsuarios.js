let paginaActual = 1;
const usuariosPorPagina = 20;
async function obtenerUsuarios(pagina = 1, porPagina = 20) {
    try {
        const response = await fetch(`../controller/action/ajax_usuarios.php?pagina=${pagina}&porPagina=${porPagina}`);
        if (!response.ok) {
            throw new Error('Error al obtener usuarios');
        }
        const data = await response.json();
        // Espera que el backend devuelva: { usuarios: [...], total: 123 }
        return data;
    } catch (error) {
        console.error('Error:', error);
        return { usuarios: [], total: 0 };
    }
}
// Función para renderizar la tabla de usuarios
function renderizarTablaUsuarios(usuarios, total) {
    const container = document.getElementById('usuarios-table-container');
    if (!container) return;

    if (!usuarios || usuarios.length === 0) {
        container.innerHTML = '<p class="text-gray-500">No hay usuarios registrados.</p>';
        return;
    }

    let rows = usuarios.map(usuario => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.nombre}</td>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.apellido}</td>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.email}</td>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.sexo}</td>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.fechaNacimiento}</td>
            <td class="px-6 py-4 whitespace-nowrap">${usuario.rol}</td>
        </tr>
    `).join('');

    const totalPaginas = Math.ceil(total / usuariosPorPagina);

    let paginacion = `
    <nav class="flex justify-center mt-6" aria-label="Paginación">
        <ul class="inline-flex -space-x-px text-sm">
            <li>
                <button ${paginaActual === 1 ? 'disabled' : ''} onclick="cambiarPagina(${paginaActual - 1})"
                    class="px-3 py-1 rounded-l border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActual === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                    Anterior
                </button>
            </li>
            <li>
                <span class="px-3 py-1 border-t border-b border-gray-300 bg-white text-gray-700">
                    Página ${paginaActual} de ${totalPaginas}
                </span>
            </li>
            <li>
                <button ${paginaActual === totalPaginas ? 'disabled' : ''} onclick="cambiarPagina(${paginaActual + 1})"
                    class="px-3 py-1 rounded-r border border-gray-300 bg-white text-gray-500 hover:bg-blue-50 hover:text-blue-600 ${paginaActual === totalPaginas ? 'opacity-50 cursor-not-allowed' : ''}">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nacimiento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                ${rows}
            </tbody>
        </table>
        ${paginacion}
    `;
}

// Función para obtener usuarios y mostrarlos en la tabla
async function mostrarUsuarios() {
    const data = await obtenerUsuarios(paginaActual, usuariosPorPagina);
    renderizarTablaUsuarios(data.usuarios, data.total);
}
window.cambiarPagina = function(nuevaPagina) {
    paginaActual = nuevaPagina;
    mostrarUsuarios();
};

window.eliminarUsuario = async function(idUsuario) {
    if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) return;
    try {
        const response = await fetch('../controller/action/ajax_usuarios.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idUsuario })
        });
        const data = await response.json();
        console.log("Se recibio", data);
        console.log("Se recibio", response);
        if (data.success) {
            alert('Usuario eliminado correctamente');
            mostrarUsuarios(); // Recarga la tabla
        } else {
            alert(data.error || 'Error al eliminar usuario');
        }
    } catch (error) {
        alert('Error al eliminar usuario');
    }
};