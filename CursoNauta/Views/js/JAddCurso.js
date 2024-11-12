
function generateLevelFields() {
    const levels = document.getElementById("levels").value;
    const levelContainer = document.getElementById("level-container");
    levelContainer.innerHTML = '';

    for (let i = 1; i <= levels; i++) {
        const levelDiv = document.createElement("div");
        levelDiv.style.marginBottom = "15px";
        levelDiv.style.padding = "15px";
        levelDiv.style.border = "1px solid #d0d0d0";
        levelDiv.style.borderRadius = "5px";
        levelDiv.style.backgroundColor = "#f9f9f9";

        const levelTitle = document.createElement("h3");
        levelTitle.style.fontWeight = "bold";
        levelTitle.style.color = "#6a5b8f";
        levelTitle.innerText = `Nivel ${i}`;
        levelDiv.appendChild(levelTitle);

        const titleLabel = document.createElement("label");
        titleLabel.setAttribute("for", `level-title-${i}`);
        titleLabel.innerText = `Título del Nivel ${i}:`;
        levelDiv.appendChild(titleLabel);

        const titleInput = document.createElement("input");
        titleInput.type = "text";
        titleInput.name = `level_title_${i}`;
        titleInput.id = `level-title-${i}`;
        titleInput.style.width = "100%";
        levelDiv.appendChild(titleInput);

        const videoLabel = document.createElement("label");
        videoLabel.setAttribute("for", `level-video-${i}`);
        videoLabel.innerText = "Video del Nivel:";
        levelDiv.appendChild(videoLabel);

        const videoInput = document.createElement("input");
        videoInput.type = "file";
        videoInput.name = `level_video_${i}`;
        videoInput.id = `level-video-${i}`;
        levelDiv.appendChild(videoInput);

        const contentLabel = document.createElement("label");
        contentLabel.setAttribute("for", `level-content-${i}`);
        contentLabel.innerText = "Contenido del Nivel:";
        levelDiv.appendChild(contentLabel);

        const contentInput = document.createElement("textarea");
        contentInput.name = `level_content_${i}`;
        contentInput.id = `level-content-${i}`;
        contentInput.style.width = "100%";
        contentInput.style.height = "100px";
        levelDiv.appendChild(contentInput);

        // Agregar campo para archivos adicionales
        const attachmentsLabel = document.createElement("label");
        attachmentsLabel.setAttribute("for", `level-attachments-${i}`);
        attachmentsLabel.innerText = "Archivo adicional (PDF, imagen, etc.):";
        levelDiv.appendChild(attachmentsLabel);

        const attachmentsInput = document.createElement("input");
        attachmentsInput.type = "file";
        attachmentsInput.name = `level_attachments_${i}`; // Cambiado para solo un archivo
        attachmentsInput.id = `level-attachments-${i}`;
        attachmentsInput.style.width = "100%";
        levelDiv.appendChild(attachmentsInput);
        
        levelContainer.appendChild(levelDiv);
    }
}

document.getElementById('course-form').addEventListener('submit', function (event) {
    const courseImage = document.getElementById('course-image').files.length;
    const title = document.getElementById('course-title').value.trim();
    const description = document.getElementById('course-description').value.trim();
    const levels = document.getElementById('levels').value;
    const coursePrice = document.getElementById('course-price').value;
    const category = document.getElementById('course-category').value;

    let errorMessages = [];

    if (category === '') errorMessages.push('Debes seleccionar una categoría para el curso.');
    if (courseImage === 0) errorMessages.push('Debes cargar una imagen del curso.');
    if (title === '') errorMessages.push('El título del curso no puede estar vacío.');
    if (description === '') errorMessages.push('La descripción del curso no puede estar vacía.');
    if (levels < 1) errorMessages.push('La cantidad de niveles debe ser al menos 1.');
    if (coursePrice <= 0) errorMessages.push('El costo del curso debe ser un valor positivo.');

    if (errorMessages.length > 0) {
        event.preventDefault();
        errorMessages.forEach(message => alert(message));
    }
});
