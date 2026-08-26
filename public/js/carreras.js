const API_URL = "../api/carreras";

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

// Renderizado dinámico de carreras

document.addEventListener("DOMContentLoaded", function () {
  cargarCarreras();
});

function calcularEstado(carrera) {
  const fecha = carrera.fecha ? new Date(carrera.fecha) : null;
  const fechaFormateada = fecha
    ? fecha.toLocaleDateString("es-ES", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      })
    : "Fecha por definir";

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

  return { fecha, fechaFormateada, hoy, fechaCarrera, estado, claseEstado };
}

function renderBotonParticipar(carrera, carreraId, idRuta, estado, fechaCarrera, hoy) {
  if (carreraId && (!fechaCarrera || fechaCarrera > hoy)) {
    return `
      <a href="detalles.php?id=${carreraId}&idRuta=${idRuta}" class="race-cta enabled">
        <i class="fas fa-running"></i> Participar
      </a>
    `;
  } else if (fechaCarrera && fechaCarrera <= hoy) {
    return `
      <button class="race-cta disabled" disabled>
        ${estado === "Hoy" ? "¡Carrera en curso!" : "Carrera finalizada"}
      </button>
    `;
  }
  return `<button class="race-cta disabled" disabled>Próximamente</button>`;
}

function cargarCarreras() {
  fetch("../api/carreras", {
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
      const destacadaContenedor = document.getElementById("carrera-destacada");

      if (carreras.length === 0) {
        contenedor.innerHTML = `
          <div class="catalog-empty">
            <i class="fas fa-flag-checkered"></i>
            <p>No hay carreras disponibles por ahora.</p>
          </div>
        `;
        destacadaContenedor.innerHTML = `
          <div class="featured-loading"><p>No hay carrera destacada por ahora.</p></div>
        `;
        return;
      }

      contenedor.innerHTML = "";
      let carreraDestacada = null;

      carreras.forEach((carrera) => {
        const carreraId = carrera.idCarrera || null;
        const idRuta = carrera.ruta;
        const { fechaFormateada, hoy, fechaCarrera, estado, claseEstado } = calcularEstado(carrera);

        if (!carreraDestacada && estado === "Próxima") {
          carreraDestacada = { ...carrera, carreraId, idRuta, fechaFormateada, estado, claseEstado, fechaCarrera, hoy };
        }

        const botonParticipar = renderBotonParticipar(carrera, carreraId, idRuta, estado, fechaCarrera, hoy);

        const tarjeta = document.createElement("div");
        tarjeta.className = "event-card";
        tarjeta.innerHTML = `
            <div class="race-card-media">
                <img src="${carrera.imagen || "../public/assets/img/runner5.png"}" alt="${carrera.nombre || "Carrera"}">
                <span class="status ${claseEstado}">${estado}</span>
            </div>
            <div class="race-card-body">
                <h3>${carrera.nombre || "Nombre no disponible"}</h3>
                <span class="race-card-distance"><i class="fas fa-ruler-horizontal"></i> ${carrera.distancia || "Distancia no especificada"}</span>
                <p class="race-card-desc">${carrera.descripcion || "Descripción no disponible"}</p>

                <div class="race-card-meta">
                    <div><i class="fas fa-calendar-day"></i> <span>${fechaFormateada}</span></div>
                    <div><i class="fas fa-map-marker-alt"></i> <span>${carrera.ubicacion || "Ubicación por definir"}</span></div>
                    <div><i class="fas fa-tag"></i> <span class="cap">${carrera.categoria || "Categoría no especificada"}</span></div>
                </div>

                ${botonParticipar}
            </div>
        `;

        contenedor.appendChild(tarjeta);
      });

      if (carreraDestacada) {
        const botonDestacado = renderBotonParticipar(
          carreraDestacada,
          carreraDestacada.carreraId,
          carreraDestacada.idRuta,
          carreraDestacada.estado,
          carreraDestacada.fechaCarrera,
          carreraDestacada.hoy
        );
        destacadaContenedor.innerHTML = `
          <img src="${carreraDestacada.imagen || "../public/assets/img/runner5.png"}" alt="${carreraDestacada.nombre || "Carrera"}">
          <div class="featured-race-body">
            <span class="status ${carreraDestacada.claseEstado}">${carreraDestacada.estado}</span>
            <h3>${carreraDestacada.nombre || "Nombre no disponible"}</h3>
            <span class="featured-race-distance"><i class="fas fa-ruler-horizontal"></i> ${carreraDestacada.distancia || "Distancia no especificada"}</span>
            <p class="desc">${carreraDestacada.descripcion || "Descripción no disponible"}</p>
            <div class="featured-race-meta">
              <div><i class="fas fa-calendar-day"></i> <span>${carreraDestacada.fechaFormateada}</span></div>
              <div><i class="fas fa-map-marker-alt"></i> <span>${carreraDestacada.ubicacion || "Ubicación por definir"}</span></div>
              <div><i class="fas fa-tag"></i> <span class="cap">${carreraDestacada.categoria || "Categoría no especificada"}</span></div>
            </div>
            ${botonDestacado}
          </div>
        `;
      } else {
        destacadaContenedor.innerHTML = `
          <div class="featured-loading"><p>No hay carrera destacada por ahora.</p></div>
        `;
      }
    })
    .catch((error) => {
      const contenedor = document.querySelector(".contenedor");
      contenedor.innerHTML = `
        <div class="catalog-empty">
            <i class="fas fa-exclamation-triangle"></i>
            <p>Error al cargar las carreras. Por favor, inténtalo de nuevo.</p>
            <p class="error-detail">${error.message}</p>
        </div>
    `;
    });
}