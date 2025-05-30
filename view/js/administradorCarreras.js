document.addEventListener('DOMContentLoaded', () => {
  const categoriaSelect = document.getElementById('categoriaSelect');
  const tipoCarreraSelect = document.getElementById('tipoCarreraSelect');
  const form = document.getElementById('formCarrera');

  const urlDatos = '../controller/action/ajax_tipo_carrera_categoria.php';
  const urlGuardar = '../controller/action/ajax_carreras.php';

  // Cargar categorías y tipos de carrera
  function cargarDatos() {
    fetch(urlDatos)
      .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Limpiar selects primero
          categoriaSelect.innerHTML = '<option value="">Seleccione categoría</option>';
          tipoCarreraSelect.innerHTML = '<option value="">Seleccione tipo de carrera</option>';

          // Llenar categorías
          data.categorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.nombre;
            categoriaSelect.appendChild(option);
          });

          // Llenar tipos de carrera
          data.tiposCarrera.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id;
            option.textContent = tipo.nombre;
            tipoCarreraSelect.appendChild(option);
          });
          
        } else {
          throw new Error(data.error || 'No se pudieron cargar los datos');
        }
      })
      .catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'Error al cargar datos',
          text: err.message
        });
      });
  }

  // Validar formulario antes de enviar
  function validarFormulario(formData) {
    const camposRequeridos = ['nombre', 'fecha', 'hora', 'descripcion', 'direccion', 
                             'idCategoria', 'idTipoCarrera', 'distancia', 'idRuta'];
    
    for (const campo of camposRequeridos) {
      if (!formData.get(campo)) {
        throw new Error(`El campo ${campo} es requerido`);
      }
    }
    
    return true;
  }

  // Envío del formulario con fetch
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    try {
      const formData = new FormData(form);
      formData.set('action', 'crear');
      
      // Validar antes de enviar
      validarFormulario(formData);
      
      const response = await fetch(urlGuardar, {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json'
        }
      });
      
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Error HTTP ${response.status}: ${errorText}`);
      }
      
      const data = await response.json();
      
      if (!data.success) {
        throw new Error(data.error || 'Error desconocido del servidor');
      }
      
      // Éxito
      Swal.fire({
        icon: 'success',
        title: 'Carrera creada exitosamente',
        text: 'ID de la carrera: ' + data.id,
        confirmButtonText: 'Aceptar'
      });
      form.reset();
      document.getElementById('idRuta').value = '';
      
    } catch (err) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.message
      });
      
      // Mostrar error detallado en la interfaz
      const errorContainer = document.getElementById('error-message') || 
                            document.createElement('div');
      errorContainer.id = 'error-message';
      errorContainer.style.color = 'red';
      errorContainer.style.marginTop = '10px';
      errorContainer.textContent = err.message;
      
      if (!document.getElementById('error-message')) {
        form.appendChild(errorContainer);
      }
    }
  });

  // Inicializar
  cargarDatos();
});