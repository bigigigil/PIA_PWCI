-- ----------------------------------------------------------------------
-- STORED PROCEDURES (SP)
-- ----------------------------------------------------------------------

USE infografia_pwci;

DELIMITER //

DROP PROCEDURE IF EXISTS sp_obtener_usuario_login;
DROP PROCEDURE IF EXISTS sp_obtener_publicaciones_blog;
DROP PROCEDURE IF EXISTS sp_obtener_publicaciones_filtro;
DROP PROCEDURE IF EXISTS sp_obtener_comentarios;
DROP PROCEDURE IF EXISTS sp_obtener_publicaciones_pendientes;
DROP PROCEDURE IF EXISTS sp_moderar_publicacion;
DROP PROCEDURE IF EXISTS sp_obtener_perfil_usuario;
DROP PROCEDURE IF EXISTS sp_obtener_mundiales;
DROP PROCEDURE IF EXISTS sp_obtener_categorias_simple;
DROP PROCEDURE IF EXISTS sp_obtener_selecciones;
DROP PROCEDURE IF EXISTS sp_crear_publicacion;
DROP PROCEDURE IF EXISTS sp_obtener_usuarios_activos;
DROP PROCEDURE IF EXISTS sp_verificar_usuario_existe;
DROP PROCEDURE IF EXISTS sp_registrar_usuario;
DROP PROCEDURE IF EXISTS sp_crear_comentario;
DROP PROCEDURE IF EXISTS sp_calificar_publicacion;
DROP PROCEDURE IF EXISTS sp_eliminar_comentario;
DROP PROCEDURE IF EXISTS sp_eliminar_usuario;
DROP PROCEDURE IF EXISTS sp_editar_usuario_admin;
DROP PROCEDURE IF EXISTS sp_obtener_usuario_por_id;
DROP PROCEDURE IF EXISTS sp_actualizar_mi_perfil; 
DROP PROCEDURE IF EXISTS sp_obtener_publicacion_pendiente_por_id;

CREATE PROCEDURE sp_obtener_usuario_login(IN p_username VARCHAR(50), IN p_email VARCHAR(100))
BEGIN
    SELECT id, nombre, username, email, password, rol, estatus
    FROM Usuario
    WHERE (username = p_username OR email = p_email) AND estatus = 'Activo';
END //

CREATE PROCEDURE sp_obtener_publicaciones_blog()
BEGIN
    SELECT 
        idPublicacion, 
        titulo, 
        contenido,
        tipoContenido,
        vistas,
        username AS autor,
        mundialNombre AS mundial,
        categoriaNombre AS categoria
    FROM vista_publicaciones_blog
    ORDER BY fechaCreacion DESC;
END //

CREATE PROCEDURE sp_obtener_publicaciones_filtro(
    IN p_search VARCHAR(100),
    IN p_categoria VARCHAR(50),
    IN p_mundial VARCHAR(10),
    IN p_orden VARCHAR(30)
)
BEGIN
    SELECT 
        idPublicacion, titulo, contenido, tipoContenido, fechaCreacion, vistas, 
        tipoArchivo, archivoMultimedia, 
        username, autorNombre, 
        mundialNombre, mundialAnio, sedePrincipal,
        total_likes, total_comentarios
    FROM vista_publicaciones_blog
    WHERE 
        (p_search IS NULL OR p_search = '' OR titulo LIKE CONCAT('%', p_search, '%') OR username LIKE CONCAT('%', p_search, '%') OR sedePrincipal LIKE CONCAT('%', p_search, '%'))
        AND (p_categoria IS NULL OR p_categoria = '' OR categoriaNombre = p_categoria)
        AND (p_mundial IS NULL OR p_mundial = '' OR mundialAnio = p_mundial)
    ORDER BY 
        CASE 
            WHEN p_orden = 'likes' THEN total_likes
            WHEN p_orden = 'comentarios' THEN total_comentarios
            WHEN p_orden = 'vistas' THEN vistas
            ELSE NULL 
        END DESC,
        CASE 
            WHEN p_orden = 'cronologico_mundial' THEN mundialAnio 
            ELSE NULL 
        END ASC,
        fechaCreacion DESC;
