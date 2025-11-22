-- ----------------------------------------------------------------------
-- VIEWS
-- ----------------------------------------------------------------------

USE infografia_pwci;

CREATE VIEW vista_publicaciones_blog AS
SELECT 
    p.idPublicacion, 
    p.titulo, 
    p.contenido, 
    p.tipoContenido, 
    p.fechaCreacion, 
    p.vistas, 
    p.tipoArchivo, 
    p.archivoMultimedia, 
    u.username, 
    u.nombre AS autorNombre, 
    m.nombre AS mundialNombre, 
    m.año AS mundialAnio, 
    m.sedePrincipal,
    c.nombre AS categoriaNombre,
    (SELECT COUNT(idCalificacion) FROM Calificacion WHERE idPublicacion = p.idPublicacion AND valor = 1) AS total_likes,
    (SELECT COUNT(idComentario) FROM Comentario WHERE idPublicacion = p.idPublicacion) AS total_comentarios
FROM Publicacion p
LEFT JOIN Usuario u ON p.idUsuario = u.id
LEFT JOIN Mundial m ON p.idMundial = m.id
LEFT JOIN Categoria c ON p.idCategoria = c.id
WHERE p.estado = 'aprobada';