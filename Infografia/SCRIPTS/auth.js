document.addEventListener('DOMContentLoaded', () => {
  
    const navLinks = document.querySelector('.nav-link ul');

    if (!navLinks) {
        return; 
    }

    const adminLink = navLinks.querySelector('a[href="admin.php"]')?.parentElement;
    const blogLink = navLinks.querySelector('a[href="blog.php"]')?.parentElement;
    const loginLink = navLinks.querySelector('a[href="log-In.html"]')?.parentElement;

    fetch('API/session_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
              
                if (data.rol !== 'Admin' && adminLink) {
                    adminLink.style.display = 'none';
                }

                if (loginLink) {
              
                    loginLink.innerHTML = `
                        <li>
                            <a href="perfil.php" id="profileLink">
                                <i class="bi bi-person-circle"></i> MI PERFIL (${data.username})
                            </a>
                        </li>
                        <li>
                            <a href="#" id="logoutBtn">
                                <i class="bi bi-box-arrow-right"></i> CERRAR SESIÓN
                            </a>
                        </li>`;
                    
                    document.getElementById('logoutBtn').addEventListener('click', (e) => {
                        e.preventDefault();
                        fetch('API/logout.php')
                            .then(() => {
                                window.location.href = 'index.html'; 
                            });
                    });
                }

            } else {
      
                if (adminLink) {
                    adminLink.style.display = 'none';
                }

                if (blogLink) {
                    blogLink.style.display = 'none';
                }
            }
        });
});