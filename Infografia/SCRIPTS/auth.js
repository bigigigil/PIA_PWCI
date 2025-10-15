// SCRIPTS/auth.js

document.addEventListener('DOMContentLoaded', () => {
    // Seleccionamos los elementos de la barra de navegación que queremos controlar
    const navLinks = document.querySelector('.nav-link ul');
    const adminLink = navLinks.querySelector('a[href="admin.php"]')?.parentElement;
    const blogLink = navLinks.querySelector('a[href="blog.php"]')?.parentElement;
    const loginLink = navLinks.querySelector('a[href="log-In.html"]')?.parentElement;

    // Consultamos nuestro verificador de sesión
    fetch('API/session_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                // --- Usuario Conectado ---

                // 1. Ocultar el enlace de Administrador si no es Admin
                if (data.rol !== 'Admin' && adminLink) {
                    adminLink.style.display = 'none';
                }

                // 2. Reemplazar "Iniciar Sesión" con "Cerrar Sesión" y el nombre de usuario
                if (loginLink) {
                    loginLink.innerHTML = `
                        <a href="#" id="logoutBtn">
                            <i class="bi bi-box-arrow-right"></i> CERRAR SESIÓN (${data.username})
                        </a>`;
                    
                    // Añadir funcionalidad al botón de logout
                    document.getElementById('logoutBtn').addEventListener('click', (e) => {
                        e.preventDefault();
                        fetch('API/logout.php')
                            .then(() => {
                                window.location.href = 'index.html'; // Redirigir al inicio
                            });
                    });
                }

            } else {
                // --- Usuario NO Conectado (Invitado) ---

                // 1. Ocultar el enlace de Administrador
                if (adminLink) {
                    adminLink.style.display = 'none';
                }

                // 2. Ocultar el enlace del Blog
                if (blogLink) {
                    blogLink.style.display = 'none';
                }
            }
        });
});