function loadUsersTable() {
    const tableBody = document.querySelector('#usuari .admin-table tbody');
    tableBody.innerHTML = '<tr><td colspan="6">Cargando usuarios...</td></tr>';

    fetch('API/usuarios.php') 
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

function loadPendingPostsTable() {
    const tableBody = document.getElementById('pendingPostsTableBody');
    tableBody.innerHTML = '<tr><td colspan="7">Cargando publicaciones pendientes...</td></tr>';

    fetch('API/Moderacion.php?action=pending') 
        .then(response => {
        
            if (!response.ok) {
              
                return response.json().then(err => { 
                    throw new Error(err.error || `Error HTTP: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(posts => {
          
            console.log("Datos de publicaciones pendientes recibidos:", posts);
            
            if (!Array.isArray(posts)) {
                console.error("La API no devolvió un arreglo.", posts);
                tableBody.innerHTML = '<tr><td colspan="7" style="color: red;">Error: La API devolvió un formato de datos inesperado.</td></tr>';
                return;
            }


            tableBody.innerHTML = ''; 
            if (posts.length === 0) {
                 tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #555;">No hay publicaciones pendientes de aprobación.</td></tr>';
                 return;
            }

            posts.forEach(post => {
           
                const autorDisplay = post.autor || `ID: ${post.idUsuario} (Error)`;
                const mundialDisplay = post.mundial || `ID: ${post.idMundial} (Error)`;
                const categoriaDisplay = post.categoria || `ID: ${post.idCategoria} (Error)`;

                const row = `
                    <tr data-id="${post.idPublicacion}">
                        <td>${post.idPublicacion}</td>
                        <td>
                            <strong title="${post.titulo}">
                                ${post.titulo.substring(0, 30)}${post.titulo.length > 30 ? '...' : ''}
                            </strong>
                        </td>
                        <td>${autorDisplay}</td>
                        <td>${mundialDisplay}</td>
                        <td>${categoriaDisplay}</td>
                        <td>${new Date(post.fechaCreacion).toLocaleDateString()}</td>
                        <td class="table-actions">
                            <button class="table-btn approve-btn" data-id="${post.idPublicacion}" title="Aprobar"><i class="fas fa-check"></i></button>
                            <button class="table-btn reject-btn" data-id="${post.idPublicacion}" title="Rechazar"><i class="fas fa-times"></i></button>
                            <button class="table-btn view-btn" data-id="${post.idPublicacion}" title="Ver Detalle"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error al cargar pendientes:', error);
         
            tableBody.innerHTML = `<tr><td colspan="7" style="color: red;">Error al cargar. ${error.message}</td></tr>`;
        });
}

async function handleModeration(id, action, motivo = null) {
    const url = `API/Moderacion.php?action=${action}`;
    const payload = { idPublicacion: id };
    if (motivo) {
        payload.motivo = motivo;
    }

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message);
            loadPendingPostsTable(); 
        } else {
            alert(data.error || 'Error al procesar la solicitud.');
        }
    } catch (error) {
        console.error(`Error en la acción ${action}:`, error);
        alert(`Error de conexión al intentar ${action}.`);
    }
}

document.addEventListener('DOMContentLoaded', function() {

    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    const contentArea = document.querySelector('.content-area'); 

    function showTab(tabId) {
       
        tabContents.forEach(content => {
            content.style.display = 'none';
        });


        const activeContent = document.getElementById(tabId);
        if (activeContent) {
            activeContent.style.display = 'block'; 

            if (tabId === 'usuari') {
                loadUsersTable();
            } else if (tabId === 'pending-posts') {
                loadPendingPostsTable(); 
            }
        }
        
    }

    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            
            const tabId = this.getAttribute('data-tab');

            tabLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            showTab(tabId);
        });
    });

    contentArea.addEventListener('click', function(e) {
        const target = e.target.closest('.table-btn');
        if (!target) return;

        const id = target.dataset.id;
        if (!id) return;

        if (target.classList.contains('approve-btn')) {
            if (confirm('¿Está seguro de aprobar esta publicación?')) {
                handleModeration(id, 'approve');
            }
        } else if (target.classList.contains('reject-btn')) {
            const motivo = prompt('Ingrese el motivo del rechazo (opcional):');
            if (motivo !== null) { 
                handleModeration(id, 'reject', motivo);
            }
        } else if (target.classList.contains('view-btn')) {
           
             alert(`Funcionalidad para ver el detalle de la publicación #${id} pronto disponible.`);
        }
    });


    const initialActiveLink = document.querySelector('.tab-link.active');
    if (initialActiveLink) {
        const initialTabId = initialActiveLink.getAttribute('data-tab');
        showTab(initialTabId);
    } else if (tabLinks.length > 0) {

        tabLinks[0].click(); 
    }
});