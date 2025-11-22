<?php
 session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: log-In.html');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografía - Blog de Publicaciones</title>
    <link rel="icon" type="image/x-icon" href="images/ball.png">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/blog.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

    <section class="subBlog-header">
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
        <h1>Blog de Publicaciones</h1><br>
    </section>

    <section class="search-filter-area">
        <h2>Encuentra publicaciones por Mundial o Categoría</h2>
        <div class="filter-controls">
            <input type="text" id="searchInput" placeholder="Buscar: Título, Autor, Sede..." >
            
            <select id="categoryFilter">
                <option value="">Todas las Categorías</option>
                </select>
            
            <select id="yearFilter">
                <option value="">Todos los Mundiales</option>
                </select>
        </div>
        <div class="order-controls">
            <label for="ordenSelect">Ordenar por:</label>
            <select id="ordenSelect">
                <option value="cronologico_mundial">Cronológico Mundial (Defecto)</option>
                <option value="fecha">Fecha de Publicación</option>
                <option value="likes">Más Likes</option>
                <option value="comentarios">Más Comentarios</option>
            </select>
        </div>
    </section>


    <section class="blog-content">
        <div class="row">
            
            <div class="blog-left" id="blogPostsContainer">
                
                <h2 >Cargando publicaciones...</h2>
                
            </div>
            
            <div class="blog-right">

                <div class="card-sidebar">
                    <h3>Crea una Publicación</h3>
                    <p >
                        ¡Comparte tu pasión por el fútbol!
                    </p> <br>
                    <a href="publicar.php" class="hero-btn green-btn" >
                        <i class="bi bi-plus-circle"></i> Publicar Ahora
                    </a>
                </div>

                <div class="card-sidebar">
                    <h3>Categorías</h3>
                    <div id="categoryList">
<!--
<div><span>Noticias</span><span>10</span></div>
                        <div><span>Historia del futbol</span><span>2</span></div>                    
-->
                        
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
    <script src="SCRIPTS/auth.js"></script>
    <script src="SCRIPTS/carrusel.js"></script>
    <script src="SCRIPTS/blog.js"></script>
</body>

</html>