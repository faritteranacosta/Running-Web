const API_URL = "../controller/action/ajax_carreras.php";

export function insertarCarrera(data) {
  return fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "insertar",
      ...data,
    }),
  }).then((r) => r.json());
}

export function actualizarCarrera(data) {
  return fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "actualizar",
      ...data,
    }),
  }).then((r) => r.json());
}

export function eliminarCarrera(idCarrera) {
  return fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "eliminar",
      idCarrera,
    }),
  }).then((r) => r.json());
}

export function obtenerCarreraPorId(idCarrera) {
  return fetch(
    `${API_URL}?action=obtener&idCarrera=${encodeURIComponent(idCarrera)}`
  ).then((r) => r.json());
}

export function listarCarreras() {
  return fetch(`${API_URL}?action=listar`).then((r) => r.json());
}

// Renderizado dinámico de carreras en carreras.html

document.addEventListener("DOMContentLoaded", function () {
  cargarCarreras();
});

function cargarCarreras() {
  fetch("../controller/action/ajax_carreras.php?action=listar", {
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
    .then((carreras) => {
      if (!carreras || !Array.isArray(carreras)) {
        throw new Error("Formato de datos inválido");
      }

      const contenedor = document.querySelector(".contenedor");
      contenedor.innerHTML = "";

      carreras.forEach((carrera) => {
        let carreraId;

        // Intenta encontrar el ID en varios nombres comunes
        if (carrera.idCarrera) {
          carreraId = carrera.idCarrera;
        } else if (carrera.id) {
          carreraId = carrera.id;
        } else if (carrera.carrera_id) {
          carreraId = carrera.carrera_id;
        } else if (carrera.id_carrera) {
          carreraId = carrera.id_carrera;
        } else {
          carreraId = null; // Si no se encuentra ningún ID
        }

        const fecha = carrera.fecha ? new Date(carrera.fecha) : null;
        const fechaFormateada = fecha
          ? fecha.toLocaleDateString("es-ES", {
              day: "2-digit",
              month: "long",
              year: "numeric",
            })
          : "Fecha por definir";

        // Determinar estado de la carrera
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaCarrera = fecha ? new Date(carrera.fecha) : null;
        let estado = "";
        let claseEstado = "";

        if (!fechaCarrera) {
          estado = "Por definir";
          claseEstado = "status-pendiente";
        } else {
          fechaCarrera.setHours(0, 0, 0, 0);

          if (fechaCarrera > hoy) {
            estado = "Próxima";
            claseEstado = "status-proxima";
          } else if (fechaCarrera.getTime() === hoy.getTime()) {
            estado = "Hoy";
            claseEstado = "status-hoy";
          } else {
            estado = "Finalizada";
            claseEstado = "status-finalizada";
          }
        }

        const tarjeta = document.createElement("div");
        tarjeta.className =
          "bg-white rounded-xl shadow-md overflow-hidden event-card card-hover";

        let botonParticipar = "";
        if (carreraId && (!fechaCarrera || fechaCarrera > hoy)) {
          botonParticipar = `
                <a href="detalles.php?id=${carreraId}" class="block mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-running mr-2"></i> Participar
                </a>
            `;
        } else if (fechaCarrera && fechaCarrera <= hoy) {
          botonParticipar = `
                <button class="mt-4 w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                    ${
                      estado === "Hoy"
                        ? "¡Carrera en curso!"
                        : "Carrera finalizada"
                    }
                </button>
            `;
        } else {
          botonParticipar = `
                <button class="mt-4 w-full bg-gray-300 text-gray-600 py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                    Próximamente
                </button>
            `;
        }

        tarjeta.innerHTML = `
            <div class="relative">
                <img src="${carrera.imagen || "assets/img/runner5.png"}" alt="${
            carrera.nombre || "Carrera"
            }" 
                     class="w-full h-48 object-cover hover:scale-105 transition duration-300">
                <span class="absolute top-2 right-2 ${claseEstado} text-white text-xs font-bold px-3 py-1 rounded-full">
                    ${estado}
                </span>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-800">${
                      carrera.nombre || "Nombre no disponible"
                    }</h3>
                </div>
                <span class="text-blue-600 font-semibold">
                    <i class="fas fa-ruler-horizontal mr-1"></i>
                    Distancia: ${carrera.distancia || "No especificada"}
                </span>
                
                <p class="text-gray-600 mb-3 line-clamp-2">${
                  carrera.descripcion || "Descripción no disponible"
                }</p>
                
                <div class="flex items-center text-gray-500 mb-2">
                    <i class="fas fa-calendar-day mr-2"></i>
                    <span>${fechaFormateada}</span>
                </div>
                
                <div class="flex items-center text-gray-500 mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>${carrera.direccion || "Dirección por definir"}</span>
                </div>
                <div class="flex items-center text-gray-500 mb-2">
                    <i class="fas fa-city mr-2"></i>
                    <span>${carrera.ciudad || "Ciudad por definir"}</span>
                </div>
                
                <div class="flex items-center text-gray-500 mb-4">
                    <i class="fas fa-tag mr-2"></i>
                    <span class="capitalize">${
                      carrera.categoria || "Categoría no especificada"
                    }</span>
                </div>
                
                ${botonParticipar}
            </div>
        `;

        contenedor.appendChild(tarjeta);
      });
    })
    .catch((error) => {
      console.error("Error al cargar carreras:", error);
      const contenedor = document.querySelector(".contenedor");
      contenedor.innerHTML = `
        <div class="col-span-full text-center py-8">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
            <p class="text-gray-600">Error al cargar las carreras. Por favor, inténtalo de nuevo.</p>
            <p class="text-sm text-gray-500 mt-2">${error.message}</p>
        </div>
    `;
    });
}
