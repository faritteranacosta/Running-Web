document.addEventListener("DOMContentLoaded", function () {
  cargarEventos();
});

function cargarEventos() {
  fetch("../controller/action/ajax_eventos.php", {
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
      contenedor.innerHTML = "";

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
        } else if (fechaEvento.getTime() === hoy.getTime()) {
          estado = "En curso";
          claseEstado = "status-en-curso";
        } else {
          estado = "Finalizado";
          claseEstado = "status-finalizado";
        }

        const tarjeta = document.createElement("div");
        tarjeta.className = "bg-white rounded-xl shadow-md overflow-hidden event-card card-hover";
        tarjeta.innerHTML = `
    <div class="h-48 overflow-hidden">
        <img src="${"assets/img/runner9.png"}" alt="${evento.nombre || "Evento"}" 
             class="w-full h-full object-cover hover:scale-105 transition duration-300">
    </div>
    <div class="p-6">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-800">${evento.nombre || "Nombre no disponible"}</h3>
            <span class="status ${claseEstado}">${estado}</span>
        </div>
        <p class="text-gray-600 mb-4 line-clamp-2">${evento.descripcion || "Descripción no disponible"}</p>
        
        <div class="flex items-center text-gray-500 mb-3">
            <i class="fas fa-calendar-day mr-2"></i>
            <span>${fechaFormateada}</span>
        </div>
        
        <div class="flex items-center text-gray-500 mb-3">
            <i class="far fa-clock mr-2"></i>
            <span>${evento.hora || "Hora no especificada"}</span>
        </div>
        
        <div class="flex items-center text-gray-500 mb-3">
            <i class="fas fa-running mr-2"></i>
            <span>${evento.tipo || "Tipo no especificado"}</span>
        </div>
        
        <div class="flex items-center text-gray-500 mb-3">
            <i class="fas fa-map-marker-alt mr-2"></i>
            <span>${evento.direccion || "Dirección no especificada"}</span>
        </div>
    </div>
    <div class="p-4 bg-gray-50 border-t border-gray-200">
        <button class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center justify-center">
            <i class="fas fa-info-circle mr-2"></i>
            Ver detalles
        </button>
    </div>
`;

        // Añadir evento de clic si es necesario
        tarjeta.addEventListener("click", () => {
          // Aquí puedes añadir la lógica para mostrar más detalles del evento
          console.log("Evento seleccionado:", evento);
        });

        contenedor.appendChild(tarjeta);
      });
    })
    .catch((error) => {
      console.error("Error:", error);

      Swal.fire({
        icon: "error",
        title: "Error",
        html: `No se pudieron cargar los eventos.<br><small>${error.message}</small>`,
        confirmButtonText: "Entendido",
      });
    });

}