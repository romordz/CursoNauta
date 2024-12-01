
<?php include 'Views/Parciales/Head.php'; ?>
<link rel="stylesheet" href="Views/css/SPago.css">

<?php include 'Views/Parciales/Nav.php'; ?>

<?php
require_once 'Controllers/CursoController.php';

$cursoController = new CursoController();
$idCurso = isset($_GET['idCurso']) ? (int)$_GET['idCurso'] : 0;

if ($idCurso > 0) {
    $curso = $cursoController->obtenerCursoPorId($idCurso);
} else {
    echo "Curso no encontrado.";
    exit;
}
?>

<div class="container">
    <div class="course-details">
        <h2>Detalles del Curso</h2>
        <div class="course" id="course-<?php echo $idCurso; ?>">
            <div class="course-info">
                <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                <p class="course-description"><?php echo htmlspecialchars($curso['descripcion'] ?? ''); ?></p>
                <div class="course-price">
                    <p>Precio: $<?php echo htmlspecialchars($curso['costo']); ?></p>
                </div>
            </div>

            <div class="payment-section">
                <div class="payment-method">
                    <h3>Método de Pago</h3>
                    <select id="paymentMethod">
                        <option value="">Seleccione un método de pago</option>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                        <option value="paypal">PayPal</option>
                        <option value="transferencia">Transferencia Bancaria</option>
                    </select>
                </div>

                <form action="index.php?page=IC" method="POST" id="purchaseForm">
                    <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>">
                    <input type="hidden" name="action" value="registrarInscripcion">
                    <input type="hidden" name="forma_pago" id="forma_pago">
                    <input type="hidden" name="precio_pagado" id="precio_pagado">

                    <button type="button" class="purchase-btn" onclick="processPurchase(<?php echo $idCurso; ?>, <?php echo $curso['costo']; ?>)">
                        Comprar Ahora
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>


<script src="Views\js\JPago.js"> </script>

<?php include 'Views/Parciales/Footer.php'; ?>