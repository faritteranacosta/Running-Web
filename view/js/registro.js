document.querySelector('.form-container form').addEventListener('submit', function(e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const apellido = document.getElementById('apellido').value;
    const correo = document.getElementById('email').value;
    const contrasena = document.getElementById('password').value;
    const repetir = document.getElementById('confirm-password').value;
    const sexo = document.getElementById('sexo').value;
    const fecha = document.getElementById('fechaNacimiento').value;

    // Verificar que las contraseñas coincidan
    if (contrasena !== repetir) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Las contraseñas no coinciden',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Mostrar loader mientras se procesa
    Swal.fire({
        title: 'Procesando registro',
        html: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('../controller/action/ajax-registro.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            nombre: nombre,
            apellido: apellido,
            correo: correo,
            contrasena: contrasena,
            sexo: sexo,
            fecha: fecha
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.type === "success") {
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: data.msg,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Continuar',
                willClose: () => {
                    window.location.href = data.ruta;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error en el registro',
                text: data.msg,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Entendido'
            });
        }
    })
    .catch(() => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    });
});