document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("recuperar-form");
  const emailInput = document.getElementById("recuperar-email");
  const messageDiv = document.getElementById("recuperar-msg");
  const submitBtn = document.getElementById("submit-btn");
  const btnText = document.getElementById("btn-text");
  const btnSpinner = document.getElementById("btn-spinner");

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    // Validación básica
    if (!emailInput.value) {
      showMessage("Por favor ingresa tu correo electrónico", "error");
      return;
    }

    if (!isValidEmail(emailInput.value)) {
      showMessage("Por favor ingresa un correo electrónico válido", "error");
      return;
    }

    // Mostrar loading
    setLoadingState(true);

    try {
      const response = await fetch(
        "../controller/action/act_recuperarContrasena.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "email=" + encodeURIComponent(emailInput.value),
        }
      );
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Error en la solicitud");
      }

      if (data.success) {
        showMessage(
          data.message ||
            "Se ha enviado un enlace de recuperación a tu correo electrónico",
          "success"
        );
        form.reset();
      } else {
        throw new Error(data.message || "Error al procesar la solicitud");
      }
    } catch (error) {
      console.error("Error:", error);
      showMessage(error.message || "Error en la conexión", "error");
    } finally {
      setLoadingState(false);
    }
  });

  function setLoadingState(isLoading) {
    submitBtn.disabled = isLoading;
    btnText.textContent = isLoading
      ? "Enviando..."
      : "Enviar enlace de recuperación";
    btnSpinner.classList.toggle("hidden", !isLoading);
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showMessage(message, type) {
    messageDiv.textContent = message;
    messageDiv.className = "mt-4 text-sm text-center";

    if (type === "success") {
      messageDiv.classList.add("text-green-600");
    } else {
      messageDiv.classList.add("text-red-600");
    }

    messageDiv.classList.remove("hidden");

    // Ocultar el mensaje después de 5 segundos
    setTimeout(() => {
      messageDiv.classList.add("hidden");
    }, 5000);
  }
});
