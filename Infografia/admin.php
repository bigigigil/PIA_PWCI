<?php
    session_start();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
        header('Location: index.html');
        exit();
    }
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografia - Football</title>
    <link rel="icon" type="image/x-icon" href="images/ball.png">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>


    <section class="sub-header">
        <nav>
            <a href="index.html"><img src="images/logo.png" alt="Logo"></a>
            <div class="nav-link" id="navLink">
                <i class="bi bi-x-circle" onclick="hideMenu()"></i>
                <ul>
                  <li><a href="index.html">INICIO</a></li>
                    <li><a href="about.html">SOBRE NOSOTROS</a></li>
                    <li><a href="categorie.html">CATEGORIA</a></li>
                    <li><a href="blog.php">BLOG</a></li>
                    <li><a href="admin.php">ADMINISTRADOR</a></li>
                    <li>
                    <li>
                        <a href="log-In.html">
                            <i class="bi bi-person-circle"></i> INICIAR SESION
                        </a>
                    </li>

                </ul>
            </div>
            <i class="bi bi-list" onclick="showMenu()"></i>
        </nav>

        <h1>Panel de Administración</h1>
        <p>Gestiona usuarios, publicaciones y configuración del sistema</p>

    </section>



    <main class="content-area">
        <div class="tab-container">
           <ul class="tabs">
    <li><a href="#" class="tab-link active" data-tab="usuari">Usuarios</a></li>
    <li><a href="#" class="tab-link" data-tab="pending-posts">Moderación</a></li>
    <li><a href="#" class="tab-link" data-tab="data-management">Gestión de Etiquetas</a></li> 
    <!--
        <li><a href="#" class="tab-link" data-tab="config">Configuración</a></li>-->
        
    </ul>

<div id="data-management" class="admin-section tab-content" style="display: none;">
    <div class="section-header">
        <h3 class="section-title">Creación de Etiquetas para Publicaciones</h3>
    </div>

    <div class="settings-section">
        <h4><i class="fas fa-tag"></i> Nueva Categoría</h4>
        <form id="formCategoria" class="settings-form">
            <div class="form-group">
                <label>Nombre de la Categoría</label>
                <input type="text" name="nombre" required placeholder="Ej. Jugadas, Entrevistas">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" placeholder="Breve descripción">
            </div>
            <button type="submit" class="btn btn-primary" style="height: fit-content; align-self: flex-end;">Crear</button>
        </form>
    </div>

    <div class="settings-section">
        <h4><i class="fas fa-globe-americas"></i> Nuevo Mundial</h4>
        <form id="formMundial" class="settings-form">
            <div class="form-group">
                <label>Nombre del Mundial</label>
                <input type="text" name="nombre" required placeholder="Ej. Copa Mundial 2026">
            </div>
            <div class="form-group">
                <label>Año</label>
                <input type="number" name="anio" required placeholder="2026" min="1930" max="2100">
            </div>
            <div class="form-group">
                <label>Sede Principal</label>
                <input type="text" name="sede" required placeholder="Ej. México/EEUU/Canadá">
            </div>
            <button type="submit" class="btn btn-primary" style="height: fit-content; align-self: flex-end;">Crear</button>
        </form>
    </div>

    <div class="settings-section">
        <h4><i class="fas fa-flag"></i> Nueva Selección</h4>
        <form id="formSeleccion" class="settings-form">
            <div class="form-group">
                <label>Nombre de la Selección</label>
                <input type="text" name="nombre" required placeholder="Ej. El tri">
            </div>
            <div class="form-group">
                <label>País</label>
                <input type="text" name="pais" required placeholder="Ej. México">
            </div>
            <button type="submit" class="btn btn-primary" style="height: fit-content; align-self: flex-end;">Crear</button>
        </form>
    </div>
</div>
        </div>

        <div id="usuari" class="admin-section tab-content">
            <div class="section-header">
                <h3 class="section-title">Gestión de Usuarios</h3>
<!--
 <div class="section-actions">
                    <button class="action-button">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </button>
                    <button class="action-button">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>                