END //

CREATE PROCEDURE sp_obtener_comentarios(IN p_idPublicacion INT)
BEGIN
    SELECT 
        c.idComentario, 
        c.comentario, 
        c.fechaComentario, 
        u.username AS autorUsername,
        c.idUsuario
    FROM Comentario c
    JOIN Usuario u ON c.idUsuario = u.id
    WHERE c.idPublicacion = p_idPublicacion
    ORDER BY c.fechaComentario ASC;
END //

CREATE PROCEDURE sp_obtener_publicaciones_pendientes()
BEGIN
    SELECT 
        p.idPublicacion, p.titulo, p.fechaCreacion, p.tipoContenido,
        u.username AS autor, m.nombre AS mundial, c.nombre AS categoria,
        p.idMundial, p.idCategoria, p.idUsuario 
    FROM Publicacion p
    LEFT JOIN Usuario u ON p.idUsuario = u.id
    LEFT JOIN Mundial m ON p.idMundial = m.id
    LEFT JOIN Categoria c ON p.idCategoria = c.id
    WHERE p.estado = 'pendiente'
    ORDER BY p.fechaCreacion ASC;
END //

CREATE PROCEDURE sp_moderar_publicacion(
    IN p_idPublicacion INT, 
    IN p_nuevoEstado VARCHAR(20), 
    IN p_idAprobador INT, 
    IN p_motivo TEXT
)
BEGIN
    UPDATE Publicacion SET 
        estado = p_nuevoEstado, 
        fechaAprobacion = NOW(), 
        idUsuarioAprobador = p_idAprobador,
        motivoRechazo = p_motivo
    WHERE idPublicacion = p_idPublicacion;
END //

CREATE PROCEDURE sp_obtener_perfil_usuario(IN p_idUsuario INT)
BEGIN
    SELECT nombre, username, email, fechaNacimiento, genero, paisNacimiento, nacionalidad, fechaRegistro, rol, foto, tipoImagen 
    FROM Usuario 
    WHERE id = p_idUsuario;
END //

CREATE PROCEDURE sp_obtener_mundiales()
BEGIN
    SELECT id, nombre, año FROM Mundial ORDER BY año DESC;
END //

CREATE PROCEDURE sp_obtener_categorias_simple()
BEGIN
    SELECT id, nombre FROM Categoria ORDER BY nombre ASC;
END //

CREATE PROCEDURE sp_obtener_selecciones()
BEGIN
    SELECT id, nombre FROM Seleccion ORDER BY nombre ASC;
END //

CREATE PROCEDURE sp_crear_publicacion(
    IN p_titulo VARCHAR(200),
    IN p_contenido TEXT,
    IN p_tipoContenido VARCHAR(20),
    IN p_archivo LONGBLOB,
    IN p_tipoArchivo VARCHAR(10),
    IN p_idUsuario INT,
    IN p_idMundial INT,
    IN p_idCategoria INT,
    IN p_idSeleccion INT
)
BEGIN
    INSERT INTO Publicacion 
    (titulo, contenido, tipoContenido, archivoMultimedia, tipoArchivo, idUsuario, idMundial, idCategoria, idSeleccion, estado) 
    VALUES 
    (p_titulo, p_contenido, p_tipoContenido, p_archivo, p_tipoArchivo, p_idUsuario, p_idMundial, p_idCategoria, p_idSeleccion, 'pendiente');
END //

CREATE PROCEDURE sp_obtener_usuarios_activos()
BEGIN
    SELECT id, nombre, email, fechaRegistro, estatus, rol 
    FROM Usuario 
    WHERE estatus = 'Activo' 
    ORDER BY fechaRegistro DESC;
END //

CREATE PROCEDURE sp_verificar_usuario_existe(IN p_username VARCHAR(50), IN p_email VARCHAR(100))
BEGIN
    SELECT id FROM Usuario WHERE email = p_email OR username = p_username;
END //

