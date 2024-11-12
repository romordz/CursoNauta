<?php
class CategoriaModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registrarCategoria($nombre_categoria, $descripcion, $id_creador)
    {
        try {
            $sql = "CALL RegistrarCategoria(:nombre_categoria, :descripcion, :id_creador)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nombre_categoria", $nombre_categoria);
            $stmt->bindParam(":descripcion", $descripcion);
            $stmt->bindParam(":id_creador", $id_creador);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al registrar la categoría: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerCategorias($id_creador)
    {
        $query = "SELECT id_categoria, nombre_categoria, descripcion, activo FROM Categorias WHERE id_creador = :id_creador";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_creador', $id_creador, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategorias()
    {
        $query = "SELECT id_categoria, nombre_categoria FROM Categorias";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para actualizar el nombre y la descripción de una categoría
    public function actualizarCategoria($id_categoria, $nombre_categoria, $descripcion)
    {
        $query = "UPDATE Categorias SET nombre_categoria = :nombre_categoria, descripcion = :descripcion WHERE id_categoria = :id_categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_categoria', $nombre_categoria);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Método para cambiar el estado de una categoría (activar/desactivar)
    public function cambiarEstadoCategoria($id_categoria, $nuevoEstado)
    {
        $query = "UPDATE Categorias SET activo = :nuevoEstado WHERE id_categoria = :id_categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_BOOL);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
