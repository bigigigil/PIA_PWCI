<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: log-In.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografía - Perfil de Usuario</title>
    <link rel="icon" type="image/x-icon" href="images/ball.png">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/perfil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>

    <section class="sub-header-perfil">
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

        <h1>Perfil de Usuario</h1>

    </section>

    <section class="perfil-section">
        <div class="perfil-container">

            <div class="perfil-sidebar">
                <img id="perfilPhoto" src="images/futbolistas/jugadores.jpg" alt="Foto de perfil" class="perfil-photo">
                <h2 id="perfilUsername">Cargando...</h2>
                <p id="perfilRole"></p>
                <a href="#" class="edit-btn-perfil">Editar Perfil</a>
            </div>

            <div class="perfil-main-content">
                <h3>Información Personal</h3>
                <div class="perfil-details">
                    <div>
                        <label>Nombre Completo:</label>
                        <span id="perfilFullname"></span>
                    </div>
                    <div>
                        <label>Correo Electrónico:</label>
                        <span id="perfilEmail"></span>
                    </div>
                    <div>
                        <label>Fecha de Nacimiento:</label>
                        <span id="perfilBirthdate"></span>
                    </div>
                    <div>
                        <label>Género:</label>
                        <span id="perfilGender"></span>
                    </div>
                    <div>
                        <label>País/Nacionalidad:</label>
                        <span id="perfilCountry"></span>
                    </div>
                    <div>
                        <label>Miembro desde:</label>
                        <span id="perfilRegistrationDate"></span>
                    </div>
                </div>

            </div>
        </div>
    </section>

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

    <script>
        var navLink = document.getElementById("navLink");
        function showMenu() { navLink.style.right = "0"; }
        function hideMenu() { navLink.style.right = "-200px"; }
    </script>

<div id="modalEditPerfil" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('modalEditPerfil').style.display='none'">&times;</span>
        <h3>Editar Mi Perfil</h3>
        <form id="formEditPerfil" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" id="p_nombre" required>
            </div>
          <div class="form-group">
            <label>Género:</label>
                <select id="p_genero" name="genero">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="O">Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label>País:</label>
                <input type="text" name="pais" id="p_pais" required>
            </div>
            <div class="form-group">
                <label>Nacionalidad:</label>
                <input type="text" name="nacionalidad" id="p_nacionalidad" required>
            </div>
            <div class="form-group">
                <label>Cambiar Foto:</label>
                <input type="file" name="photo" accept="image/*">
            </div>
            <button type="submit" class="hero-btn green-btn">Guardar Cambios</button>
        </form>
    </div>
</div>

    <script src="SCRIPTS/auth.js"></script>
    <script src="SCRIPTS/perfil.js"></script>

</body>

</html>