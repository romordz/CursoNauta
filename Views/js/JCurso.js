document.addEventListener("DOMContentLoaded", function () {
  const topicButtons = document.querySelectorAll(".topic-btn");

  topicButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const subtopicsList = this.nextElementSibling;
      const arrow = this.querySelector(".arrow");

      if (subtopicsList.style.display === "block") {
        subtopicsList.style.display = "none";
        arrow.style.transform = "rotate(0deg)";
      } else {
        subtopicsList.style.display = "block";
        arrow.style.transform = "rotate(90deg)";
      }
    });
  });

  const subtopicLinks = document.querySelectorAll(".subtopic-link");
  const video = document.getElementById("course-video");

  subtopicLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      video.src = this.getAttribute("href");
      video.load();
      video.play();
      this.previousElementSibling.checked = true;

      const idNivel = this.getAttribute("data-id-nivel");
      const idCurso = video.getAttribute("data-id-curso");

      fetch("Controllers/ProgresoController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=marcarNivel&id_nivel=${idNivel}&id_curso=${idCurso}`
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const progresoTexto = document.querySelector(".video-section h4");
            const progresoFill = document.querySelector(".progress-fill");
            if (progresoTexto) progresoTexto.textContent = `Tu progreso: ${Math.round(data.progreso)}%`;
            if (progresoFill) progresoFill.style.width = `${Math.round(data.progreso)}%`;
          }
        })
        .catch(error => console.error("Error al actualizar progreso:", error));
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const resourceHeader = document.querySelector(".resource-header");
  if (resourceHeader) {
    resourceHeader.addEventListener("click", function () {
      const content = document.querySelector(".resource-content");
      const icon = document.querySelector(".toggle-icon");

      if (content.style.display === "block") {
        content.style.display = "none";
        icon.style.transform = "rotate(0deg)";
      } else {
        content.style.display = "block";
        icon.style.transform = "rotate(90deg)";
      }
    });
  }
});