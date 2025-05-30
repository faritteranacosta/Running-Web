let paginaActual = 1;
const usuariosPorPagina = 20;
async function obtenerUsuarios(pagina = 1, porPagina = 20) {
  try {
    const response = await fetch(
      `../controller/action/ajax_usuarios.php?pagina=${pagina}&porPagina=${porPagina}`
    );
    if (!response.ok) {
      throw new Error("Error al obtener usuarios");
    }
    const data = await response.json();
    //{ usuarios: [...], total: 123 }
    console.log("Datos obtenidos:", data);
    return data;
  } catch (error) {
    console.error("Error:", error);
    return { usuarios: [], total: 0 };
  }
}
// Función para renderizar la tabla de usuarios
function renderizarTablaUsuarios(usuarios, total) {
  const container = document.getElementById("usuarios-table-container");
  if (!container) return;

  if (!usuarios || usuarios.length === 0) {
    container.innerHTML = `
            <div class="no-users-message bg-gradient-to-r from-purple-100 to-blue-100 p-8 rounded-2xl shadow-inner text-center">
                <div class="text-5xl mb-4">👻</div>
                <p class="text-xl font-medium text-gray-700">No hay usuarios, fantasmas por aquí...</p>
                <p class="text-gray-500 mt-2">¿Por qué no agregas uno?</p>
            </div>
        `;
    return;
  }

  // Efecto de tarjetas que se elevan al hover
  const rows = usuarios
    .map(
      (usuario) => `
        <tr class="user-row transform transition-all duration-300">
            <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-900 bg-gradient-to-r from-white to-gray-50 rounded-l-2xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold mr-3">
                        ${usuario.nombre.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        ${usuario.nombre}
                        <div class="text-xs text-gray-500">${usuario.rol}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700 font-medium">${
              usuario.apellido || "N/A"
            }</td>
            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">
                <a href="mailto:${
                  usuario.email
                }" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    ${usuario.email}
                </a>
            </td>
            <td class="px-6 py-5 whitespace-nowrap">
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    ${
                      usuario.sexo === "Masculino"
                        ? "bg-blue-100 text-blue-800"
                        : usuario.sexo === "Femenino"
                        ? "bg-pink-100 text-pink-800"
                        : "bg-purple-100 text-purple-800"
                    }">
                    ${usuario.sexo || "Otro"}
                </span>
            </td>
            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">
                <div class="flex items-center">
                    <div class="mr-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    ${usuario.fechaNacimiento || "N/A"}
                </div>
            </td>
        </tr>
    `
    )
    .join("");

  const totalPaginas = Math.ceil(total / usuariosPorPagina);

  const paginacion = `
    <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-b-lg mt-4" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-700">
                Mostrando <span class="font-medium">${(paginaActual - 1) * usuariosPorPagina + 1}</span> a 
                <span class="font-medium">${Math.min(paginaActual * usuariosPorPagina, total)}</span> de 
                <span class="font-medium">${total}</span> usuarios
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end">
            <button ${paginaActual === 1 ? 'disabled' : ''} onclick="cambiarPagina(${paginaActual - 1})"
                class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold ${
                    paginaActual === 1 
                    ? 'text-gray-300 cursor-not-allowed' 
                    : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0'
                }">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Anterior
            </button>
            <button ${paginaActual === totalPaginas ? 'disabled' : ''} onclick="cambiarPagina(${paginaActual + 1})"
                class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold ${
                    paginaActual === totalPaginas 
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
    <div class="table-wrapper bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 w-full h-full flex flex-col">
        <div class="table-header bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <p class="text-blue-100 text-sm mt-1">Mostrando ${usuarios.length} de ${total} usuarios</p>
        </div>
        
        <div class="overflow-x-auto flex-grow">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nacimiento</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${rows}
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container bg-gray-50 px-6 py-4 border-t border-gray-200">
            ${paginacion}
        </div>
    </div>`;
}

// Función para obtener usuarios y mostrarlos en la tabla
async function mostrarUsuarios() {
  const data = await obtenerUsuarios(paginaActual, usuariosPorPagina);
  renderizarTablaUsuarios(data.usuarios, data.total);
}
window.cambiarPagina = function (nuevaPagina) {
  paginaActual = nuevaPagina;
  mostrarUsuarios();
};

window.eliminarUsuario = async function (idUsuario) {
  if (!confirm("¿Estás seguro de que deseas eliminar este usuario?")) return;
  try {
    const response = await fetch("../controller/action/ajax_usuarios.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ idUsuario }),
    });
    const data = await response.json();
    console.log("Se recibio", data);
    console.log("Se recibio", response);
    if (data.success) {
      alert("Usuario eliminado correctamente");
      mostrarUsuarios(); // Recarga la tabla
    } else {
      alert(data.error || "Error al eliminar usuario");
    }
  } catch (error) {
    alert("Error al eliminar usuario");
  }
};

async function totalUsuarios() {
    const data = await obtenerUsuarios(1, 1);
    console.log("Total de usuarios:", data);
    document.getElementById("nUsuarios").innerText = data.total;
}



