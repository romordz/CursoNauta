// Función para mostrar el contenido de la sección seleccionada
function showContent(contentId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.right-pane .section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Remover la clase 'active' de todos los botones
    const buttons = document.querySelectorAll('.left-pane button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });

    // Mostrar la sección seleccionada
    const selectedSection = document.getElementById(contentId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }

    // Marcar el botón seleccionado como 'active'
    const activeButton = document.querySelector(`.left-pane button[onclick="showContent('${contentId}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}

// Mostrar la sección de 'usuarios' por defecto
document.addEventListener('DOMContentLoaded', () => {
    showContent('usuarios');
});

function showContent(contentId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.right-pane .section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Remover la clase 'active' de todos los botones
    const buttons = document.querySelectorAll('.left-pane button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });

    // Mostrar la sección seleccionada
    const selectedSection = document.getElementById(contentId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }

    // Marcar el botón seleccionado como 'active'
    const activeButton = document.querySelector(`.left-pane button[onclick="showContent('${contentId}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }

    // Ocultar formulario de agregar categoría si se cambia de sección
    if (contentId !== 'categorias') {
        toggleCategoryForm(false);
    }
}

function toggleCategoryForm(show) {
    const form = document.getElementById('add-category-form');
    form.style.display = show ? 'block' : 'none';
}

function toggleEditCategoryForm(show) {
    const form = document.getElementById('edit-category-form');
    form.style.display = show ? 'block' : 'none';
}

function editarCategoria(id, title, description) {
    // Establece los valores en el formulario de edición
    document.getElementById('edit-id_categoria').value = id;
    document.getElementById('edit-category-title').value = title;
    document.getElementById('edit-category-description').value = description;

    // Muestra el formulario de edición
    toggleEditCategoryForm(true);
}

function confirmarAccion() {
    return confirm("¿Está seguro de que desea realizar esta acción?");
}

// Ocultar formularios cuando se cambia de sección
document.addEventListener('DOMContentLoaded', () => {
    showContent('usuarios');
});


//REPORTES
function toggleReport(userType) {
    document.getElementById('instructor-report').style.display = 'none';
    document.getElementById('student-report').style.display = 'none';

    if (userType === 'instructor') {
        document.getElementById('instructor-report').style.display = 'block';
    } else if (userType === 'estudiante') {
        document.getElementById('student-report').style.display = 'block';
    }
}

function generateReport() {
    const userType = document.getElementById('user-type').value;
    toggleReport(userType);
}

document.addEventListener('DOMContentLoaded', () => {
    // Oculta ambos reportes al cargar la página
    document.getElementById('instructor-report').style.display = 'none';
    document.getElementById('student-report').style.display = 'none';
});



