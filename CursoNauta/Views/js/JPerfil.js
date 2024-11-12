// Obtener los datos del usuario 
document.addEventListener('DOMContentLoaded', function () {
    const userId = document.getElementById('userId').value;

    // Hacer la solicitud GET al Api
    fetch(`api.php?idUsuario=${userId}`)
        .then(response => response.json())
        .then(data => {

            console.log("Datos recibidos de la API:", data);

            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            // Rellenar campos 
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('rol').value = data.id_rol;
            document.getElementById('correo').value = data.correo;
            document.getElementById('genero').value = data.genero;
            document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento;
            document.getElementById('contrasena').value = data.contrasena;

            const profilePic = document.getElementById('profile-pic');
            profilePic.src = data.foto_avatar ? data.foto_avatar : 'Recursos/Perfil.jpg';
        })
        .catch(error => {
            console.error('Error al obtener los datos del perfil:', error);
        });
});

// Boton Editar
document.getElementById('edit-btn').addEventListener('click', function () {
    var form = document.getElementById('profile-form');
    var isEditing = form.classList.toggle('editing');
    var inputs = form.querySelectorAll('input, select');
    var button = form.querySelector('button[type="submit"]');
    var photoInput = document.getElementById('photo');

    inputs.forEach(input => input.disabled = !isEditing);
    button.style.display = isEditing ? 'block' : 'none';
    this.textContent = isEditing ? 'Cancelar' : 'Editar';

    photoInput.style.display = isEditing ? 'block' : 'none';
});

//Validaciones
document.getElementById('profile-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar la recarga de la página

    const userId = document.getElementById('userId').value;
    const nombre = document.getElementById('nombre').value;
    const correo = document.getElementById('correo').value;
    const contrasena = document.getElementById('contrasena').value;
    const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    const rol = document.getElementById('rol').value;
    const genero = document.getElementById('genero').value;
    const photoInput = document.getElementById('photo').files[0]; // Acceder al archivo seleccionado

    let errorMessages = [];

    // Validaciones
    if (nombre.trim() === '') {
        errorMessages.push('El nombre completo no puede estar vacío.');
    } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
        errorMessages.push('El nombre completo solo debe contener letras.');
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (correo.trim() === '') {
        errorMessages.push('El correo electrónico no puede estar vacío.');
    } else if (!emailRegex.test(correo)) {
        errorMessages.push('Por favor, introduce un correo electrónico válido.');
    }

    if (contrasena.trim() === '') {
        errorMessages.push('La contraseña no puede estar vacía.');
    } else {
        if (contrasena.length < 8) {
            errorMessages.push('La contraseña debe tener al menos 8 caracteres.');
        }
        if (!/[A-Z]/.test(contrasena)) {
            errorMessages.push('La contraseña debe contener al menos una letra mayúscula.');
        }
        if (!/[0-9]/.test(contrasena)) {
            errorMessages.push('La contraseña debe contener al menos un número.');
        }
        if (!/[!@#$%^&*]/.test(contrasena)) {
            errorMessages.push('La contraseña debe contener al menos un carácter especial (por ejemplo: !@#$%^&*).');
        }
    }


    if (fechaNacimiento.trim() === '') {
        errorMessages.push('La fecha de nacimiento no puede estar vacía.');
    }

    if (errorMessages.length > 0) {
        errorMessages.forEach(function (message) {
            alert(message);
        });
        return;
    }

    // Crear un FormData para enviar datos y archivo
    const formData = new FormData();
    formData.append('accion', 'modificar'); // Acción para el servidor
    formData.append('idUsuario', userId);
    formData.append('full_name', nombre);
    formData.append('correo', correo);
    formData.append('contrasena', contrasena);
    formData.append('fecha_nacimiento', fechaNacimiento);
    formData.append('role', rol);
    formData.append('genero', genero);
    if (photoInput) {
        formData.append('photo', photoInput);
    }

    // Enviar los datos al servidor con fetch
    fetch('api.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.message || 'Usuario modificado con éxito');
                document.getElementById('edit-btn').click();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
});

