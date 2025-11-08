# ᯓ⚽︎ Infografía de Fútbol ⋅˚₊‧ ୨୧ ⌗ .ᐟ
```
                                                        —⁺˖°ʚ EQUIPO ɞ°⁺˖—
                                              ⚽︎ Ana Sofía Barajas Mier y Terán
                                              ⚽︎ Roxanna Abigail Mendoza González          
                                                           ₊✩‧₊˚౨ৎ˚₊✩‧₊
                                                                                                                            
                     ██████████████  ██                                                                                     
                   ███            ████      ███████████                                                                     
                 ███                █████████        █████                                                                  
               ███                                      ████                                                                
              ███                                         █████████                                                         
             ███                                            ██ ██ ███                                                       
            ██                          ██                   █ ████ ██                                                      
            ██                          ██                   █ ████ ██                     ███████████                      
           ██                                                █ ███ ██                  ██████  █████████                    
           ██                                                ██████                 ██████     ██████████                   
           ██                                                ███                    ███████     ██     ████                 
           ██                                               ██                   ██ ████  ████████      ████                
          ██ ███ ██    █                                   ███                   █ ████    ████████    ██████               
          ██ ███ ██████ ███                              ███                     █ ████    ██████████████████               
          ██ █████████████ ██          ███            ████                       █ ████    ████████    ██████               
          ██ █████████████████           ███       █████                         █ ████████  █████      ████                
          ███ █████████████████             ████████            █████████        ██ ██████       ██    ████                 
           ██  █████████████ ██              █████             █████████████   ██ ██ █████      ███████████                 
           ███ ████████████████             ███               ███████████████   ██ ██ ███████ ███████████                   
           ███ █████████████████████████     █████            ██████████████     ██  █  ███████████████                     
            ███ ███████████████       ███████ █████████        ██████████████               ███████                         
              ████ ███ ██ ████     ████████████  ███ █████     ██████████████████                                           
                ████████████    ██████████████    ██   ██████  ███████████████████                                          
                    ████      ██████           █████       █████  █████████████████                                         
                             ██████               ██          ██████████████████████                                        
                             █████            ███ ██          ██████ ██████████████                                         
                              ███         ███ █████           ██████   ████████████                                         
                                ███    ██████████            ███████  █████████████                                         
                                 ████████ ██ █████          ██████████████   █████                                          
                                         ███  █████       ██████████                                                        
                                  ███    ██  ███████     ███████████                                                        
                                    ███████   ███████ █████████                                                             
                                     ██ ███   ████████████████                                                              
                                      ████████████████████████                                                              
                                             █████████████████                                                              
                                                ███ █████  █████                                                            
                                          █████████████████████████                                                         
                                        █████████████████████████████                                                       
                                        ██████████████████████████████                                                      
                                        ██████████████████████████████                                                      
                                          ███████████████████████████                                                       
                                          █████████████████████████                                                         
                                                ████ ████████ ████                                                          
                                                        ████                                                                
                                                                                                                            
```                                                                                                                                     

## Instalación ₊‧.°.⋆˚₊‧⋆.

Sigue estos pasos para configurar y ejecutar el proyecto en tu entorno local.

### 1\. Prerrequisitos

