<?php
require_once 'Models/UsuarioModel.php';

class UsuarioController
{
    private $usuarioModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->usuarioModel = new UsuarioModel($db);
    }

    public function mostrarUsuarios()
    {
        return $this->usuarioModel->obtenerUsuarios();
    }
    public function cambiarEstadoUsuario()
    {
        $usuarioIdActual = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idUsuario']) && isset($_POST['nuevoEstado'])) {
            $idUsuario = $_POST['idUsuario'];
            $nuevoEstado = (bool) $_POST['nuevoEstado'];

            // Verificar si el usuario está intentando desactivarse a sí mismo
            if ($idUsuario == $usuarioIdActual && $nuevoEstado == 0) {
            //     echo "<script>
            //     alert('No puedes desactivar tu propia cuenta.');
            //   </script>";
            //     return; 
            }else

            // Si no es el mismo usuario, cambiar el estado normalmente
            $this->usuarioModel->cambiarEstadoUsuario($idUsuario, $nuevoEstado);

            // Redirige para recargar la página y ver los cambios
            header("Location: index.php?page=Admi");
            exit;
        }
    }
}
