document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("nueva-password-form");
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const formMessage = document.getElementById("form-message");
  const submitBtn = document.getElementById("submit-btn");
  const btnText = document.getElementById("btn-text");
  const btnSpinner = document.getElementById("btn-spinner");
  const tokenInput = document.getElementById("token");

  // Validación en tiempo real
 // passwordInput.addEventListener("input", validatePassword);
  confirmPasswordInput.addEventListener("input", validatePasswordMatch);

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    const formData = {
      token: tokenInput.value,
      password: passwordInput.value,
      confirm_password: confirmPasswordInput.value,
    };

    setLoadingState(true);

    try {
      const response = await fetch(
        "../controller/action/act_ActualizarContrasena.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        }
      );

      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        const text = await response.text();
        throw new Error(text || "Respuesta no válida del servidor");
      }

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Error al actualizar la contraseña");
      }

      showSuccessMessage(
        data.message || "Contraseña actualizada correctamente"
      );

      // Redirigir después de 3 segundos
      setTimeout(() => {
        window.location.href = "iniciar_sesion.html";
      }, 3000);
    } catch (error) {
      console.error("Error:", error);
      showMessage(error.message || "Error al procesar la solicitud", "error");
    } finally {
      setLoadingState(false);
    }
  });

  function validateForm() {
    // if (!validatePassword()) {
    //     showMessage('Por favor cumple con todos los requisitos de contraseña', 'error');
    //     return false;
    // }

    if (!validatePasswordMatch()) {
      showMessage("Las contraseñas no coinciden", "error");
      return false;
    }

    if (!tokenInput.value) {
      showMessage("Token de recuperación inválido", "error");
      return false;
    }

    return true;
  }

  // function validatePassword() {
  //     const password = passwordInput.value;
  //     const requirements = {
  //         length: password.length >= 8,
  //         uppercase: /[A-Z]/.test(password),
  //         number: /\d/.test(password),
  //         special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
  //     };

  //     // Actualizar indicadores visuales
  //     document.getElementById('req-length').className = requirements.length ? 'text-green-500' : '';
  //     document.getElementById('req-uppercase').className = requirements.uppercase ? 'text-green-500' : '';
  //     document.getElementById('req-number').className = requirements.number ? 'text-green-500' : '';
  //     document.getElementById('req-special').className = requirements.special ? 'text-green-500' : '';

  //     return Object.values(requirements).every(Boolean);
  // }

  function validatePasswordMatch() {
    const match = passwordInput.value === confirmPasswordInput.value;
    confirmPasswordInput.setCustomValidity(
      match ? "" : "Las contraseñas no coinciden"
    );
    return match;
  }

  function setLoadingState(isLoading) {
    submitBtn.disabled = isLoading;
    btnText.textContent = isLoading
      ? "Procesando..."
      : "Establecer nueva contraseña";
    btnSpinner.classList.toggle("hidden", !isLoading);
  }

  function showMessage(message, type) {
    formMessage.textContent = message;
    formMessage.className =
      "mt-4 text-sm text-center " +
      (type === "success" ? "text-green-600" : "text-red-600");
    formMessage.classList.remove("hidden");
  }

  function showSuccessMessage(message) {
    Swal.fire({
      title: "¡Éxito!",
      text: message,
      icon: "success",
      confirmButtonColor: "#0ea5e9",
      timer: 3000,
    });
    form.reset();
  }
});

function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const icon = field.nextElementSibling;

  if (field.type === "password") {
    field.type = "text";
    icon.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    field.type = "password";
    icon.classList.replace("fa-eye-slash", "fa-eye");
  }
}
