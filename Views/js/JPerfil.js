// Obtener los datos del usuario 
function convertToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}   

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
if (data.foto_avatar) {
    // Verifica si la cadena ya contiene el prefijo "data:image/jpeg;base64,"
    profilePic.src = data.foto_avatar.startsWith('data:image') 
        ? data.foto_avatar 
        : 'data:image/jpeg;base64,' + data.foto_avatar;
} else {
    profilePic.src = 'Recursos/Perfil.jpg';
}

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
    let photoBase64 = null;
    if (photoInput) {
        convertToBase64(photoInput).then(base64Image => {
            console.log(base64Image); // Verifica la salida aquí
            photoBase64 = base64Image.split(',')[1]; // Eliminar el prefijo "data:image/png;base64,"
            enviarFormulario(photoBase64);
        }).catch(error => {
            console.error('Error al convertir la imagen a base64:', error);
        });
    } else {
        enviarFormulario(photoBase64); // Enviar formulario sin imagen
    }

    function enviarFormulario(photoBase64) {
    const formData = new FormData();
    formData.append('accion', 'modificar'); // Acción para el servidor
    formData.append('idUsuario', userId);
    formData.append('full_name', nombre);
    formData.append('correo', correo);
    formData.append('contrasena', contrasena);
    formData.append('fecha_nacimiento', fechaNacimiento);
    formData.append('role', rol);
    formData.append('genero', genero);
    if (photoBase64) {
        console.log("Imagen base64 enviada:", photoBase64); // Verifica si la foto está siendo agregada
        formData.append('photo_base64', photoBase64);
    }

    // Enviar los datos al servidor con fetch
    fetch('api.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())  // Usar .text() para ver el contenido crudo
        .then(responseText => {
            console.log('Texto crudo recibido:', responseText);  // Ver qué está devolviendo el servidor
            try {
                const data = JSON.parse(responseText);  // Intentar convertir manualmente a JSON
                if (data.error) {
                    alert(data.error);
                } else {
                    alert(data.message || 'Usuario modificado con éxito');
                    document.getElementById('edit-btn').click();
                }
            } catch (error) {
                console.error('Error al parsear JSON:', error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});

