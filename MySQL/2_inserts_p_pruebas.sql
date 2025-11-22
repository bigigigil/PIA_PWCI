USE infografia_pwci;

INSERT INTO Usuario (
    nombre, 
    username,
    fechaNacimiento, 
    genero, 
    paisNacimiento, 
    nacionalidad, 
    email, 
    password, 
    rol
) VALUES (
    'roxanna',
    'bigigigil',
    '2005-01-03',
    'F',
    'México',
    'Mexicana',
    'roxanna@hotmail.com',
    '$2y$10$HDZh.vNmivkAwho3tsbw6OhTIsFiOwoG0oO8DQ.VBU5vhxHwOf.FO', -- Contraseña_123
    'Admin'
);

INSERT INTO Mundial (nombre, año, sedePrincipal, idUsuarioCreador) 
VALUES ('Copa Mundial de la FIFA 2022', 2022, 'Qatar', 1);

INSERT INTO Categoria (nombre, descripcion, idUsuarioCreador) 
VALUES ('Jugadas', 'Análisis de jugadas y goles memorables.', 1),
       ('Estadísticas', 'Datos y números curiosos.', 1);