document.querySelector('form').addEventListener('submit', function (event) {
    event.preventDefault();

    const fullName = document.getElementById('full-name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const birthdate = document.getElementById('birthdate').value;
    const role = document.getElementById('role').value;
    const photo = document.getElementById('photo').value;
    const gender = document.getElementById('gender').value;

    let errorMessages = [];

    // Nombre Completo 
    if (fullName.trim() === '') {
        errorMessages.push('El nombre completo no puede estar vacío.');
    } else if (!/^[a-zA-Z\s]+$/.test(fullName)) {
        errorMessages.push('El nombre completo solo debe contener letras.');
    }

    // Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.trim() === '') {
        errorMessages.push('El correo electrónico no puede estar vacío.');
    } else if (!emailRegex.test(email)) {
        errorMessages.push('Por favor, introduce un correo electrónico válido.');
    }

    // Contraseña
    if (password.trim() === '') {
        errorMessages.push('La contraseña no puede estar vacía.');
    } else {
        if (password.length < 8) {
            errorMessages.push('La contraseña debe tener al menos 8 caracteres.');
        }
        if (!/[A-Z]/.test(password)) {
            errorMessages.push('La contraseña debe contener al menos una letra mayúscula.');
        }
        if (!/[0-9]/.test(password)) {
            errorMessages.push('La contraseña debe contener al menos un número.');
        }
        if (!/[!@#$%^&*]/.test(password)) {
            errorMessages.push('La contraseña debe contener al menos un carácter especial (por ejemplo: !@#$%^&*).');
        }
    }

    // Fecha de nacimiento
    var today = new Date().toISOString().split('T')[0];
    if (birthdate.trim() === '') {
        errorMessages.push('La fecha de nacimiento no puede estar vacía.');
    } else if (birthdate >= today) {
        errorMessages.push('La fecha de nacimiento no puede ser hoy ni una fecha futura.');
    }

    // Rol
    if (role === '') {
        errorMessages.push('Por favor, selecciona un rol.');
    }

    // Genero
    if (gender === '') {
        errorMessages.push('Por favor, selecciona un género.');
    }

    // Foto
    if (photo === '') {
        errorMessages.push('Por favor, carga una foto.');
    }

    // Mensajes
    if (errorMessages.length > 0) {
        event.preventDefault();
        errorMessages.forEach(function (message) {
            alert(message);
        });
        return;
    }

    const formData = new FormData(this);
    fetch('api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const errorDiv = document.getElementById("errorMessages");
        if (data.error) {
            errorDiv.innerHTML = `<p style="color:red;">${data.error}</p>`;
        } else if (data.message) {
            errorDiv.innerHTML = `<p style="color:green;">${data.message}</p>`;
            setTimeout(() => {
                window.location.href = 'index.php?page=Login';// Redirigir después de 3 segundos
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

});
