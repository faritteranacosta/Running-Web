$(document).ready(function () {
    $(document).on('click', '#register', function() {

        const nombre = $('#inputName').val().trim();
        const apellido = $('#inputSurname').val().trim();
        const correo = $('#inputEmail').val().trim();
        const contrasena = $('#inputPassword').val().trim();
        const repetirContrasena = $('#inputConfirm-password').val().trim();
        const sexo = $('#sexo').val();
        const fecha_nacimiento = $('#fecha_nacimiento').val();
        const rol = $('#tipo_usuario').val();

        
        let nombre_tienda = $('#inputEmpresa').val().trim();
        if(rol === "vendedor"){
            nombre_tienda = $('#inputEmpresa').val().trim();
            console.log("El usuario es vendedor mi amor");
            if (nombre_tienda === "") {
                alert("Por favor, completa el campo de nombre de tienda.");
                return;
            }
        }

        if (
            nombre !== "" &&
            apellido !== "" &&
            correo !== "" &&
            contrasena !== "" &&
            repetirContrasena !== "" &&
            sexo !== "" &&
            fecha_nacimiento !== "" &&
            rol !== ""
        ) {
            if (contrasena !== repetirContrasena) {
                alert("Las contraseñas no coinciden.");
                return;
            }
            console.log(nombre, apellido, sexo, rol, correo, contrasena, nombre_tienda);
   
            ajaxRegister(nombre, apellido, sexo, fecha_nacimiento, rol, correo, contrasena, nombre_tienda);
        } else {
            alert("Por favor, completa todos los campos.");
        }
    });
});

function ajaxRegister(nombre, apellido, sexo, fecha_nacimiento, rol, correo, contrasena, nombre_tienda="") {
    $.ajax({
        url: '/Running-Web_v/controller/action/ajax_register.php',
        type: 'POST',
        data: {
            nombre: nombre,
            apellido: apellido,
            sexo: sexo,
            fecha_nacimiento: fecha_nacimiento,
            rol: rol,
            correo: correo,
            contrasena: contrasena,
            nombre_tienda: nombre_tienda
        },
        dataType: 'json'
    }).done(function (response) {
        if (response.message) {
            alert(response.message);
            if (response.ruta) {
                window.location.href = response.ruta;
            }
        } else {
            alert("Respuesta inesperada del servidor.");
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Fallo en la solicitud AJAX:");
        console.error("Estado:", textStatus);
        console.error("Error:", errorThrown);
        console.error("Respuesta del servidor:", jqXHR.responseText);
    });
}
