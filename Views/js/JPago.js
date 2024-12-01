function processPurchase(courseId, price) {
    const paymentMethod = document.getElementById('paymentMethod').value;
    if (!paymentMethod) {
        alert('Por favor seleccione un método de pago');
        return;
    }

    // Asignar el método de pago al campo oculto
    document.getElementById('forma_pago').value = paymentMethod;
    document.getElementById('precio_pagado').value = price;

    // Enviar el formulario
    document.getElementById('purchaseForm').submit();
}
