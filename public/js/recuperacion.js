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
        "../api/password/recover",
        {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "email=" + encodeURIComponent(emailInput.value),
        }
      );
      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        const text = await response.text();
        console.error("Respuesta no JSON del servidor:", text);
        throw new Error("El servidor no devolvió una respuesta válida. Inténtalo de nuevo más tarde.");
      }

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
      showMessage(error.message || "No se pudo procesar la solicitud. Inténtalo de nuevo más tarde.", "error");
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
    messageDiv.className = "auth-msg show " + (type === "success" ? "success" : "error");

    // Ocultar el mensaje después de 5 segundos
    setTimeout(() => {
      messageDiv.classList.remove("show");
    }, 5000);
  }
});
