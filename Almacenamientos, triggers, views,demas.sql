USE CursoNauta;
-- Procedimientos Almacenados --
-- IMPLEMENTADOS --

-- USUARIOS --
-- Procedimiento para bloquear usuario después de 3 intentos fallidos
DELIMITER //
CREATE PROCEDURE BloquearUsuario(IN user_id INT)
BEGIN
    UPDATE Usuarios SET activo = FALSE WHERE idUsuario = user_id;
END //
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerUsuarios()
BEGIN
    SELECT idUsuario, nombre, correo, fecha_registro, activo, id_rol
    FROM Usuarios;
END $$
DELIMITER ;


-- Procedimiento para Cambiar Estado
DELIMITER //
CREATE PROCEDURE CambiarEstadoUsuario(
    IN p_idUsuario INT,
    IN p_nuevoEstado BOOLEAN
)
BEGIN
    UPDATE Usuarios
    SET activo = p_nuevoEstado
    WHERE idUsuario = p_idUsuario;
END //
DELIMITER ;


-- CATEGORIAS --
-- Registro de Categoría
DELIMITER //
CREATE PROCEDURE RegistrarCategoria(
    IN nombre_categoria VARCHAR(255),
    IN descripcion TEXT,
    IN id_creador INT
)
BEGIN
    INSERT INTO Categorias (nombre_categoria, descripcion, id_creador)
    VALUES (nombre_categoria, descripcion, id_creador);
END //
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerCategorias(IN id_creador INT)
BEGIN
    SELECT id_categoria, nombre_categoria, descripcion, activo 
    FROM Categorias 
    WHERE id_creador = id_creador;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerTodasCategorias()
BEGIN
    SELECT id_categoria, nombre_categoria 
    FROM Categorias;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ActualizarCategoria(
    IN id_categoria INT, 
    IN nombre_categoria VARCHAR(255), 
    IN descripcion TEXT
)
BEGIN
    UPDATE Categorias 
    SET nombre_categoria = nombre_categoria, descripcion = descripcion 
    WHERE id_categoria = id_categoria;
END $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE CambiarEstadoCategoria(
    IN id_categoria INT, 
    IN nuevoEstado BOOL
)
BEGIN
    UPDATE Categorias 
    SET activo = nuevoEstado 
    WHERE id_categoria = id_categoria;
END $$
DELIMITER ;



-- CURSOS --
-- Procedimiento para publicar un curso
DELIMITER //
CREATE PROCEDURE InsertarCurso(
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_imagen BLOB,
    IN p_costo DECIMAL(10, 2),
    IN p_niveles INT,
    IN p_id_instructor INT,
    IN p_id_categoria INT,
    OUT p_id_curso INT
)
BEGIN
    -- Insertar el curso en la tabla Cursos
    INSERT INTO Cursos (titulo, descripcion, imagen, costo, niveles, id_instructor, id_categoria)
    VALUES (p_titulo, p_descripcion, p_imagen, p_costo, p_niveles, p_id_instructor, p_id_categoria);

    -- Obtener el id del curso recién insertado y asignarlo a la variable de salida
    SET p_id_curso = LAST_INSERT_ID();
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_cursos()
BEGIN
    SELECT cursos.id_curso, cursos.titulo, cursos.descripcion, cursos.activo, usuarios.nombre AS instructor_nombre
    FROM cursos
    JOIN usuarios ON cursos.id_instructor = usuarios.idUsuario;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_actualizar_estado_curso(IN idCurso INT, IN nuevoEstado INT)
BEGIN
    UPDATE cursos SET activo = nuevoEstado WHERE id_curso = idCurso;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_curso_por_id(IN idCurso INT)
BEGIN
    SELECT 
        Cursos.*, 
        Usuarios.nombre AS nombre_creador, 
        Categorias.nombre_categoria AS nombre_categoria 
    FROM Cursos
    JOIN Usuarios ON Cursos.id_instructor = Usuarios.idUsuario
    JOIN Categorias ON Cursos.id_categoria = Categorias.id_categoria
    WHERE Cursos.id_curso = idCurso;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_cursos_por_instructor(IN id_instructor INT)
