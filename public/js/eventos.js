document.addEventListener("DOMContentLoaded", function () {
  cargarEventos();
});

function cargarEventos() {
  fetch("../api/eventos", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  })
    .then(async (response) => {
      // Verificar estado de la respuesta
      if (!response.ok) {
        const error = await response.json().catch(() => null);
        throw new Error(error?.message || `Error HTTP: ${response.status}`);
      }

      // Verificar tipo de contenido
      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        throw new TypeError("La respuesta no es JSON válido");
      }

      return response.json();
    })
    .then((eventos) => {
      if (!eventos || !Array.isArray(eventos)) {
        throw new Error("Formato de datos inválido");
      }

      const contenedor = document.querySelector(".contenedor");
      const destacadoContenedor = document.getElementById("evento-destacado");

      if (eventos.length === 0) {
        contenedor.innerHTML = `
          <div class="catalog-empty">
            <i class="fas fa-calendar-xmark"></i>
            <p>No hay eventos disponibles por ahora.</p>
          </div>
        `;
        destacadoContenedor.innerHTML = `
          <div class="featured-loading">
            <p>No hay evento destacado por ahora.</p>
          </div>
        `;
        return;
      }

      contenedor.innerHTML = "";

      // El primer evento próximo se usa como evento destacado
      let eventoDestacado = null;

      eventos.forEach((evento) => {
        const fecha = evento.fecha ? new Date(evento.fecha) : new Date();
        const fechaFormateada = fecha.toLocaleDateString("es-ES", {
          day: "2-digit",
          month: "long",
          year: "numeric",
        });

        // Determinar el estado del evento
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaEvento = new Date(evento.fecha);
        fechaEvento.setHours(0, 0, 0, 0);

        let estado = "";
        let claseEstado = "";

        if (fechaEvento > hoy) {
          estado = "Próximo";
          claseEstado = "status-proximo";
          if (!eventoDestacado) {
            eventoDestacado = { ...evento, fechaFormateada, estado, claseEstado };
          }
        } else if (fechaEvento.getTime() === hoy.getTime()) {
          estado = "En curso";
          claseEstado = "status-en-curso";
        } else {
          estado = "Finalizado";
          claseEstado = "status-finalizado";
        }

        const tarjeta = document.createElement("div");
        tarjeta.className = "event-card";
        tarjeta.innerHTML = `
    <div class="event-card-media">
        <img src="../public/assets/img/runner9.png" alt="${evento.nombre || "Evento"}">
    </div>
    <div class="event-card-body">
        <div class="event-card-top">
            <h3>${evento.nombre || "Nombre no disponible"}</h3>
            <span class="status ${claseEstado}">${estado}</span>
        </div>
        <p class="event-card-desc">${evento.descripcion || "Descripción no disponible"}</p>

        <div class="event-card-meta">
            <div><i class="fas fa-calendar-day"></i> <span>${fechaFormateada}</span></div>
            <div><i class="far fa-clock"></i> <span>${evento.hora || "Hora no especificada"}</span></div>
            <div><i class="fas fa-running"></i> <span>${evento.tipo || "Tipo no especificado"}</span></div>
            <div><i class="fas fa-map-marker-alt"></i> <span>${evento.direccion || "Dirección no especificada"}</span></div>
        </div>

        <button class="event-card-cta">
            <i class="fas fa-info-circle"></i> Ver detalles
        </button>
    </div>
`;

        tarjeta.addEventListener("click", () => {
          console.log("Evento seleccionado:", evento);
        });

        contenedor.appendChild(tarjeta);
      });

      // Renderizar el evento destacado
      if (eventoDestacado) {
        destacadoContenedor.innerHTML = `
          <img src="../public/assets/img/runner9.png" alt="${eventoDestacado.nombre || "Evento"}">
          <div class="featured-event-body">
            <span class="status ${eventoDestacado.claseEstado}">${eventoDestacado.estado}</span>
            <h3>${eventoDestacado.nombre || "Nombre no disponible"}</h3>
            <p>${eventoDestacado.descripcion || "Descripción no disponible"}</p>
            <div class="featured-event-meta">
              <div><i class="fas fa-calendar-day"></i> <span>${eventoDestacado.fechaFormateada}</span></div>
              <div><i class="far fa-clock"></i> <span>${eventoDestacado.hora || "Hora no especificada"}</span></div>
              <div><i class="fas fa-running"></i> <span>${eventoDestacado.tipo || "Tipo no especificado"}</span></div>
              <div><i class="fas fa-map-marker-alt"></i> <span>${eventoDestacado.direccion || "Dirección no especificada"}</span></div>
            </div>
            <button class="btn btn-primary"><i class="fas fa-info-circle"></i> Ver detalles</button>
          </div>
        `;
      } else {
        destacadoContenedor.innerHTML = `
          <div class="featured-loading">
            <p>No hay evento destacado por ahora.</p>
          </div>
        `;
      }
    })
    .catch((error) => {
      console.error("Error:", error);

      const contenedor = document.querySelector(".contenedor");
      if (contenedor) {
        contenedor.innerHTML = `
          <div class="catalog-empty">
            <i class="fas fa-exclamation-triangle"></i>
            <p>No se pudieron cargar los eventos.</p>
          </div>
        `;
      }

      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudieron cargar los eventos. Inténtalo de nuevo más tarde.",
        confirmButtonText: "Entendido",
      });
    });
}