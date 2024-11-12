<?php include 'Views\Parciales\Head.php'; ?>
<link rel="stylesheet" href="Views\\css\\SLoRe.css">

<div class="register-container">
    <h2>Iniciar Sesión</h2>

    <form action="index.php?page=LC" method="POST">
        <div class="form-group">
            <label for="email">Correo:</label>
            <input type="email" id="email" name="correo" value="<?php echo isset($correo_valor) ? htmlspecialchars($correo_valor) : ''; ?>">
            <?php if (!empty($error_correo)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error_correo); ?></p>
                </div>
            <?php endif; ?>
         
            <?php if (!empty($error_desactivada)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error_desactivada); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="contrasena">
            <?php if (!empty($error_contrasena)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error_contrasena); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-register">Iniciar Sesión</button>

        <div class="login-link">
            <p>¿No tienes cuenta? <a href="index.php?page=Registro">Regístrate aquí</a></p>
        </div>
    </form>

</div>
<script src="Views\js\JLogin.js"> </script>

</body>