BEGIN
    SELECT 
        c.id_curso,
        c.titulo,
        COUNT(i.id_usuario) AS alumnos_inscritos,
        IFNULL(AVG(i.progreso), 0) AS nivel_promedio,
        IFNULL(SUM(v.precio_pagado), 0) AS ingresos_totales,
        c.activo
    FROM Cursos c
    LEFT JOIN Inscripciones i ON c.id_curso = i.id_curso
    LEFT JOIN Ventas v ON c.id_curso = v.id_curso
    WHERE c.id_instructor = id_instructor
    GROUP BY c.id_curso, c.titulo, c.activo;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_total_ingresos(IN id_instructor INT)
BEGIN
    SELECT SUM(v.precio_pagado) AS total_ingresos
    FROM ventas v
    JOIN cursos c ON v.id_curso = c.id_curso
    WHERE c.id_instructor = id_instructor;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_niveles_por_curso(IN idCurso INT)
BEGIN
    SELECT * FROM Niveles WHERE id_curso = idCurso ORDER BY numero_nivel;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_valoracion_promedio(IN idCurso INT)
BEGIN
    DECLARE promedio FLOAT;

    SELECT obtenerPromedioCurso(idCurso) INTO promedio;
    
    UPDATE Cursos SET calificacion_promedio = promedio WHERE id_curso = idCurso;

    SELECT promedio AS promedio;
END; //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_obtener_comentarios(IN idCurso INT)
BEGIN
    SELECT c.comentario, c.calificacion, c.fecha_comentario, c.id_usuario, c.eliminado,
           u.nombre AS nombre_usuario, u.foto_avatar 
    FROM Comentarios AS c
    JOIN Usuarios AS u ON c.id_usuario = u.idUsuario
    WHERE c.id_curso = idCurso
    ORDER BY c.fecha_comentario DESC;
END; //
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerComentario(IN id_curso INT, IN id_usuario INT)
BEGIN
    SELECT comentario, calificacion, fecha_comentario
    FROM Comentarios
    WHERE id_curso = id_curso 
    AND id_usuario = id_usuario 
    AND eliminado = 0;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GuardarComentario(
    IN id_curso INT, 
    IN id_usuario INT, 
    IN comentario TEXT, 
    IN calificacion INT
)
BEGIN
    INSERT INTO Comentarios (id_curso, id_usuario, comentario, calificacion)
    VALUES (id_curso, id_usuario, comentario, calificacion);
END $$
DELIMITER ;

-- MENSAJES --
DELIMITER $$
CREATE PROCEDURE ObtenerInstructoresConMensajes(IN id_emisor INT)
BEGIN
    SELECT DISTINCT u.idUsuario, u.nombre, u.foto_avatar
    FROM Usuarios u
    JOIN Mensajes m 
        ON (u.idUsuario = m.id_receptor AND m.id_emisor = id_emisor)
        OR (u.idUsuario = m.id_emisor AND m.id_receptor = id_emisor);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerMensajesEntreUsuarios(IN id_emisor INT, IN id_receptor INT)
BEGIN
    SELECT m.*, u.foto_avatar, u.nombre
    FROM Mensajes m
    JOIN Usuarios u ON u.idUsuario = m.id_emisor
    WHERE (m.id_emisor = id_emisor AND m.id_receptor = id_receptor) 
       OR (m.id_emisor = id_receptor AND m.id_receptor = id_emisor)
    ORDER BY m.fecha_hora ASC;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE IniciarChatSiNoExiste(IN id_emisor INT, IN id_receptor INT)
