document.querySelector('form').addEventListener('submit', function(event) {
    const user = document.getElementById('email').value;
    const password = document.getElementById('password').value;
   

    let errorMessages = [];

    // Validación de usuario
    if (user.trim() === '') {
        errorMessages.push('Ingrese su Usuario');
    }

    // Validación de contraseña
    if (password.trim() === '') {
        errorMessages.push('Ingrese su contraseña');
    }

    // Mostrar mensajes de error uno por uno
    if (errorMessages.length > 0) {
        event.preventDefault();
        errorMessages.forEach(function(message) {
            alert(message);
        });
    }
});

