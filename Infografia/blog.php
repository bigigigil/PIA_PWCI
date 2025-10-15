<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: log-In.html');
        exit();
    }
?>
<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografía - Football</title>
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
                    <li><a href="contact.html">CONTACTO</a></li>
                    <li><a href="admin.php">ADMINISTRADOR</a></li>
                    <li>
                        <a href="log-In.html">
                            <i class="bi bi-person-circle"></i> INICIAR SESION
                        </a>
                    </li>

                </ul>
            </div>
            <i class="bi bi-list" onclick="showMenu()"></i>
        </nav>

        <h1>Blog</h1>
    </section>

    <!-- Parte 1 -->

    <section class="blog-content">
        <div class="row">
            <div class="blog-left">

                <img src="images/blog/blogPrueba.jpg">
                <h1>Dejanos saber que piensas!</h1>
                <p>Tu opinión es muy importante para nosotros.
                    ¿Qué te pareció esta infografía? ¿Hay algún dato que te sorprendió o un momento que te gustaría que
                    hubiéramos incluido?
                    Estamos siempre buscando la manera de mejorar y seguir celebrando la magia del fútbol.
                    Escríbenos tus comentarios, sugerencias o cualquier idea que tengas.
                    ¡Nos encantaría saber de ti y construir juntos una comunidad de verdaderos aficionados!
                </p>
                <br>

                <h2>Deja tu comentario!</h2>

                <div class="comment-box">
                    <form class="comment-form">
                        <input type="text" placeholder="Ingresa tu nombre:">
                        <input type="text" placeholder="Ingresa tu email:">
                        <textarea rows="10" placeholder="Tu comentario"></textarea>
                        <button type="submit" class="hero-btn green-btn">Post comment</button>
                    </form>
                </div>

            </div>
            <div class="blog-right">

                <h3>Post categories</h3>

                <div>
                    <span>Noticias</span>
                    <span>10</span>
                </div>

                <div>
                    <span>Historia del futbol</span>
                    <span>2</span>
                </div>

                <div>
                    <span>Jugadores y leyendas</span>
                    <span>17</span>
                </div>

                <div>
                    <span>Eurocopa</span>
                    <span>48</span>
                </div>

                <div>
                    <span>Mundial de Fútbol</span>
                    <span>74</span>
                </div>

                <div>
                    <span>Champions League</span>
                    <span>213</span>
                </div>


            </div>
        </div>
    </section>

    <!-- Footer -->
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
        <p>Hecho con <i class="bi bi-stars"></i> por Nosotros</p>
    </section>

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
    <script src="SCRIPTS/carrusel.js"></script>


</body>

</html>