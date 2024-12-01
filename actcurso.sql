DELIMITER $$
CREATE PROCEDURE ActualizarCurso(
    IN p_id_curso INT,
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_imagen BLOB,
    IN p_costo DECIMAL(10, 2),
    IN p_id_categoria INT
)
BEGIN
    UPDATE Cursos
    SET
        titulo = p_titulo,
        descripcion = p_descripcion,
        imagen = IF(p_imagen IS NOT NULL, p_imagen, imagen), -- Solo actualiza la imagen si no es NULL
        costo = p_costo,
        id_categoria = p_id_categoria
    WHERE id_curso = p_id_curso;
END$$
DELIMITER ;

CALL ActualizarCurso(1, 'Nuevo Título', 'Descripción actualizada', NULL, 100.00, 2);

