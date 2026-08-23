<?php 
include_once 'Controllers/NavController.php';
$navController = new NavController();
$categoriasActivas = $navController->getCategoriasActivas();
?>

<header>
    <div class="logo">
        <img src="Views/Recursos/Icon2.png" alt="Logo de CursoNauta" class="logo-img">
        <h1>CursoNauta</h1>
    </div>

   <!-- Barra de búsqueda -->
   <div class="search-bar">
    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="All">
        <input type="text" name="search" placeholder="Buscar cursos...">
        <button type="submit" class="search-button" id="search-btn">
            <span class="material-icons">search</span>
        </button>
    </form>
</div>


    <nav>
        <ul>
            <li><a href="index.php?page=Principal">Inicio</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-btn">Categorías</a>
                <ul class="dropdown-content">
                    <?php foreach ($categoriasActivas as $categoria): ?>
                        <li><a href="index.php?page=All&categoria=<?= $categoria['id_categoria'] ?>">
                            <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li><a href="index.php?page=All">Cursos</a></li>
        </ul>
    </nav>

    <div class="user-profile" id="user-profile">
        <!-- <?php
        session_start();
        ?> -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="index.php?page=Login" class="btn-login">Iniciar Sesión</a>
        <?php else:
            $id_rol = $_SESSION['user_role'];

            $role_name = '';
            switch ($id_rol) {
                case 1:
                    $role_name = 'Administrador';
                    break;
                case 2:
                    $role_name = 'Instructor';
                    break;
                case 3:
                    $role_name = 'Estudiante';
                    break;
                default:
                    $role_name = 'Desconocido';
                    break;
            }
        ?>
            <a href="" class="profile-toggle">
                <img src="<?php echo $_SESSION['user_img']; ?>" alt="Usuario" class="user-img">
            </a>
            <div class="user-info profile-toggle">
                <p class="user-name"><?php echo $_SESSION['user_name']; ?></p>
                <p class="user-role"><?php echo ucfirst($role_name); ?></p>
            </div>

            <ul class="dropdown-menu">
                <li><a href="index.php?page=Perfil">Mi perfil</a></li>

                <?php if ($_SESSION['user_role'] == '3'): ?>
                    <li><a href="index.php?page=Mensajes">Mis Mensajes</a></li>
                    <li><a href="index.php?page=Kardex">Kardex</a></li>

                <?php elseif ($_SESSION['user_role'] == '2'): ?>
                    <li><a href="index.php?page=Mensajes">Mis Mensajes</a></li>
                    <li><a href="index.php?page=Ventas">Mis Ventas</a></li>
                <?php elseif ($_SESSION['user_role'] == '1'): ?>

                    <li><a href="index.php?page=Admi">Administración</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=logout">Cerrar Sesión</a></li>
            </ul>
        <?php endif; ?>
    </div>
</header>

<script src="Views/js/Nav.js"></script>