// Función para cargar y mostrar los detalles de una publicación pendiente en el modal
async function viewPostDetails(id) {
    const modal = document.getElementById('modalViewPost');
    const loadingHtml = '<div style="text-align: center; padding: 50px;">Cargando detalles... <i class="fas fa-spinner fa-spin"></i></div>';

    // Resetear contenido y mostrar carga
    document.getElementById('previewTitle').textContent = '';
    document.getElementById('previewMultimedia').innerHTML = loadingHtml;
    document.getElementById('previewContent').textContent = '';
    modal.style.display = 'block';

    try {
        const response = await fetch(`API/Moderacion.php?action=view&id=${id}`);
        const data = await response.json();

        if (response.ok) {
            const mediaContainer = document.getElementById('previewMultimedia');
            let mediaHtml = '';

            // Renderizar Multimedia
            if (data.archivoMultimedia) {
                const src = `data:${data.tipoMime || 'application/octet-stream'};base64,${data.archivoMultimedia}`;

                if (data.tipoContenido === 'imagen') {
                    mediaHtml = `<img src="${src}" alt="${data.titulo}" style="max-width: 100%; height: auto; max-height: 400px; border-radius: 8px; object-fit: contain;">`;
                } else if (data.tipoContenido === 'video') {
                    mediaHtml = `
                        <video controls style="width: 100%; height: auto; max-height: 400px; border-radius: 8px;">
                            <source src="${src}" type="${data.tipoMime || 'video/mp4'}">
                            Tu navegador no soporta la etiqueta de video.
                        </video>
                    `;
                }
            } else {
                mediaHtml = `<div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #555;">No hay archivo multimedia adjunto.</div>`;
            }

            // Rellenar contenido de texto
            document.getElementById('previewTitle').textContent = data.titulo;
            document.getElementById('previewAutor').textContent = data.autor || 'N/A';
            document.getElementById('previewMundial').textContent = `${data.mundial || 'N/A'}`;
            document.getElementById('previewCategoria').textContent = `${data.categoria || 'N/A'}`;
            document.getElementById('previewContent').textContent = data.contenido;
            mediaContainer.innerHTML = mediaHtml;

            // Actualizar IDs en botones de moderación del modal
            document.getElementById('approveBtnModal').dataset.id = id;
            document.getElementById('rejectBtnModal').dataset.id = id;

        } else {
            alert(data.error || 'Error al cargar detalles de la publicación. La publicación podría haber sido moderada o eliminada.');
            modal.style.display = 'none';
        }

    } catch (error) {
        console.error('Error fetching post details:', error);
        alert('Error de conexión al cargar la publicación.');
        modal.style.display = 'none';
    }
}

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
        <button class="table-btn edit-user-btn" data-id="${user.id}"><i class="fas fa-edit"></i></button>
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

function setupManagementForms() {
    const forms = [
        { id: 'formCategoria', action: 'categoria' },
        { id: 'formMundial', action: 'mundial' },
        { id: 'formSeleccion', action: 'seleccion' }
    ];

    forms.forEach(item => {
        const formElement = document.getElementById(item.id);

        if (!formElement) {
            console.warn(`El formulario con ID ${item.id} no se encontró en el HTML.`);
            return;
        }

        const newFormElement = formElement.cloneNode(true);
        formElement.parentNode.replaceChild(newFormElement, formElement);

        newFormElement.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log(`Enviando formulario: ${item.id}`); // Log para depuración

            const btn = newFormElement.querySelector('button');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Guardando...';

            const formData = new FormData(newFormElement);

            fetch(`API/gestion_datos.php?action=${item.action}`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status >= 400 || body.error) {
                        alert('Error: ' + (body.error || 'Error desconocido'));
                        console.error('Error respuesta:', body);
                    } else {
                        alert(body.message || '¡Guardado con éxito!');
                        newFormElement.reset();
                    }
                })
                .catch(err => {
                    alert('Error de conexión o sintaxis JSON.');
                    console.error(err);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerText = originalText;
                });
        });
    });
}



document.addEventListener('DOMContentLoaded', function () {
    setupManagementForms();
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
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const tabId = this.getAttribute('data-tab');

            tabLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            showTab(tabId);
        });
    });

    const userTableContainer = document.getElementById('usuari');

    if (userTableContainer) {
        userTableContainer.addEventListener('click', function (e) {

            const btn = e.target.closest('.edit-user-btn');

            if (btn) {
                const idUsuario = btn.dataset.id;

                const modal = document.getElementById('modalEditUser');
                modal.style.display = 'block';

                document.getElementById('edit_nombre').value = "Cargando...";

                fetch(`API/usuarios.php?id=${idUsuario}`)
                    .then(response => response.json())
                    .then(user => {

                        document.getElementById('edit_id').value = user.id;
                        document.getElementById('edit_nombre').value = user.nombre;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_rol').value = user.rol;
                        document.getElementById('edit_estatus').value = user.estatus;
                        document.querySelector('#formEditUser input[name="password"]').value = "";
                    })
                    .catch(err => console.error("Error cargando usuario", err));
            }
        });
    }

    const closeBtns = document.querySelectorAll('.close-btn');
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function () {

            this.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });

    contentArea.addEventListener('click', function (e) {
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

            viewPostDetails(id);
        }
    });
    document.querySelector('#usuari table').addEventListener('click', function (e) {
        const btn = e.target.closest('.table-btn');
        if (!btn) return;
        const id = btn.dataset.id;

        // ACCIÓN: ELIMINAR
        if (btn.classList.contains('delete-btn')) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                fetch(`API/usuarios.php?id=${id}`, { method: 'DELETE' })
                    .then(r => r.json())
                    .then(data => {
                        alert(data.message);
                        loadUsersTable();
                    });
            }
        }

        if (btn.classList.contains('edit-user-btn')) {
            fetch(`API/usuarios.php?id=${id}`)
                .then(r => r.json())
                .then(user => {
                    document.getElementById('edit_id').value = user.id;
                    document.getElementById('edit_nombre').value = user.nombre;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_rol').value = user.rol;
                    document.getElementById('edit_estatus').value = user.estatus;
                    document.getElementById('modalEditUser').style.display = 'block';
                });
        }
    });

    document.getElementById('formEditUser').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('API/usuarios.php?action=update', {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                alert(data.message || data.error);
                document.getElementById('modalEditUser').style.display = 'none';
                loadUsersTable();
            });
    });

    const initialActiveLink = document.querySelector('.tab-link.active');
    if (initialActiveLink) {
        const initialTabId = initialActiveLink.getAttribute('data-tab');
        showTab(initialTabId);
    } else if (tabLinks.length > 0) {

        tabLinks[0].click();
    }

    // Nuevos listeners para los botones Aprobar/Rechazar dentro del modal de vista previa
    document.getElementById('modalViewPost').addEventListener('click', function (e) {
        const approveBtn = e.target.closest('#approveBtnModal');
        const rejectBtn = e.target.closest('#rejectBtnModal');
        
        if (approveBtn) {
            const id = approveBtn.dataset.id;
            if (confirm('¿Está seguro de aprobar esta publicación?')) {
                handleModeration(id, 'approve');
                document.getElementById('modalViewPost').style.display = 'none'; 
            }
        } else if (rejectBtn) {
            const id = rejectBtn.dataset.id;
            const motivo = prompt('Ingrese el motivo del rechazo (opcional):');
            if (motivo !== null) {
                handleModeration(id, 'reject', motivo);
                document.getElementById('modalViewPost').style.display = 'none';
            }
        }
    });

});