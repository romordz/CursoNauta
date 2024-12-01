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
        try {
            $stmt = $this->conn->prepare("CALL ObtenerCategorias(:id_creador)");
            $stmt->bindParam(':id_creador', $id_creador, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener categorías: " . $e->getMessage();
            return [];
        }
    }

    public function getAllCategorias()
    {
        try {
            $stmt = $this->conn->prepare("CALL ObtenerTodasCategorias()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener todas las categorías: " . $e->getMessage();
            return [];
        }
    }

    // Método para actualizar el nombre y la descripción de una categoría
    public function actualizarCategoria($id_categoria, $nombre_categoria, $descripcion)
    {
        try {
            $stmt = $this->conn->prepare("CALL ActualizarCategoria(:id_categoria, :nombre_categoria, :descripcion)");
            $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_categoria', $nombre_categoria);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar la categoría: " . $e->getMessage();
            return false;
        }
    }

    // Método para cambiar el estado de una categoría (activar/desactivar)
    public function cambiarEstadoCategoria($id_categoria, $nuevoEstado)
    {
        try {
            $stmt = $this->conn->prepare("CALL CambiarEstadoCategoria(:id_categoria, :nuevoEstado)");
            $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
            $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al cambiar el estado de la categoría: " . $e->getMessage();
            return false;
        }
    }
}