BEGIN
    -- Verificar si ya existe un chat entre el emisor y el receptor
    DECLARE chat_existe INT;

    SELECT COUNT(*) INTO chat_existe
    FROM Mensajes
    WHERE (id_emisor = id_emisor AND id_receptor = id_receptor) 
       OR (id_emisor = id_receptor AND id_receptor = id_emisor);

    -- Si no existe, crear un mensaje de bienvenida
    IF chat_existe = 0 THEN
        INSERT INTO Mensajes (id_emisor, id_receptor, mensaje) 
        VALUES (id_emisor, id_receptor, 'Hola, este es el inicio de nuestra conversación');
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE EnviarMensaje(IN id_emisor INT, IN id_receptor INT, IN mensaje TEXT)
BEGIN
    INSERT INTO Mensajes (id_emisor, id_receptor, mensaje)
    VALUES (id_emisor, id_receptor, mensaje);
END $$
DELIMITER ;





-- NIVELES --
DELIMITER //
CREATE PROCEDURE InsertarNivel(
    IN p_id_curso INT,
    IN p_numero_nivel INT,
    IN p_titulo_nivel VARCHAR(255),
    IN p_video LONGBLOB,
    IN p_contenido TEXT,
    IN p_archivos LONGBLOB,
    IN p_costo DECIMAL(10, 2)
)
BEGIN
    -- Insertar el nivel en la tabla Niveles
    INSERT INTO Niveles (id_curso, numero_nivel, titulo_nivel, video, contenido, archivos, costo)
    VALUES (p_id_curso, p_numero_nivel, p_titulo_nivel, p_video, p_contenido, p_archivos, p_costo);
END //
DELIMITER ;


-- INSCRIPCIONES --
DELIMITER //
CREATE PROCEDURE RegistrarInscripcion(
    IN p_id_curso INT,
    IN p_id_usuario INT
)
BEGIN
    INSERT INTO Inscripciones (id_curso, id_usuario, fecha_inscripcion, fecha_ultimo_acceso, progreso, estado)
    VALUES (p_id_curso, p_id_usuario, NOW(), NOW(), 0, 'en curso');
END //
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerCursosInscritos(IN id_usuario INT)
BEGIN
    SELECT i.*, 
           c.titulo AS curso_titulo, 
           cat.nombre_categoria AS categoria
    FROM Inscripciones i
    JOIN Cursos c ON i.id_curso = c.id_curso
    JOIN Categorias cat ON c.id_categoria = cat.id_categoria
    WHERE i.id_usuario = id_usuario;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE RegistrarVenta(IN id_curso INT, IN id_usuario INT, IN precio_pagado DECIMAL(10,2), IN forma_pago VARCHAR(50))
BEGIN
    INSERT INTO Ventas (id_curso, id_usuario, precio_pagado, forma_pago)
    VALUES (id_curso, id_usuario, precio_pagado, forma_pago);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerDatosCertificado(IN id_curso INT, IN id_usuario INT)
BEGIN
    SELECT u.nombre AS nombre_estudiante,
           c.titulo AS nombre_curso,
           i.fecha_terminacion,
           instructor.nombre AS nombre_instructor
    FROM Inscripciones i
    JOIN Usuarios u ON i.id_usuario = u.idUsuario
    JOIN Cursos c ON i.id_curso = c.id_curso
    JOIN Usuarios instructor ON c.id_instructor = instructor.idUsuario
    WHERE i.id_curso = id_curso AND i.id_usuario = id_usuario AND i.completado = 1;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerCategoriasActivas()
BEGIN
    SELECT id_categoria, nombre_categoria 
    FROM Categorias 
    WHERE activo = TRUE;
END $$
DELIMITER ;








-- Funcion1 ---
DELIMITER //
CREATE FUNCTION obtenerPromedioCurso(idCurso INT)
RETURNS DECIMAL(3, 2)
DETERMINISTIC
BEGIN
    DECLARE promedio DECIMAL(3, 2);

    SELECT AVG(calificacion)
    INTO promedio
    FROM Comentarios
    WHERE id_curso = idCurso AND eliminado = 0;

    RETURN IFNULL(promedio, 0);
