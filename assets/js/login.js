$(() => {
  // Elementos
  const body = document.body;
  const modalOverlay = document.getElementById("registerModal");
  const openBtn = document.getElementById("openRegisterModal");
  const closeBtn = document.getElementById("closeRegisterModal");
  const backToLogin = document.getElementById("backToLogin");

  // Abrir modal
  openBtn.addEventListener("click", function () {
    modalOverlay.classList.add("active");
    body.classList.add("modal-open");
  });

  // Función para cerrar modal
  function closeModal() {
    modalOverlay.classList.remove("active");
    body.classList.remove("modal-open");
  }

  // Cerrar con botón X
  closeBtn.addEventListener("click", closeModal);

  // Cerrar con "Inicia sesión" dentro del modal
  backToLogin.addEventListener("click", closeModal);

  // Cerrar al hacer clic fuera del modal (en el overlay)
  modalOverlay.addEventListener("click", function (e) {
    if (e.target === modalOverlay) {
      closeModal();
    }
  });

  // Manejar envío del formulario de login
  document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.getElementById("floatingEmail").value;
    const password = document.getElementById("floatingPassword").value;
    $.ajax({
      method: "POST",
      url: "/finanzas_bao/auth/loginUser",
      data: {
        username: email,
        password: password,
      },
      success: function (data) {
        var p = JSON.parse(data);

        if (p.status == "success") {
          window.location.href = p.redirect;
        } else {
          alert("Usuario o contraseña incorrectos");
        }
      },
    });
  });
  // Manejar envío del formulario de registro
  document
    .querySelector("#registerModal form")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const name = document.getElementById("registerName").value;
      const email = document.getElementById("registerEmail").value;
      const password = document.getElementById("registerPassword").value;
      const confirmPassword = document.getElementById(
        "registerConfirmPassword",
      ).value;

      if (password !== confirmPassword) {
        alert("Las contraseñas no coinciden");
        return;
      }
      $.ajax({
        method: "POST",
        url: "/finanzas_bao/auth/register",
        data: {
          username: name,
          correo: email,
          password: password,
        },
        success: function (data) {
          var p = JSON.parse(data);
          // console.log(p.status);
          if (p.status == "success") {
            window.location.href = p.redirect;
          } else {
            alert("Usuario o contraseña incorrectos");
          }
        },
      });
    });

  // Simular validación en tiempo real (solo para demostrar)
  const emailInput = document.getElementById("floatingEmail");
  const emailError = document.querySelector(".email-error");

  emailInput.addEventListener("input", function () {
    const email = this.value;
    const isValid = email.includes("@") && email.includes(".");

    if (isValid || email === "") {
      this.classList.remove("error");
      emailError.style.display = "none";
    } else {
      this.classList.add("error");
      emailError.style.display = "flex";
    }
  });
});
