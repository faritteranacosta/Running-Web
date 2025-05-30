document.addEventListener('DOMContentLoaded', () => {
  const categoriaSelect = document.getElementById('categoriaSelect');
  const tipoCarreraSelect = document.getElementById('tipoCarreraSelect');
  const form = document.getElementById('formCarrera');

  const urlDatos = '../controller/action/ajax_tipo_carrera_categoria.php';
  const urlGuardar = '../controller/action/ajax_carreras.php';

  // Cargar categorías y tipos de carrera
  function cargarDatos() {
    console.log('📤 Cargando categorías y tipos de carrera...');
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
          
          console.log('✅ Datos cargados correctamente');
        } else {
          throw new Error(data.error || 'No se pudieron cargar los datos');
        }
      })
      .catch(err => {
        console.error('❌ Error al cargar datos:', err);
        alert('Error al cargar datos: ' + err.message);
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
      
      // Verificar datos del formulario
      console.log('📝 Datos a enviar:');
      for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }
      
      // Validar antes de enviar
      validarFormulario(formData);
      
      console.log('🟢 Enviando datos al servidor...');
      
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
      console.log('🔵 Respuesta del servidor:', data);
      
      if (!data.success) {
        throw new Error(data.error || 'Error desconocido del servidor');
      }
      
      // Éxito
      alert('✅ Carrera creada exitosamente con ID: ' + data.id);
      form.reset();
      document.getElementById('idRuta').value = '';
      
    } catch (err) {
      console.error('❌ Error en el envío:', err);
      alert('Error: ' + err.message);
      
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