END //
DELIMITER ;

DELIMITER $$
CREATE FUNCTION inscripcionYaRegistrada(idCurso INT, idUsuario INT) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE resultado INT;

    SELECT COUNT(*) INTO resultado
    FROM Inscripciones
    WHERE id_curso = idCurso AND id_usuario = idUsuario;

    RETURN resultado;
END $$
DELIMITER ;





-- VIEWS --
-- Cursos más vendidos ---
CREATE OR REPLACE VIEW CursosMasVendidos AS
SELECT c.id_curso, c.titulo, c.imagen, c.descripcion, c.costo, cat.nombre_categoria, COUNT(v.id_venta) AS total_ventas
FROM cursos c
JOIN ventas v ON c.id_curso = v.id_curso
JOIN categorias cat ON c.id_categoria = cat.id_categoria
WHERE c.activo = TRUE
GROUP BY c.id_curso, c.titulo, c.imagen, c.descripcion, c.costo, cat.nombre_categoria
ORDER BY total_ventas DESC;

-- Cursos recientes ---
CREATE OR REPLACE VIEW CursosRecientes AS
SELECT c.id_curso, c.titulo, c.imagen, c.descripcion, c.costo, cat.nombre_categoria, cat.fecha_creacion
FROM cursos c
JOIN categorias cat ON c.id_categoria = cat.id_categoria
WHERE c.activo = TRUE
ORDER BY cat.fecha_creacion DESC
LIMIT 10;

-- Cursos mejor calificados ---
CREATE OR REPLACE VIEW CursosMejorCalificados AS
SELECT c.id_curso, c.titulo, c.imagen, c.descripcion, c.costo, cat.nombre_categoria, c.calificacion_promedio
FROM cursos c
JOIN categorias cat ON c.id_categoria = cat.id_categoria
WHERE c.activo = TRUE
ORDER BY c.calificacion_promedio DESC;



-- Vista de cursos activos
CREATE OR REPLACE VIEW CursosActivos AS
SELECT 
    c.id_curso,
    c.titulo,
    c.descripcion,
    c.imagen,
    c.costo,
    c.niveles,
    c.calificacion_promedio,
    c.id_instructor,
    c.id_categoria,
    c.fecha_creacion,
    i.nombre AS nombre_instructor,
    cat.nombre_categoria
FROM cursos c
JOIN usuarios i ON c.id_instructor = i.idUsuario
JOIN categorias cat ON c.id_categoria = cat.id_categoria
WHERE c.activo = TRUE;


-- Vista instructores --
CREATE OR REPLACE VIEW InstructoresActivos AS
SELECT 
    idUsuario,
    nombre,
    foto_avatar,
    correo
FROM 
    Usuarios
WHERE 
    id_rol = 2
    AND activo = TRUE;



-- Categorias activas
-- Con Cursos activos
CREATE VIEW CategoriasActivas AS
SELECT DISTINCT cat.id_categoria, cat.nombre_categoria
FROM categorias cat
JOIN cursos c ON cat.id_categoria = c.id_categoria
WHERE c.activo = TRUE;

-- No cursos Activos--
CREATE OR REPLACE VIEW CategoriasActivas AS
SELECT id_categoria, nombre_categoria
FROM Categorias
WHERE activo = TRUE;


-- Reportes
CREATE VIEW ReporteInstructores AS
SELECT 
    u.idUsuario AS id_instructor,
    u.nombre AS nombre_instructor,
    u.fecha_registro AS fecha_ingreso,
    COUNT(c.id_curso) AS cantidad_cursos_ofrecidos,
    IFNULL(SUM(v.precio_pagado), 0) AS total_ganancias
FROM 
    Usuarios u
LEFT JOIN 
    Cursos c ON u.idUsuario = c.id_instructor
LEFT JOIN 
    Ventas v ON c.id_curso = v.id_curso