Asegúrate de tener instalado un entorno de desarrollo local que incluya Apache y MySQL.

  * **Servidor:** [**XAMPP**](https://www.apachefriends.org/index.html) (yo usé la version de PHP de 8.4.6).
  * **Gestor de Base de Datos:** MySQL yo usé el de **MySQL Workbench**.

### 2\. Configuración de la Base de Datos

El primer paso es crear la base de datos y sus tablas.

1.  Abrir **MySQL Workbench**.
2.  Copia y ejecuta el siguiente script SQL. Este creará la base de datos `infografia_pwci`, todas las tablas necesarias y un usuario administrador de prueba que es mi nombre (lo puedes cambiar si quieres).

<!-- end list -->

```sql

CREATE DATABASE IF NOT EXISTS infografia_pwci;

USE infografia_pwci;

-- ----------------------------------------------------------------------
-- 1. CREACIÓN DE TABLAS PRINCIPALES
-- ----------------------------------------------------------------------

-- Tabla Usuario
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
    password VARCHAR(255) NOT NULL, 
    rol ENUM('Admin','Usuario') DEFAULT 'Usuario',
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estatus ENUM('Activo','Eliminado') DEFAULT 'Activo',
    tipoImagen VARCHAR(10)
);

-- Tabla Categoria
CREATE TABLE Categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    idUsuarioCreador INT NOT NULL,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuarioCreador) REFERENCES Usuario(id)
);

-- Tabla Mundial
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

-- Tabla Seleccion
CREATE TABLE Seleccion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    bandera LONGBLOB,
    tipoBandera VARCHAR(10)
);

-- Tabla Publicacion
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
    vistas INT DEFAULT 0,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id),
    FOREIGN KEY (idMundial) REFERENCES Mundial(id),
    FOREIGN KEY (idCategoria) REFERENCES Categoria(id),
    FOREIGN KEY (idSeleccion) REFERENCES Seleccion(id),
    FOREIGN KEY (idUsuarioAprobador) REFERENCES Usuario(id)
);

-- ----------------------------------------------------------------------
-- 2. NUEVAS TABLAS (Comentarios y Calificaciones)
-- ----------------------------------------------------------------------

-- Tabla Comentario
CREATE TABLE Comentario (
    idComentario INT PRIMARY KEY AUTO_INCREMENT,
    idPublicacion INT NOT NULL,
    idUsuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fechaComentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPublicacion) REFERENCES Publicacion(idPublicacion) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);

-- Tabla Calificacion (Likes/Dislikes)
CREATE TABLE Calificacion (
    idCalificacion INT PRIMARY KEY AUTO_INCREMENT,
    idPublicacion INT NOT NULL,
    idUsuario INT NOT NULL,
    valor TINYINT(1) NOT NULL CHECK (valor IN (1, -1)), 
    fechaCalificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_calificacion (idPublicacion, idUsuario), 
    FOREIGN KEY (idPublicacion) REFERENCES Publicacion(idPublicacion) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(id)
);


-- ----------------------------------------------------------------------
-- 3. INSERCIONES DE PRUEBA (Admin, Mundial, Categorías)
-- ----------------------------------------------------------------------

INSERT INTO Usuario (nombre, username, fechaNacimiento, genero, paisNacimiento, nacionalidad, email, password, rol) 
VALUES (
    'Roxanna',
    'bigigigil',
    '2005-01-03',
    'F',
    'México',
    'Mexicana',
    'roxanna@hotmail.com',
    -- Contraseña hasheada para 'cOntraseña_123' (VARCHAR 255 necesario)
    '$2y$10$HDZh.vNmivkAwho3tsbw6OhTIsFiOwoG0oO8DQ.VBU5vhxHwOf.FO', 
    'Admin'
);


INSERT INTO Mundial (nombre, año, sedePrincipal, idUsuarioCreador) 
VALUES ('Copa Mundial de la FIFA 2022', 2022, 'Qatar', 1);

INSERT INTO Categoria (nombre, descripcion, idUsuarioCreador) 
VALUES ('Jugadas', 'Análisis de jugadas y goles memorables.', 1),
       ('Estadísticas', 'Datos y números curiosos.', 1);

```

### 3\. Instalación de los Archivos del Proyecto

1.  Descarga o clona este repositorio.
2.  Copia la carpeta del proyecto llamada `PIA_PWCI` dentro del directorio `htdocs` de tu instalación de XAMPP. La ruta usualmente es: `C:\xampp\htdocs\`.

### 4\. Configuración de la Conexión

1.  Navega a la raíz del proyecto y abre el archivo `database.php`.

2.  Cambia con tu usuario y contraseña de MySQL. En la mayoría de los casos, solo necesitas cambiar la contraseña.

    ```php
    private $host = 'localhost';
    private $db_name = 'infografia_pwci';
    private $username = 'root';
    private $password = ''; // <-- Aqui escribe tu contraseña
    ```

### 5\. Iniciar el Servidor

1.  Abre el panel de control de **XAMPP**.
2.  Inicia los servicios de **Apache**.

### 6\. ¡Listo para Usar\! ٩(ˊᗜˋ*)و

Abre tu navegador web y ve a la siguiente dirección:

*ੈ✩‧₊˚ **[http://localhost/PIA\_PWCI/Infografia/](https://www.google.com/search?q=http://localhost/PIA_PWCI/Infografia/)**

```
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⡠⠤⠒⠒⠒⠂⠲⠤⣀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣤⣤⣤⣀⠀⣠⠞⣁⣴⣶⠀⠀⠀⠀⠀⠇⠀⠑⢆⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⡏⠁⠀⠉⠙⢻⠁⠰⣿⣿⠟⠀⠀⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣇⡀⠀⣀⣤⣧⣤⣮⣄⢀⣀⠀⠀⠀⠀⢠⠀⠀⠀⠀⡇⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⣿⣳⣛⠿⠿⣟⠋⠉⠀⠀⠀⠉⠐⠢⢄⣏⡀⠀⠀⠀⠇⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⠁⠀⠀⠀⠀⠈⠑⠠⣀⠀⠀⢀⡠⠔⠋⠀⠈⠉⠉⣶⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢠⣤⣤⣿⣤⠄⠀⢀⣤⡀⠀⠀⠈⠉⠉⠁⠀⠀⣠⣀⠀⠀⠶⢿⠟⠒⠂
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣿⣤⡄⠀⠘⠿⠃⠀⠀⢠⡤⣄⠀⠀⠀⠻⠏⠀⠀⢰⣿⠶⠤⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⢉⣿⣶⡆⠀⠀⠀⠀⠀⠘⠓⠛⠀⠀⠀⠀⠀⠀⣰⣿⢧⣄⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠐⠛⠉⠙⢛⣶⣤⣤⣄⣀⣀⣀⣀⣀⣀⣠⣤⣴⣾⠛⠁⠀⠈⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣤⣾⡿⢿⠿⠿⢿⣍⠉⠉⠉⣉⡽⠿⢿⣿⠿⢙⣦⡀⠀⠀⠀
⠀⠀⢀⣠⠤⡤⠤⣀⠀⠀⠀⠀⠀⣾⠋⠙⢳⠀⣴⣷⡄⠉⠛⢶⠞⠋⠀⢠⣿⣧⢰⠋⠙⢿⡀⠀⠀
⢀⣔⣉⣠⣶⣦⣄⣀⣹⣦⠀⠀⠀⢿⣄⣀⡞⠀⠀⠀⣧⣄⡄⣄⣦⢣⣤⡘⠿⠟⠘⣄⣠⡾⠁⠀⠀
⡾⠁⠀⢻⣿⣿⡇⠀⠀⢻⣇⠀⠀⠀⠈⠉⡇⠀⠀⠀⠛⠛⠛⠹⠛⠘⠘⠃⠀⠀⠀⡏⠉⠀⠀⠀⠀
⡿⣄⣰⠃⠀⠀⢱⣀⣀⠎⠸⠀⠀⠀⠀⠀⣿⠉⠉⠉⠑⠒⠒⢶⠒⠒⠒⠒⠒⠉⣿⡇⠀⠀⠀⠀⠀
⠹⣿⣿⣦⣀⣠⣾⣿⣿⣴⠃⠀⠀⠀⠀⠀⠻⣦⣀⠀⠀⠀⣀⣾⣄⠀⠀⠀⣀⣠⡿⠀⠀⠀⠀⠀⠀
⠀⠈⠻⠯⢀⣀⡼⠿⠛⠁⠀⠀⠀⠀⠀⠀⠀⠈⠉⠛⠛⠛⠉⠁⠉⠙⠛⠛⠋⠉⠀⠀⠀⠀⠀⠀⠀
```

*(**Nota:** Si renombraste la carpeta del proyecto, reemplaza `PIA_PWCI` con el nuevo nombre).*

¡Ahora deberías poder ver la página principal de la infografía y probar el inicio de sesión con el usuario administrador\!
