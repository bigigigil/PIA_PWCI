
function loadUsersTable() {
    const tableBody = document.querySelector('#usuari .admin-table tbody');
    tableBody.innerHTML = '<tr><td colspan="6">Cargando usuarios...</td></tr>'; // Mensaje de carga

    fetch('api/usuarios.php') 
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(users => {
            tableBody.innerHTML = ''; 
            users.forEach(user => {
                const row = `
                    <tr>
                        <td>${user.nombre}</td>
                        <td>${user.email}</td>
                        <td>${new Date(user.fechaRegistro).toLocaleDateString()}</td>
                        <td><span class="status-badge status-${user.estatus.toLowerCase()}">${user.estatus}</span></td>
                        <td>${user.rol}</td>
                        <td class="table-actions">
                            <button class="table-btn edit-btn" data-id="${user.id}"><i class="fas fa-edit"></i></button>
                            <button class="table-btn delete-btn" data-id="${user.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error al cargar usuarios:', error);
            tableBody.innerHTML = '<tr><td colspan="6">Error al cargar los datos de usuarios.</td></tr>';
        });
}


document.addEventListener('DOMContentLoaded', function() {

    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');


    function showTab(tabId) {
       
        tabContents.forEach(content => {
            content.style.display = 'none';
        });


        tabLinks.forEach(link => {
            link.classList.remove('active');
        });

 const activeContent = document.getElementById(tabId);
        if (activeContent) {
            activeContent.style.display = 'block'; 

            if (tabId === 'usuari') {
                loadUsersTable();
            }
        }
        
    }

    

    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            
            const tabId = this.getAttribute('data-tab');

       
            showTab(tabId);
            this.classList.add('active');
        });
    });

    const initialActiveLink = document.querySelector('.tab-link.active');
    if (initialActiveLink) {
        const initialTabId = initialActiveLink.getAttribute('data-tab');
        showTab(initialTabId);
    } else if (tabLinks.length > 0) {

        tabLinks[0].click(); 
    }
});