WHERE 
    u.id_rol = (SELECT id_rol FROM Roles WHERE rol_nombre = 'Instructor')
GROUP BY 
    u.idUsuario, u.nombre, u.fecha_registro;
    

CREATE VIEW ReporteEstudiantes AS
SELECT 
    u.idUsuario AS id_estudiante,
    u.nombre AS nombre_estudiante,
    u.fecha_registro AS fecha_ingreso,
    COUNT(i.id_inscripcion) AS cantidad_cursos_inscritos,
    CONCAT(
        ROUND(
            (SUM(CASE WHEN i.completado = TRUE THEN 1 ELSE 0 END) / COUNT(i.id_inscripcion)) * 100, 
            2
        ), 
        '%'
    ) AS porcentaje_cursos_terminados
FROM 
    Usuarios u
LEFT JOIN 
    Inscripciones i ON u.idUsuario = i.id_usuario
WHERE 
    u.id_rol = (SELECT id_rol FROM Roles WHERE rol_nombre = 'Estudiante')
GROUP BY 
    u.idUsuario, u.nombre, u.fecha_registro;



DELIMITER $$
-- Procedimiento para obtener el reporte de instructores
CREATE PROCEDURE ObtenerReporteInstructores()
BEGIN
    SELECT 
        id_instructor, 
        nombre_instructor, 
        fecha_ingreso, 
        cantidad_cursos_ofrecidos, 
        total_ganancias
    FROM ReporteInstructores;
END $$

-- Procedimiento para obtener el reporte de estudiantes
CREATE PROCEDURE ObtenerReporteEstudiantes()
BEGIN
    SELECT 
        id_estudiante, 
        nombre_estudiante, 
        fecha_ingreso, 
        cantidad_cursos_inscritos, 
        porcentaje_cursos_terminados
    FROM ReporteEstudiantes;
END $$
DELIMITER ;


-- BUSQUEDAS --
DELIMITER //
CREATE PROCEDURE BuscarCursosPorPalabraClave(IN palabraClave VARCHAR(255))
BEGIN
    SELECT c.id_curso, c.titulo, c.descripcion, c.imagen, c.costo, c.niveles, c.calificacion_promedio, 
           cat.nombre_categoria, u.nombre AS nombre_instructor
    FROM cursos c
    JOIN categorias cat ON c.id_categoria = cat.id_categoria
    JOIN usuarios u ON c.id_instructor = u.idUsuario
    WHERE c.activo = TRUE
      AND (c.titulo LIKE CONCAT('%', palabraClave, '%') 
           OR c.descripcion LIKE CONCAT('%', palabraClave, '%'));
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE BuscarCursosDinamico(
    IN in_categoriaID INT,
    IN in_instructorID INT,
    IN in_fechaInicio DATE,
    IN in_fechaFin DATE
)
BEGIN
    SELECT * FROM CursosActivos
    WHERE (in_categoriaID IS NULL OR id_categoria = in_categoriaID)
      AND (in_instructorID IS NULL OR id_instructor = in_instructorID)
      AND (in_fechaInicio IS NULL OR fecha_creacion >= in_fechaInicio)
      AND (in_fechaFin IS NULL OR fecha_creacion <= in_fechaFin);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE BuscarKardexDinamico(
    IN in_categoriaID INT,
    IN in_estado VARCHAR(20),
    IN in_fechaInicio DATE,
    IN in_fechaFin DATE,
    IN in_usuarioID INT
)
BEGIN
    SELECT 
        i.id_inscripcion,
        c.titulo AS curso_titulo,
        i.fecha_inscripcion,
        i.fecha_ultimo_acceso,
        i.progreso,
        i.fecha_terminacion,
        cat.nombre_categoria AS categoria,
        i.estado
    FROM Inscripciones i
    JOIN Cursos c ON i.id_curso = c.id_curso
    JOIN Categorias cat ON c.id_categoria = cat.id_categoria
    WHERE i.id_usuario = in_usuarioID
      AND (in_categoriaID IS NULL OR c.id_categoria = in_categoriaID)
      AND (in_estado IS NULL OR i.estado = in_estado)
      AND (in_fechaInicio IS NULL OR i.fecha_inscripcion >= in_fechaInicio)
      AND (in_fechaFin IS NULL OR i.fecha_inscripcion <= in_fechaFin);