-->
               
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Registro</th>
                        <th>Estado</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                
                    <tr><td colspan="6">Cargando usuarios...</td></tr>
                </tbody>
            </table>
        </div>
   
        <div id="pending-posts" class="admin-section tab-content" style="display: none;">
            <div class="section-header">
                <h3 class="section-title">Publicaciones Pendientes de Aprobación</h3>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Mundial</th>
                        <th>Categoría</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pendingPostsTableBody">
                    <tr><td colspan="7">Cargando publicaciones pendientes...</td></tr>
                </tbody>
            </table>
        </div>
  
        <div id="config" class="settings-container tab-content">
            <div class="settings-header">
                <h2 class="settings-title">Configuración General</h2>
                <div class="settings-actions">
                    <button class="btn btn-outline">
                        <i class="fas fa-undo"></i> Restablecer
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
            
            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        Perfil Público
                    </h3>
                </div>

                <div class="settings-form">
                    <div class="form-group">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" id="username" value="bigigigil">
                        <p class="form-hint">Este es el nombre que otros usuarios verán</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Biografía</label>
                    <textarea id="bio"
                        placeholder="Cuéntanos sobre ti...">fútbol fútbol fútbol! siempre fútbol!</textarea>
                    <p class="form-hint">Máximo 100 caracteres</p>
                </div>
            </div>

            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        Idioma y Región
                    </h3>
                </div>

                <div class="settings-form">
                    <div class="form-group">
                        <label for="language">Idioma</label>
                        <select id="language">
                            <option selected>Español</option>
                            <option>English</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="settings-section danger-zone">
                <div class="section-header">
                    <h3 class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        Zona Peligrosa
                    </h3>
                </div>

                <div class="form-group">
                    <label>Eliminar Cuenta</label>
                    <p class="form-hint">Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, ten
                        certeza.</p>
                    <button class="danger-button">
                        <i class="fas fa-trash"></i> Eliminar Mi Cuenta
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-outline">Cancelar</button>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>

    </main>
<div id="modalViewPost" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <span class="close-btn" onclick="document.getElementById('modalViewPost').style.display='none'">&times;</span>
        <h3 class="modal-title">Vista Previa de Publicación Pendiente</h3>
        
        <div id="postPreviewContent">
            <h2 id="previewTitle" style="color: var(--fifa-blue); font-size: 2rem;"></h2>
            <p style="margin-bottom: 10px;">
                <strong>Autor:</strong> <span id="previewAutor"></span> | 
                <strong>Mundial:</strong> <span id="previewMundial"></span> | 
                <strong>Categoría:</strong> <span id="previewCategoria"></span>
            </p>
            <hr>
            
            <div id="previewMultimedia" style="margin: 20px 0; max-height: 400px; overflow: hidden; text-align: center;">
                </div>
            
            <p style="font-weight: 600; margin-top: 20px;">Contenido/Descripción:</p>
            <p id="previewContent" style="white-space: pre-wrap; text-align: justify; padding: 10px; background: #f9f9f9; border-radius: 8px;"></p>
            
            <div class="form-actions" style="border-top: 1px solid #eee; padding-top: 15px;">
                <button id="approveBtnModal" class="btn btn-primary" data-id=""><i class="fas fa-check"></i> Aprobar</button>
                <button id="rejectBtnModal" class="danger-button" data-id=""><i class="fas fa-times"></i> Rechazar</button>
            </div>
        </div>
    </div>
</div>
    <section class="footer">
        <h4>Sobre el equipo</h4>
        <p>
            Equipo de Programacion de Capa Intermedia, grupo 052
        </p>
        <div class="icon">
            <i class="bi bi-facebook"></i>
            <i class="bi bi-instagram"></i>
            <i class="bi bi-twitter"></i>
            <i class="bi bi-linkedin"></i>
        </div>
               <p>Hecho por Sofy & Rox <i class="bi bi-stars"></i> </p>
    </section>


<div id="modalEditUser" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="close-btn" onclick="document.getElementById('modalEditUser').style.display='none'">&times;</span>
        <h3 class="modal-title">Editar Usuario</h3>
        <form id="formEditUser">
            <input type="hidden" id="edit_id" name="id">
            
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" id="edit_nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
                <label>Rol:</label>
                <select id="edit_rol" name="rol">
                    <option value="Usuario">Usuario</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estatus:</label>
                <select id="edit_estatus" name="estatus">
                    <option value="Activo">Activo</option>
                    <option value="Eliminado">Eliminado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nueva Contraseña (Opcional):</label>
                <input type="password" name="password" placeholder="Dejar en blanco para no cambiar">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Guardar Cambios</button>
        </form>
    </div>
</div>

    <script>
        var navLink = document.getElementById("navLink");

        function showMenu() {
            navLink.style.right = "0";
        }

        function hideMenu() {
            navLink.style.right = "-200px";
        }
    </script>
<script src="SCRIPTS/auth.js"></script>
<script src="SCRIPTS/admin.js"></script>


</body>



</html>