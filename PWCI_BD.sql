Create Database CursoNauta;
USE CursoNauta;

-- Tablas --

CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    rol_nombre ENUM('Administrador', 'Instructor', 'Estudiante')
);

CREATE TABLE Usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    genero ENUM('M', 'F', 'Otro'),
    fecha_nacimiento DATE,
    foto_avatar mediumblob,
    correo VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    intentos_fallidos INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
);

CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(255) NOT NULL,
    descripcion TEXT,
    id_creador INT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_creador) REFERENCES Usuarios(idUsuario)
);
ALTER TABLE Categorias
ADD COLUMN activo BOOLEAN DEFAULT TRUE;

CREATE TABLE Cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen BLOB,  -- Se cambió a BLOB para almacenar imágenes
    costo DECIMAL(10, 2) NOT NULL,
    niveles INT DEFAULT 1,
    calificacion_promedio DECIMAL(3, 2) DEFAULT 0,
    id_instructor INT,
    id_categoria INT,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_instructor) REFERENCES Usuarios(idUsuario),
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
);
ALTER TABLE Cursos
ADD fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP;


CREATE TABLE Niveles (
    id_nivel INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT,
    numero_nivel INT NOT NULL,
    titulo_nivel VARCHAR(255),
    video LONGBLOB,
    contenido TEXT,
    archivos LONGBLOB,
	costo DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso)
);

CREATE TABLE Inscripciones (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT,
    id_usuario INT,
    fecha_inscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_ultimo_acceso DATETIME,
    fecha_terminacion DATETIME,
    progreso DECIMAL(5, 2) DEFAULT 0,
    completado BOOLEAN DEFAULT FALSE,
	estado ENUM('en curso', 'completado', 'abandonado') DEFAULT 'en curso' ,
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(idUsuario)
);

CREATE TABLE Mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT,
    id_receptor INT,
    mensaje TEXT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_emisor) REFERENCES Usuarios(idUsuario),
    FOREIGN KEY (id_receptor) REFERENCES Usuarios(idUsuario)
);

CREATE TABLE Comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_curso INT,
    comentario TEXT,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    eliminado BOOLEAN DEFAULT FALSE,
    motivo_eliminacion TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(idUsuario),
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso)
);

CREATE TABLE Ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT,
    id_usuario INT,
    precio_pagado DECIMAL(10, 2),
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    forma_pago VARCHAR(50),
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(idUsuario)
);

-- Inserts, etc --
INSERT INTO Roles (rol_nombre)
VALUES 
('Administrador'),
('Instructor'),
('Estudiante');

DELETE FROM Usuarios;

INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) VALUES
(4, 3, '¡Hola! Este es un mensaje de prueba con imagen.');


-- Ver o borrar datos  --
SELECT * FROM Roles;
SELECT * FROM Usuarios;
SELECT * FROM Categorias;
SELECT * FROM Cursos;
SELECT * FROM Niveles;
SELECT * FROM Inscripciones;
SELECT * FROM Ventas;
SELECT * FROM Mensajes;
SELECT * FROM Comentarios;
DELETE FROM Categorias;
DELETE FROM Niveles;
DELETE FROM Cursos;
DELETE FROM Usuarios;
DELETE FROM Inscripciones;
DELETE FROM Ventas;
DELETE FROM Mensajes;
UPDATE Inscripciones
SET completado = 1
WHERE id_inscripcion = 6;


-- Quitao 
--
CREATE TABLE Kardex (
    id_kardex INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT,
    id_curso INT,
    progreso DECIMAL(5, 2),
    fecha_inscripcion DATETIME,
    fecha_ultimo_acceso DATETIME,
    fecha_terminacion DATETIME,
    FOREIGN KEY (idUsuario) REFERENCES Usuarios(idUsuario),
    FOREIGN KEY (id_curso) REFERENCES Cursos(id_curso)
);

