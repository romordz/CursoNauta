<?php include 'Views/Parciales/Head.php'; ?>

<link rel="stylesheet" href="Views/css/SPerfil.css">

<?php include 'Views/Parciales/Nav.php'; ?>

<?php
$userId = $_SESSION['user_id'];
?>

<!-- Aquí insertamos el userId desde PHP en un campo oculto -->
<input type="hidden" id="userId" value="<?php echo $userId; ?>">

<div class="edit-profile-container">
    <h2>Editar Perfil</h2>

    <div class="profile-pic">
        <img src="" alt="Foto de Perfil" id="profile-pic">
    </div>

    <button id="edit-btn">Editar</button>

    <form id="profile-form" action="" method="POST" enctype="multipart/form-data">
        <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">

        <label for="rol" hidden>Rol</label>
        <select id="rol" name="rol" hidden>
            <option value="3">Estudiante</option>
            <option value="2">Instructor</option>
        </select>

        <label for="nombre">Nombre Completo</label>
        <input type="text" class="inputext" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" disabled>

        <label for="genero">Género</label>
        <select id="genero" name="genero" disabled>
            <option value="F">Femenino</option>
            <option value="M">Masculino</option>
            <option value="Otro">Otro</option>
        </select>

        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" disabled>

        <label for="correo">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo electrónico" disabled>

        <label for="contrasena">Contraseña</label>
        <input type="text" class="inputext" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" disabled>

        <button type="submit" style="display: none;">Guardar Cambios</button>
    </form>

</div>

<script src="Views/js/JPerfil.js"></script>

<?php include 'Views/Parciales/Footer.php'; ?>
