<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AhorroApp · Iniciar sesión</title>

    <!-- Bootstrap 5 + iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fuente moderna -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link href="/finanzas_bao/assets/css/login.css" rel="stylesheet">
</head>

<body>
    <!-- Fondo con blur controlado por JavaScript -->
    <div class="login-container">
        <div class="login-card">
            <!-- Icono distintivo -->
            <div class="brand-icon">
                <i class="bi bi-piggy-bank-fill"></i>
            </div>

            <h2 class="text-center">AhorroApp</h2>
            <div class="sub-head text-center">Tu futuro empieza con pequeños pasos</div>

            <form id="loginForm">
                <!-- Campo email con estado de error -->
                <div class="form-floating input-icon-wrapper">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" class="form-control" id="floatingEmail" placeholder="nombre@ejemplo.com">
                    <label for="floatingEmail">Correo electrónico</label>
                </div>
                <!-- Mensaje de error visible (ejemplo) -->
                <div class="error-message email-error ">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Formato de correo inválido (ejemplo: usuario@dominio.com)
                </div>

                <!-- Campo contraseña con icono nítido -->
                <div class="form-floating input-icon-wrapper mt-3">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Contraseña">
                    <label for="floatingPassword">Contraseña</label>
                </div>
                <!-- Mensaje de error oculto -->
                <div class="error-message password-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    La contraseña debe tener al menos 8 caracteres
                </div>

                <!-- Checkbox recordar -->
                <div class="form-check mb-4" style="margin-left: 0.3rem; margin-top: 1.5rem;">
                    <input class="form-check-input" type="checkbox" id="rememberCheck" checked>
                    <label class="form-check-label" for="rememberCheck">
                        Mantener sesión iniciada
                    </label>
                </div>

                <!-- Botón principal sólido -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar sesión
                </button>

                <!-- Divisor -->
                <div class="divider">¿Nuevo en AhorroApp?</div>

                <!-- Botones secundarios con jerarquía reducida -->
                <div class="footer-links">
                    <!-- Botón ghost para crear cuenta (abre modal) -->
                    <button type="button" class="text-link" id="openRegisterModal">
                        <i class="bi bi-person-plus-fill"></i> Crear cuenta
                    </button>

                    <!-- Enlace de texto para recuperar contraseña -->
                    <button type="button" class="text-link">
                        <i class="bi bi-question-circle-fill"></i> ¿Olvidaste tu contraseña?
                    </button>
                </div>

                <!-- Nota de seguridad con icono -->
                <p class="security-note">
                    <i class="bi bi-shield-check"></i> Tus ahorros están seguros con cifrado de grado financiero
                </p>
            </form>
        </div>
    </div>

    <!-- Modal de registro con blur -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal-card">
            <button class="modal-close" id="closeRegisterModal">
                <i class="bi bi-x-lg"></i>
            </button>

            <h3 class="modal-title">Crear cuenta</h3>
            <p class="modal-subtitle">Comienza a ahorrar hoy mismo</p>

            <form>
                <!-- Nombre completo -->
                <div class="form-floating input-icon-wrapper mb-3">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" class="form-control" id="registerName" placeholder="Tu nombre">
                    <label for="registerName">Nombre completo</label>
                </div>

                <!-- Email -->
                <div class="form-floating input-icon-wrapper mb-3">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" class="form-control" id="registerEmail" placeholder="correo@ejemplo.com">
                    <label for="registerEmail">Correo electrónico</label>
                </div>

                <!-- Contraseña -->
                <div class="form-floating input-icon-wrapper mb-3">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" class="form-control" id="registerPassword" placeholder="Contraseña">
                    <label for="registerPassword">Contraseña</label>
                </div>

                <!-- Confirmar contraseña -->
                <div class="form-floating input-icon-wrapper mb-4">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" class="form-control" id="registerConfirmPassword" placeholder="Confirmar contraseña">
                    <label for="registerConfirmPassword">Confirmar contraseña</label>
                </div>

                <!-- Términos y condiciones -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="termsCheck">
                    <label class="form-check-label" for="termsCheck" style="color: #1e1b4b; font-size: 0.95rem;">
                        Acepto los <a href="#" style="color: #4f46e5; text-decoration: none;">Términos y condiciones</a>
                    </label>
                </div>

                <!-- Botón de registro -->
                <button type="submit" class="btn-login w-100">
                    <i class="bi bi-person-check me-2"></i> Registrarme
                </button>

                <!-- Enlace para volver al login -->
                <p class="text-center mt-3" style="color: #6b7280;">
                    ¿Ya tienes cuenta?
                    <button type="button" class="text-link" id="backToLogin" style="display: inline; width: auto; padding: 0 0.3rem; font-weight: 600;">
                        Inicia sesión
                    </button>
                </p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS y script para el modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js">
    </script>
    <script src="../../../assets/js/login.js"></script>
</body>

</html>