END //
DELIMITER ;















-- No Implementadas --
-- triggers
DELIMITER //
CREATE TRIGGER IncrementarIntentosFallidos
AFTER UPDATE ON Usuarios
FOR EACH ROW
BEGIN
    -- Solo incrementar si la contraseña fue incorrecta
    IF NEW.activo = TRUE AND NEW.intentos_fallidos < 3 THEN
        UPDATE Usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE idUsuario = NEW.idUsuario;
    END IF;
END //
DELIMITER ;

drop TRIGGER IncrementarIntentosFallidos;

DELIMITER //
CREATE TRIGGER BloquearUsuarioDespuesDeTresIntentos
AFTER UPDATE ON Usuarios
FOR EACH ROW
BEGIN
    -- Si los intentos fallidos alcanzan 3, bloquear al usuario
    IF NEW.intentos_fallidos >= 3 THEN
        UPDATE Usuarios SET activo = FALSE WHERE idUsuario = NEW.idUsuario;
    END IF;
END //
DELIMITER ;

drop TRIGGER BloquearUsuarioDespuesDeTresIntentos;

-- trigger reestablecer intentos fallidos luego de un inicio de sesion exitoso
DELIMITER //

CREATE TRIGGER RestablecerIntentosFallidos
BEFORE UPDATE ON Usuarios
FOR EACH ROW
BEGIN
    -- Si el usuario se activa, restablecer los intentos fallidos a 0
    IF NEW.activo = TRUE AND OLD.activo = FALSE THEN
        SET NEW.intentos_fallidos = 0;  -- Restablecer intentos fallidos antes de la actualización
    END IF;
END //

DELIMITER ;

drop TRIGGER RestablecerIntentosFallidos;

SHOW TRIGGERS;

-- trigger para manejar inscripciones de cursos
DELIMITER //
CREATE TRIGGER RegistrarFechaUltimoAcceso
AFTER INSERT ON inscripciones
FOR EACH ROW
BEGIN
    -- Actualizar la fecha del último acceso a la fecha de inscripción
    UPDATE inscripciones
    SET fecha_ultimo_acceso = NOW()
    WHERE id_inscripcion = NEW.id_inscripcion;
END //

-- actualizacion de progreso
DELIMITER //
CREATE TRIGGER ActualizarEstadoKardex
AFTER UPDATE ON kardex
FOR EACH ROW
BEGIN
    -- Verificar si el progreso es mayor a 0 pero menor a 100, actualizar estado a 'en curso'
    IF NEW.progreso > 0 AND NEW.progreso < 100 THEN
        UPDATE kardex
        SET estado = 'en curso'
        WHERE id_kardex = NEW.id_kardex;
    END IF;

    -- Verificar si el progreso es igual a 100, actualizar estado a 'completado' y fecha_terminacion
    IF NEW.progreso = 100 THEN
        UPDATE kardex
        SET estado = 'completado',
            fecha_terminacion = NOW()
        WHERE id_kardex = NEW.id_kardex;
    END IF;
END;
//
DELIMITER ;

-- actualizar progreso curso
DELIMITER //

CREATE TRIGGER ActualizarProgreso
AFTER UPDATE ON Inscripciones
FOR EACH ROW
BEGIN
    -- Verificar si el progreso está entre 0 y 100, y actualizar la tabla kardex
    IF NEW.progreso >= 0 AND NEW.progreso <= 100 THEN
        UPDATE kardex
        SET progreso = NEW.progreso
        WHERE idUsuario = NEW.idUsuario AND id_curso = NEW.id_curso;
    END IF;
END //

DELIMITER ;



