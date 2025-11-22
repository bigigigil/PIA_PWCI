<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: log-In.html'); exit(); }
require_once('./database.php'); 

$db = (new Database())->connect();
$mundiales = []; $categorias = []; $selecciones = [];

if ($db) {
    try {
        // 1. Mundiales
        $stmt = $db->prepare("CALL sp_obtener_mundiales()");
        $stmt->execute();
        $mundiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // ¡Crucial al usar múltiples CALLs seguidos!

        // 2. Categorías
        $stmt = $db->prepare("CALL sp_obtener_categorias_simple()");
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // 3. Selecciones
        $stmt = $db->prepare("CALL sp_obtener_selecciones()");
        $stmt->execute();
        $selecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

    } catch (Exception $e) { $error_db = "Error al cargar listas."; }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infografía - Publicar Contenido</title>
    <link rel="icon" type="image/x-icon" href="images/ball.png">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/publicar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>

    <section class="sub-header-publicar">
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

        <h1>Nueva Publicación</h1>

    </section>

    <section class="publicar-section">
        <div class="publicar-container">
            <h2>Crear Contenido</h2>

            <?php if (isset($error_db)): ?>
                <div class="message-box error"><?php echo $error_db; ?></div>
            <?php endif; ?>

            <form id="publicarForm" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="titulo">Título de la Publicación</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="200">
                </div>

                <div class="form-group">
                    <label for="contenido">Contenido / Descripción</label>
                    <textarea id="contenido" name="contenido" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Archivo Multimedia (Imagen o Video)</label>
                    <div class="file-upload-group">
                        <input type="file" id="archivoMultimedia" name="archivoMultimedia" accept="image/*,video/*" required>
                        
                        <select id="tipoContenido" name="tipoContenido" required>
                            <option value="">Tipo de Archivo</option>
                            <option value="imagen">Imagen</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="idMundial">Mundial Asociado</label>
                    <select id="idMundial" name="idMundial" required>
                        <option value="">Selecciona un Mundial</option>
                        <?php foreach ($mundiales as $mundial): ?>
                            <option value="<?php echo $mundial['id']; ?>">
                                <?php echo $mundial['nombre'] . ' (' . $mundial['año'] . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="idCategoria">Categoría</label>
                    <select id="idCategoria" name="idCategoria" required>
                        <option value="">Selecciona una Categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>">
                                <?php echo $categoria['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="idSeleccion">Selección/País (Opcional)</label>
                    <select id="idSeleccion" name="idSeleccion">
                        <option value="">Selecciona una Selección (Opcional)</option>
                        <?php foreach ($selecciones as $seleccion): ?>
                            <option value="<?php echo $seleccion['id']; ?>">
                                <?php echo $seleccion['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="bi bi-upload"></i> Enviar para Aprobación
                </button>
                
                <div class="message-box" id="responseMessage"></div>

            </form>

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

        document.getElementById('publicarForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const responseMessage = document.getElementById('responseMessage');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';
            responseMessage.style.display = 'none';

            const formData = new FormData(form);

            fetch('API/Publicar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                responseMessage.textContent = body.message || body.error;
                responseMessage.classList.remove('success', 'error');

                if (status === 201) {
                    responseMessage.classList.add('success');
                    form.reset(); 
                } else {
                    responseMessage.classList.add('error');
                }
                responseMessage.style.display = 'block';

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-upload"></i> Enviar para Aprobación';
            })
            .catch(error => {
                responseMessage.textContent = 'Error de conexión con el servidor.';
                responseMessage.classList.remove('success');
                responseMessage.classList.add('error');
                responseMessage.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-upload"></i> Enviar para Aprobación';
                console.error('Error:', error);
            });
        });
    </script>
    <script src="SCRIPTS/auth.js"></script>
    <script src="SCRIPTS/carrusel.js"></script>

</body>

</html>