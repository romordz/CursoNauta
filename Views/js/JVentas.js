document.addEventListener("DOMContentLoaded", function () {
  function cambiarEstadoCurso(idCurso, nuevoEstado) {
      if (confirm(`¿Estás seguro de que deseas ${nuevoEstado ? 'habilitar' : 'deshabilitar'} este curso?`)) {
          fetch('Controllers/CursoController.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ action: 'cambiarEstadoCurso', idCurso, nuevoEstado })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert(`Curso ${nuevoEstado ? 'habilitado' : 'deshabilitado'} correctamente.`);
                  location.reload();
              } else {
                  alert('Error al cambiar el estado del curso.');
              }
          })
          .catch(error => {
              error.response.text().then(body => {
                  console.log(body);
              });
              console.error('Error:', error);
          });
      }
  }
  window.cambiarEstadoCurso = cambiarEstadoCurso;
});

document.querySelectorAll(".course-row").forEach((row) => {
  row.addEventListener("click", function () {
      const courseName = this.getAttribute("data-course");
      const courseDetails = courseData[courseName];
      const studentsBody = document.getElementById("students-body");
      const courseTotal = document.getElementById("course-total");

      studentsBody.innerHTML = "";

      if (courseDetails && courseDetails.students) {
          courseDetails.students.forEach((student) => {
              const row = `<tr>
                  <td>${student.name}</td>
                  <td>${student.date}</td>
                  <td>${student.progress}</td>
                  <td>${student.price}</td>
                  <td>${student.payment}</td>
              </tr>`;
              studentsBody.innerHTML += row;
          });
          courseTotal.textContent = courseDetails.total;
      } else {
          studentsBody.innerHTML = `<tr><td colspan="5">No hay datos disponibles para este curso.</td></tr>`;
      }
      document.getElementById("course-details").style.display = "block";
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var addCourseBtn = document.getElementById("add-course-btn");

  addCourseBtn.addEventListener("click", function (event) {
      var isConfirmed = confirm("¿Está seguro de que desea agregar un curso?");

      if (!isConfirmed) {
          event.preventDefault();
      }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  function abrirEdicionCurso(idCurso) {
      fetch(`Controllers/CursoController.php?action=obtenerCursoPorId&id=${idCurso}`)
          .then(response => response.json())
          .then(data => {
              if (data) {
                  document.getElementById("edit-course-id").value = data.id_curso;
                  document.getElementById("edit-course-title").value = data.titulo;
                  document.getElementById("edit-course-description").value = data.descripcion;
                  document.getElementById("edit-course-price").value = data.costo;
                  document.getElementById("edit-course-category").value = data.id_categoria;

                  document.getElementById("edit-course-modal").style.display = "block";
              } else {
                  alert("No se encontraron datos para este curso.");
              }
          })
          .catch(error => {
              console.error("Error al obtener datos del curso:", error);
          });
  }

  document.getElementById("edit-course-form").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch("Controllers/CursoController.php", {
          method: "POST",
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert("Curso actualizado correctamente.");

              const updatedCourse = {
                  titulo: formData.get("course_title"),
                  descripcion: formData.get("course_description"),
                  costo: formData.get("course_price"),
                  id_categoria: formData.get("course_category"),
              };

              const row = document.querySelector(`[data-course-id="${updatedCourse.id_curso}"]`);
              if (row) {
                  row.querySelector(".course-title").textContent = updatedCourse.titulo;
                  row.querySelector(".course-price").textContent = `$${updatedCourse.costo}`;
              }

              document.getElementById("edit-course-modal").style.display = "none";
          } else {
              alert("Error al actualizar el curso.");
          }
      })
      .catch(error => {
          console.error("Error al actualizar el curso:", error);
      });
  });

  window.abrirEdicionCurso = abrirEdicionCurso;
});

function confirmarAccion() {
    return confirm("¿Está seguro de que desea realizar esta acción?");
}