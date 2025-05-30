document.querySelector('.form-container form').addEventListener('submit', function(e) {
    e.preventDefault();

    const correo = document.getElementById('email').value;
    const contrasena = document.getElementById('password').value;
    
    // Validación básica de campos
    if (!correo || !contrasena) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor ingresa tanto el correo como la contraseña',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Mostrar loader durante la autenticación
    Swal.fire({
        title: 'Iniciando sesión',
        html: 'Validando tus credenciales...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('../controller/action/ajax_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            user: correo, 
            pass: contrasena 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        Swal.close();
        
        if (data.type === "success") {
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: data.msg,
                confirmButtonColor: '#28a745',
                showConfirmButton: false,
                timer: 1500,
                willClose: () => {
                    window.location.href = data.ruta;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error de autenticación',
                text: data.msg || 'Credenciales incorrectas',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Intentar nuevamente'
            });
        }
    })
    .catch((error) => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Intenta nuevamente más tarde.',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    });
});

const menuBtn = document.getElementById("menu-btn");
const mobileMenu = document.getElementById("mobile-menu");

menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
});