<?php
include 'Views/Parciales/Head.php';
include 'Views/Parciales/Nav.php';
include 'Controllers\MensajesController.php';
?>

<link rel="stylesheet" href="Views/css/SMensajes.css">

<div class="container">
    <!-- Sidebar para los instructores -->
    <aside class="sidebar">
        <h2>Instructores</h2>
        <ul>
            <?php foreach ($instructores as $instructor): ?>
                <li>
                    <a href="index.php?page=Mensajes&user_id=<?= $instructor['idUsuario'] ?>">
                        <img src="<?= htmlspecialchars($instructor['foto_avatar']) ?>" alt="<?= htmlspecialchars($instructor['nombre']) ?>" class="instructor-img">
                        <span><?= htmlspecialchars($instructor['nombre']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <!-- Contenedor principal de chat -->
    <main class="main-content">
        <section class="messages">
            <h2>Mensajes Privados</h2>

            <div class="message-container">
                <?php foreach ($mensajes as $mensaje): ?>
                    <div class="message <?= $mensaje['id_emisor'] == $id_emisor ? '' : 'instructor' ?>">
                        <img src="<?= htmlspecialchars($mensaje['foto_avatar']) ?>" alt="<?= htmlspecialchars($mensaje['nombre']) ?>" class="user-img">
                        <div class="message-content">
                            <p class="message-text"><?= htmlspecialchars($mensaje['mensaje']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <!-- Formulario para enviar mensajes -->
            <div class="message-form">
                <form action="index.php?page=Mensajes&user_id=<?= $id_receptor ?>" method="POST">
                    <textarea name="mensaje" placeholder="Escribe tu mensaje..."></textarea>
                    <button type="submit" class="btn">Enviar</button>
                </form>
            </div>
        </section>
    </main>
</div>

<?php include 'Views/Parciales/Footer.php'; ?>