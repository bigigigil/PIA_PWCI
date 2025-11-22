-- ----------------------------------------------------------------------
-- BASE DE DATOS
-- ----------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS infografia_pwci;
USE infografia_pwci;


-- ----------------------------------------------------------------------
-- TABLAS
-- ----------------------------------------------------------------------

CREATE TABLE Usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    fechaNacimiento DATE NOT NULL,
    foto LONGBLOB,
    genero ENUM('M','F','O') NOT NULL,
    paisNacimiento VARCHAR(50) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    rol ENUM('Admin','Usuario') DEFAULT 'Usuario',
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estatus ENUM('Activo','Eliminado') DEFAULT 'Activo',
    tipoImagen VARCHAR(10)
);

CREATE TABLE Categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    idUsuarioCreador INT NOT NULL,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuarioCreador) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------
-- 3. Tabla Mundial (Depende de Usuario)
-- ----------------------------------------------------------------------
CREATE TABLE Mundial (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    año YEAR NOT NULL,
    sedePrincipal VARCHAR(100) NOT NULL,
    logo LONGBLOB,
    tipoLogo VARCHAR(10),
    imagenPrincipal LONGBLOB,
    tipoImagenPrincipal VARCHAR(10),
    resenia TEXT,
    idUsuarioCreador INT NOT NULL,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuarioCreador) REFERENCES Usuario(id)
);

CREATE TABLE Seleccion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    bandera LONGBLOB,
    tipoBandera VARCHAR(10)
);

CREATE TABLE Publicacion (
    idPublicacion INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    tipoContenido ENUM('imagen','video') NOT NULL,
    archivoMultimedia LONGBLOB NOT NULL,
    tipoArchivo VARCHAR(10),
    idUsuario INT NOT NULL,
    idMundial INT NOT NULL,
    idCategoria INT NOT NULL,
    idSeleccion INT,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fechaAprobacion DATETIME,
    idUsuarioAprobador INT,
    estado ENUM('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
    motivoRechazo TEXT,
    vistas INT DEFAULT 0, -- Columna añadida desde el inicio
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id),
    FOREIGN KEY (idMundial) REFERENCES Mundial(id),
    FOREIGN KEY (idCategoria) REFERENCES Categoria(id),
    FOREIGN KEY (idSeleccion) REFERENCES Seleccion(id),
    FOREIGN KEY (idUsuarioAprobador) REFERENCES Usuario(id)
);

CREATE TABLE Comentario (
    idComentario INT PRIMARY KEY AUTO_INCREMENT,
    idPublicacion INT NOT NULL,
    idUsuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fechaComentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPublicacion) REFERENCES Publicacion(idPublicacion) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);

CREATE TABLE Calificacion (
    idCalificacion INT PRIMARY KEY AUTO_INCREMENT,
    idPublicacion INT NOT NULL,
    idUsuario INT NOT NULL,
    valor TINYINT(1) NOT NULL CHECK (valor IN (1, -1)), -- 1 para "Like", -1 para "Dislike"
    fechaCalificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_calificacion (idPublicacion, idUsuario),
    FOREIGN KEY (idPublicacion) REFERENCES Publicacion(idPublicacion) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);