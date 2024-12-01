DELIMITER $$
CREATE PROCEDURE ActualizarNivel(
    IN p_id_nivel INT,          
    IN p_titulo_nivel VARCHAR(255), 
    IN p_contenido TEXT,       
    IN p_costo DECIMAL(10, 2)  
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Niveles
        WHERE id_nivel = p_id_nivel
    ) THEN
        UPDATE Niveles
        SET 
            titulo_nivel = p_titulo_nivel,
            contenido = p_contenido,
            costo = p_costo
        WHERE id_nivel = p_id_nivel;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El nivel especificado no existe.';
    END IF;
END$$
DELIMITER ;


DROP PROCEDURE ActualizarNivel;