CREATE PROCEDURE sp_registrar_usuario(
    IN p_nombre VARCHAR(100),
    IN p_username VARCHAR(50),
    IN p_fechaNacimiento DATE,
    IN p_genero CHAR(1),
    IN p_pais VARCHAR(50),
    IN p_nacionalidad VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_foto LONGBLOB,
    IN p_tipoImagen VARCHAR(10)
)
BEGIN
    INSERT INTO Usuario 
    (nombre, username, fechaNacimiento, genero, paisNacimiento, nacionalidad, email, password, rol, foto, tipoImagen) 
    VALUES 
    (p_nombre, p_username, p_fechaNacimiento, p_genero, p_pais, p_nacionalidad, p_email, p_password, 'Usuario', p_foto, p_tipoImagen);
END //

CREATE PROCEDURE sp_crear_comentario(IN p_idPub INT, IN p_idUser INT, IN p_comentario TEXT)
BEGIN
    INSERT INTO Comentario (idPublicacion, idUsuario, comentario) VALUES (p_idPub, p_idUser, p_comentario);
END //

CREATE PROCEDURE sp_calificar_publicacion(IN p_idPub INT, IN p_idUser INT, IN p_valor INT)
BEGIN
    INSERT INTO Calificacion (idPublicacion, idUsuario, valor) 
    VALUES (p_idPub, p_idUser, p_valor) 
    ON DUPLICATE KEY UPDATE valor = VALUES(valor);
END //

CREATE PROCEDURE sp_eliminar_comentario(IN p_idComentario INT)
BEGIN
    DELETE FROM Comentario WHERE idComentario = p_idComentario;
END //

CREATE PROCEDURE sp_eliminar_usuario(IN p_id INT)
BEGIN
    UPDATE Usuario SET estatus = 'Eliminado' WHERE id = p_id;
END //

CREATE PROCEDURE sp_editar_usuario_admin(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_rol ENUM('Admin','Usuario'),
    IN p_estatus ENUM('Activo','Eliminado'),
    IN p_password VARCHAR(255)
)
BEGIN
    UPDATE Usuario SET 
        nombre = p_nombre, 
        email = p_email, 
        rol = p_rol, 
        estatus = p_estatus,
        password = IF(p_password IS NOT NULL AND p_password != '', p_password, password)
    WHERE id = p_id;
END //


CREATE PROCEDURE sp_obtener_usuario_por_id(IN p_id INT)
BEGIN
    SELECT id, nombre, username, email, rol, estatus FROM Usuario WHERE id = p_id;
END //

CREATE PROCEDURE sp_actualizar_mi_perfil(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_genero ENUM('M','F','O'),
    IN p_pais VARCHAR(50),
    IN p_nacionalidad VARCHAR(50),
    IN p_foto LONGBLOB,
    IN p_tipoImagen VARCHAR(10)
)
BEGIN
    UPDATE Usuario SET 
        nombre = p_nombre,
        genero = p_genero,
        paisNacimiento = p_pais,
        nacionalidad = p_nacionalidad,
        foto = IF(p_foto IS NOT NULL, p_foto, foto),
        tipoImagen = IF(p_tipoImagen IS NOT NULL, p_tipoImagen, tipoImagen)
    WHERE id = p_id;
END //

CREATE PROCEDURE sp_obtener_publicacion_pendiente_por_id(
    IN p_idPublicacion INT
)
BEGIN
    SELECT 
        p.titulo, 
        p.contenido, 
        p.tipoContenido, 
        p.archivoMultimedia, 
        p.tipoArchivo, 
        m.nombre as mundial, 
        c.nombre as categoria, 
        s.nombre as seleccion, 
        u.username as autor, 
        p.fechaCreacion
    FROM Publicacion p
    JOIN Usuario u ON p.idUsuario = u.id
    JOIN Mundial m ON p.idMundial = m.id
    JOIN Categoria c ON p.idCategoria = c.id
    LEFT JOIN Seleccion s ON p.idSeleccion = s.id
    WHERE p.idPublicacion = p_idPublicacion AND p.estado = 'pendiente';
END //

DELIMITER ;