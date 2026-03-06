$(()=>{
        // Elementos
        const body = document.body;
        const modalOverlay = document.getElementById('registerModal');
        const openBtn = document.getElementById('openRegisterModal');
        const closeBtn = document.getElementById('closeRegisterModal');
        const backToLogin = document.getElementById('backToLogin');

        // Abrir modal
        openBtn.addEventListener('click', function() {
            modalOverlay.classList.add('active');
            body.classList.add('modal-open');
        });

        // Función para cerrar modal
        function closeModal() {
            modalOverlay.classList.remove('active');
            body.classList.remove('modal-open');
        }

        // Cerrar con botón X
        closeBtn.addEventListener('click', closeModal);

        // Cerrar con "Inicia sesión" dentro del modal
        backToLogin.addEventListener('click', closeModal);

        // Cerrar al hacer clic fuera del modal (en el overlay)
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });

        // Prevenir envío del formulario para demostración
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Formulario enviado (demostración)');
            });
        });

        // Simular validación en tiempo real (solo para demostrar)
        const emailInput = document.getElementById('floatingEmail');
        const emailError = document.querySelector('.email-error');

        emailInput.addEventListener('input', function() {
            const email = this.value;
            const isValid = email.includes('@') && email.includes('.');

            if (isValid || email === '') {
                this.classList.remove('error');
                emailError.style.display = 'none';
            } else {
                this.classList.add('error');
                emailError.style.display = 'flex';
            }
        });
})