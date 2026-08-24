function cargarEventos() {
  fetch("../controller/action/ajax_eventos.php", {
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
    .then((eventos) => {
      const contenedor = document.getElementById("events-container");
      contenedor.innerHTML = "";

      if (!eventos || !Array.isArray(eventos)) {
        contenedor.innerHTML = "<p class='empty-msg'>No hay eventos próximos</p>";
        return;
      }

      // Obtener fecha actual (sin horas/minutos/segundos)
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0);

      // Filtrar solo eventos futuros
      const eventosProximos = eventos.filter(evento => {
        if (!evento.fecha) return false;
        const fechaEvento = new Date(evento.fecha);
        fechaEvento.setHours(0, 0, 0, 0);
        return fechaEvento >= hoy;
      });

      if (eventosProximos.length === 0) {
        contenedor.innerHTML = "<p class='empty-msg'>No hay eventos próximos</p>";
        return;
      }

      // Ordenar eventos por fecha (más cercanos primero)
      eventosProximos.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));

      // Mostrar solo eventos próximos (máximo 5)
      eventosProximos.slice(0, 5).forEach((evento) => {
        const fecha = new Date(evento.fecha);
        const fechaFormateada = fecha.toLocaleDateString("es-ES", {
          day: "2-digit",
          month: "long",
          year: "numeric",
        });

        const fechaEvento = new Date(evento.fecha);
        fechaEvento.setHours(0, 0, 0, 0);

        let estado = "";
        let claseEstado = "";

        if (fechaEvento > hoy) {
          estado = "Próximo";
          claseEstado = "status-proximo";
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
            <img src="assets/img/runner9.png" alt="${evento.nombre || "Evento"}">
            <div class="event-card-body">
              <div class="event-card-top">
                <h3>${evento.nombre || "Sin nombre"}</h3>
                <span class="status ${claseEstado}">${estado}</span>
              </div>
              <div class="event-card-meta">
                <i class="fas fa-calendar-day"></i>
                <span>${fechaFormateada}</span>
                <i class="fas fa-map-marker-alt icon-sep"></i>
                <span>${evento.direccion || "Sin ubicación"}</span>
              </div>
            </div>
        `;

        tarjeta.addEventListener("click", () => {
          window.location.href = `detalles.php?id=${evento.id}`;
        });

        contenedor.appendChild(tarjeta);
      });
    })
    .catch((error) => {
      console.error("Error:", error);
      const contenedor = document.getElementById("events-container");
      contenedor.innerHTML = "<p class='empty-msg empty-error'>Error al cargar eventos.</p>";
    });
}


function eliminarParticipacion(idEvento) {
    Swal.fire({
        title: '¿Eliminar participación?',
        text: '¿Estás seguro de que deseas eliminar tu participación en este evento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("../controller/action/ajax_eliminar_participacion.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_evento=${encodeURIComponent(idEvento)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: 'Tu participación fue eliminada correctamente',
                        confirmButtonColor: '#28a745',
                        timer: 1500,
                        showConfirmButton: false,
                        willClose: () => {
                            cargarCarrerasProgramadas(); // Recarga solo la tabla
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'No se pudo eliminar la participación',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

// Cargar carreras programadas del usuario
function cargarCarrerasProgramadas() {
  fetch("../controller/action/ajax_participaciones.php", {
    method: "GET",
    headers: { Accept: "application/json" },
    credentials: "same-origin"
  })
    .then(async (response) => {
      if (!response.ok) {
        const error = await response.json().catch(() => null);
        throw new Error(error?.error || `Error HTTP: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      const tbody = document.getElementById("races-table-body");
      tbody.innerHTML = "";
      if (!data.success || !Array.isArray(data.participaciones) || data.participaciones.length === 0) {
        tbody.innerHTML = `<tr><td colspan='5' class='empty-msg'>No tienes carreras programadas.</td></tr>`;
        return;
      }
      
      data.participaciones.forEach((participacion) => {
        // Estado de la carrera según la fecha
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const fechaCarrera = new Date(participacion.fecha_evento);
        fechaCarrera.setHours(0, 0, 0, 0);
        
        let estado = "";
        let claseEstado = "";
        if (fechaCarrera > hoy) {
          estado = "Próximo";
          claseEstado = "status-proximo";
        } else if (fechaCarrera.getTime() === hoy.getTime()) {
          estado = "En curso";
          claseEstado = "status-en-curso";
        } else {
          estado = "Finalizado";
          claseEstado = "status-finalizado";
        }
        
        tbody.innerHTML += `
          <tr>
            <td>${participacion.nombre_evento}</td>
            <td>${new Date(participacion.fecha_evento).toLocaleDateString('es-ES')}</td>
            <td>${participacion.distancia || '-'}</td>
            <td><span class='status ${claseEstado}'>${estado}</span></td>
            <td>
                <button class='link-action' onclick='window.location.href="detalles.php?id=${participacion.id_carrera || ""}"'>Ver detalles</button>
                <button class='link-danger' onclick='eliminarParticipacion(${participacion.id_evento})'>Eliminar</button>
            </td>
          </tr>
        `;
      });
    })
    .catch((error) => {
      const tbody = document.getElementById("races-table-body");
      tbody.innerHTML = `<tr><td colspan='5' class='empty-msg empty-error'>Error al cargar tus carreras.<br><small>${error.message}</small></td></tr>`;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  cargarEventos();
  cargarCarrerasProgramadas();
});