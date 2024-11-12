document.addEventListener('DOMContentLoaded', function() {
    const topicButtons = document.querySelectorAll('.topic-btn');
    const subtopicsLists = document.querySelectorAll('.subtopics-list');

    // Alternar la visibilidad de la lista de subtemas
    topicButtons.forEach(button => {
        button.addEventListener('click', function() {
            const subtopicsList = this.nextElementSibling;
            const arrow = this.querySelector('.arrow');

            if (subtopicsList.style.display === 'block') {
                subtopicsList.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                subtopicsList.style.display = 'block';
                arrow.style.transform = 'rotate(90deg)';
            }
        });
    });

    // Cargar el video seleccionado en el reproductor de la izquierda
    const subtopicLinks = document.querySelectorAll('.subtopic-link');
    const video = document.getElementById('course-video');

    subtopicLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Evita la navegación
            video.src = this.getAttribute('href'); // Actualiza la fuente del video
            video.load(); // Carga el nuevo video
            video.play(); // Reproduce el video automáticamente
            this.previousElementSibling.checked = true; // Marca la casilla de verificación del subtema
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    
    const links = document.querySelectorAll('.subtopic-link');

    links.forEach(link => {
     
        link.addEventListener('click', function(event) {
            event.preventDefault();
       
            const linkId = this.parentElement.getAttribute('for');
      
            const checkboxId = linkId ? linkId : '';
       
            const checkbox = document.getElementById(checkboxId);

            if (checkbox) {
                checkbox.checked = true;
            }

        });
    });

});

document.querySelector('.resource-header').addEventListener('click', function() {
    const content = document.querySelector('.resource-content');
    const icon = document.querySelector('.toggle-icon');
    
    if (content.style.display === 'block') {
        content.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'block';
        icon.style.transform = 'rotate(90deg)';
    }
    });
    