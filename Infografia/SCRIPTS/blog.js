document.addEventListener('DOMContentLoaded', function () {

    const postsContainer = document.getElementById('blogPostsContainer');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter'); 
    const yearFilter = document.getElementById('yearFilter');
    const categoryListSidebar = document.getElementById('categoryList');
    const ordenSelect = document.getElementById('ordenSelect');

    let userRole = '';
    const sessionData = JSON.parse(sessionStorage.getItem('sessionData') || localStorage.getItem('sessionData') || '{}');
    if (sessionData.loggedIn) {
        userRole = sessionData.rol;
    }


    function loadFiltersAndSidebar() {
        fetch('API/filtros_blog.php')
            .then(response => response.json())
            .then(data => {
        
                categoryFilter.innerHTML = '<option value="">Todas las Categorías</option>';

                categoryListSidebar.innerHTML = '';

                if (data.categorias) {
                    data.categorias.forEach(cat => {
                        
                        const option = document.createElement('option');
                        option.value = cat.nombre;
                        option.textContent = cat.nombre;
                        categoryFilter.appendChild(option);

                        const div = document.createElement('div');
                        
                        div.style.cursor = 'pointer';
                        div.style.display = 'flex';
                        div.style.justifyContent = 'space-between';
                        div.style.padding = '8px 0';
                        div.style.borderBottom = '1px solid #eee';

                        div.onclick = function () {
                            categoryFilter.value = cat.nombre; 
                            loadPosts(); 
                            document.querySelector('.search-filter-area').scrollIntoView({ behavior: 'smooth' });
                        };

                        div.innerHTML = `
                            <span style="color: #0c4da2; font-weight: 500;">${cat.nombre}</span>
                            <span style="background: #eee; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">${cat.total}</span>
                        `;
                        categoryListSidebar.appendChild(div);
                    });
                }

                yearFilter.innerHTML = '<option value="">Todos los Mundiales</option>';
                if (data.mundiales) {
                    data.mundiales.forEach(mundial => {
                        const option = document.createElement('option');
                        option.value = mundial.año; 
                        option.textContent = `${mundial.nombre} (${mundial.año})`;
                        yearFilter.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error cargando filtros:', error));
    }
    function renderComments(comments, userRole) {
        if (!comments || comments.length === 0) {
            return '<p class="no-comments">Sé el primero en comentar.</p>';
        }

        let commentsHtml = '<div class="comments-list">';

        comments.forEach(comment => {
            const date = new Date(comment.fechaComentario).toLocaleString();
            let deleteBtnHtml = '';

            if (userRole === 'Admin') {
                deleteBtnHtml = `
                    <button class="delete-comment-btn" data-comment-id="${comment.idComentario}" title="Eliminar Comentario">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                `;
            }

            commentsHtml += `
                <div class="comment-item" data-comment-id="${comment.idComentario}">
                    <div class="comment-header">
                        <strong>${comment.autorUsername || 'Usuario Desconocido'}</strong>
                        <span class="comment-date">${date}</span>
                        ${deleteBtnHtml}
                    </div>
                    <p class="comment-body">${comment.comentario}</p>
                </div>
            `;
        });

        commentsHtml += '</div>';
        return commentsHtml;
    }

    function handleDeleteComment(e) {
        const button = e.currentTarget;
        const idComentario = button.dataset.commentId;

        if (!confirm(`¿Estás seguro de eliminar este comentario (ID: ${idComentario})? Esta acción es irreversible.`)) {
            return;
        }

        fetch(`API/Interaccion.php?action=comment&idComentario=${idComentario}`, {
            method: 'DELETE',
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error al eliminar: ' + data.error);
                } else {
                    alert(data.message);
                    loadPosts();
                }
            })
            .catch(err => alert('Error de conexión al eliminar el comentario.'));
    }

    function loadPosts() {
        postsContainer.innerHTML = '<h1 style="text-align: center;">Cargando Publicaciones...</h1>';
        const search = searchInput.value.trim();
        const categoria = categoryFilter.value;
        const mundial = yearFilter.value;
        const orden = ordenSelect.value;

        const url = `API/Publicaciones.php?search=${search}&categoria=${categoria}&mundial=${mundial}&orden=${orden}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Error HTTP ${response.status}. La API no devolvió JSON. Respuesta: ${text.substring(0, 50)}...`);
                    });
                }
                return response.json();
            })
            .then(posts => {
                postsContainer.innerHTML = '';

                if (posts.length === 0) {
                    postsContainer.innerHTML = `
                        <div style="text-align: center; padding: 50px; background: #fff; border-radius: 10px;">
                            <h2><i class="bi bi-info-circle-fill" style="color: #0c4da2;"></i> ¡Vaya!</h2>
                            <p style="font-size: 1.1em; color: #555;">
                                No se encontraron publicaciones que coincidan con los filtros aplicados.
                            </p>
                            <a href="publicar.php" class="hero-btn green-btn" style="margin-top: 20px;">
                                Publicar Ahora
                            </a>
                        </div>
                    `;
                    return;
                }

                posts.forEach(post => {

                    const likesDisplay = post.total_likes > 0
                        ? `${post.total_likes} Likes`
                        : `<span style="color: #777;">Aún no se ha calificado</span>`;

                    const commentsDisplay = post.total_comentarios > 0
                        ? `${post.total_comentarios} Comentarios`
                        : `<span style="color: #777;">No hay comentarios</span>`;

                    let mediaHtml = '';

                    if (post.archivoMultimedia) {
                        const src = `data:${post.tipoMime || 'application/octet-stream'};base64,${post.archivoMultimedia}`;

                        if (post.tipoContenido === 'imagen') {
                            mediaHtml = `<img src="${src}" alt="${post.titulo}" onerror="this.onerror=null; this.src='https://placehold.co/600x350/EAEAEA/888888?text=Error+Multimedia'">`;
                        } else if (post.tipoContenido === 'video') {
                            mediaHtml = `
                                <video controls style="width: 100%; height: auto; max-height: 350px; border-radius: 8px;">
                                    <source src="${src}" type="${post.tipoMime || 'video/mp4'}">
                                    Tu navegador no soporta la etiqueta de video.
                                </video>
                             `;
                        }
                    } else {
                        mediaHtml = `<div style="height: 300px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #555;">No hay archivo multimedia.</div>`;
                    }

                    const commentsHtml = renderComments(post.comentarios, userRole);

                    const postHtml = `
                        <article class="blog-post" data-id="${post.idPublicacion}">
                            ${mediaHtml}
                            <h1>${post.titulo}</h1>
                            <p class="post-meta">
                                Mundial: <strong>${post.mundialNombre || 'N/A'} (${post.mundialAnio || 'N/A'})</strong> | Publicado por <strong>${post.username || post.autorNombre}</strong> el ${new Date(post.fechaCreacion).toLocaleDateString()}
                            </p>
                            <p>${post.contenido.substring(0, 150)}${post.contenido.length > 150 ? '...' : ''}</p>
                            
                            <div class="post-stats">
                                <span style="margin-right: 15px;">
                                    <i class="bi bi-hand-thumbs-up-fill" style="color: #00810b; cursor: pointer;" data-action="like"></i> ${likesDisplay}
                                </span>
                                <span style="margin-right: 15px;">
                                    <i class="bi bi-chat-dots-fill" style="color: #0c4da2;"></i> ${commentsDisplay}
                                </span>
                                <span>
                                    <i class="bi bi-eye-fill"></i> ${post.vistas} Vistas
                                </span>
                            </div>
                            <div class="comment-box">
                                <form class="comment-form">
                                    <textarea rows="3" placeholder="Tu comentario" required></textarea>
                                    <button type="submit" class="hero-btn green-btn">Comentar</button>
                                </form>
                                <div class="comments-display-area">
                                    <h3>Comentarios (${post.comentarios.length})</h3>
                                    ${commentsHtml}
                                </div>
                            </div>
                        </article>
                    `;
                    postsContainer.innerHTML += postHtml;
                });

                document.querySelectorAll('.delete-comment-btn').forEach(button => {
                    button.addEventListener('click', handleDeleteComment);
                });
            })
            .catch(error => {
                console.error('Error al cargar el blog:', error);
                postsContainer.innerHTML = `<h2>Error al cargar el blog.</h2><p style="color: red;">Revise el archivo 'API/Publicaciones.php' en su servidor. Error: ${error.message}</p>`;
            });
    }

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') loadPosts();
    });
    categoryFilter.addEventListener('change', loadPosts);
    yearFilter.addEventListener('change', loadPosts);
    ordenSelect.addEventListener('change', loadPosts);

    loadFiltersAndSidebar();
    loadPosts();

    postsContainer.addEventListener('submit', function (e) {
        if (e.target.classList.contains('comment-form')) {
            e.preventDefault();
            const form = e.target;
            const idPublicacion = form.closest('.blog-post').dataset.id;
            const comentario = form.querySelector('textarea').value;

            fetch('API/Interaccion.php?action=comment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idPublicacion, comentario })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        alert('Comentario enviado y pendiente de aprobación.');
                        form.reset();
                        loadPosts();
                    }
                })
                .catch(err => alert('Error de conexión al comentar.'));
        }
    });

    postsContainer.addEventListener('click', function (e) {
        if (e.target.dataset.action === 'like') {
            const idPublicacion = e.target.closest('.blog-post').dataset.id;

            fetch('API/Interaccion.php?action=rate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idPublicacion, valor: 1 })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        loadPosts();
                    }
                })
                .catch(err => alert('Error al calificar.'));
        }
    });
});