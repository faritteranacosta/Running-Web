document.getElementById('toggle-sidebar').addEventListener('click', function () {
  document.querySelector('.sidebar').classList.toggle('sidebar-collapsed');
  document.querySelector('.main-content').classList.toggle('ml-20');
});

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
        contenedor.innerHTML = "<p class='text-gray-500'>No hay eventos próximos</p>";
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
        contenedor.innerHTML = "<p class='text-gray-500'>No hay eventos próximos</p>";
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
        tarjeta.className =
          "bg-white rounded-xl shadow-md overflow-hidden event-card card-hover p-4 flex items-center gap-4";
        tarjeta.innerHTML = `
            <img src="assets/img/runner9.png" alt="${evento.nombre || "Evento"}"
             class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
            <div class="flex-1">
            <div class="flex justify-between items-center mb-1">
                <h3 class="text-base font-semibold text-gray-800">${evento.nombre || "Sin nombre"}</h3>
                <span class="status ${claseEstado}">${estado}</span>
            </div>
            <div class="text-xs text-gray-500 flex items-center gap-2">
                <i class="fas fa-calendar-day"></i>
                <span>${fechaFormateada}</span>
                <i class="fas fa-map-marker-alt ml-3"></i>
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
      contenedor.innerHTML = "<p class='text-red-500'>Error al cargar eventos.</p>";
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
        tbody.innerHTML = `<tr><td colspan='5' class='text-center text-gray-500'>No tienes carreras programadas.</td></tr>`;
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
            <td class='px-6 py-4 whitespace-nowrap'>${participacion.nombre_evento}</td>
            <td class='px-6 py-4 whitespace-nowrap'>${new Date(participacion.fecha_evento).toLocaleDateString('es-ES')}</td>
            <td class='px-6 py-4 whitespace-nowrap'>${participacion.distancia || '-'}</td>
            <td class='px-6 py-4 whitespace-nowrap'><span class='status ${claseEstado}'>${estado}</span></td>
            <td class='px-6 py-4 whitespace-nowrap'>
                <button class='text-blue-500 hover:underline' onclick='window.location.href="detalles.php?id=${participacion.id_carrera || ""}"'>Ver detalles</button>
                <button class='text-red-500 hover:underline ml-2' onclick='eliminarParticipacion(${participacion.id_evento})'>Eliminar</button>
            </td>
          </tr>
        `;
      });
    })
    .catch((error) => {
      const tbody = document.getElementById("races-table-body");
      tbody.innerHTML = `<tr><td colspan='5' class='text-center text-red-500'>Error al cargar tus carreras.<br><small>${error.message}</small></td></tr>`;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  cargarEventos();
  cargarCarrerasProgramadas();
});