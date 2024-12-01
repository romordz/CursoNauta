// Código original (con correcciones y optimizaciones)
const courseData = {
  "Curso de Desarrollo Web": {
      students: [
          {
              name: "Juan Pérez",
              date: "12 Feb 2024",
              progress: "90%",
              price: "$1,500.00",
              payment: "Tarjeta",
          },
          {
              name: "María López",
              date: "15 Mar 2024",
              progress: "80%",
              price: "$1,200.00",
              payment: "PayPal",
          },
      ],
      total: "$25,000.00",
  },
  "Curso de Programación en Python": {
      students: [
          {
              name: "Carlos Rodríguez",
              date: "18 Feb 2024",
              progress: "100%",
              price: "$1,800.00",
              payment: "Tarjeta",
          },
          {
              name: "Ana Morales",
              date: "22 Mar 2024",
              progress: "75%",
              price: "$1,400.00",
              payment: "PayPal",
          },
      ],
      total: "$18,000.00",
  },
};

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
                  console.log(body); // Imprime el contenido de la respuesta
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

      // Limpiar los detalles previos
      studentsBody.innerHTML = "";

      if (courseDetails && courseDetails.students) {
          // Insertar nuevos detalles
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

          // Actualizar el total del curso
          courseTotal.textContent = courseDetails.total;
      } else {
          studentsBody.innerHTML = `<tr><td colspan="5">No hay datos disponibles para este curso.</td></tr>`;
      }

      // Mostrar la sección de detalles
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

// Código adicional para la funcionalidad de editar curso
document.addEventListener("DOMContentLoaded", function () {
  function abrirEdicionCurso(idCurso) {
      fetch(`Controllers/CursoController.php?action=obtenerCursoPorId&id=${idCurso}`)
          .then(response => response.json())
          .then(data => {
              if (data) {
                  // Rellenar los datos del formulario de edición
                  document.getElementById("edit-course-id").value = data.id_curso;
                  document.getElementById("edit-course-title").value = data.titulo;
                  document.getElementById("edit-course-description").value = data.descripcion;
                  document.getElementById("edit-course-price").value = data.costo;
                  document.getElementById("edit-course-category").value = data.id_categoria;

                  // Mostrar el modal
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

              // Actualizar los datos en el frontend sin recargar
              const updatedCourse = {
                  titulo: formData.get("course_title"),
                  descripcion: formData.get("course_description"),
                  costo: formData.get("course_price"),
                  id_categoria: formData.get("course_category"),
              };

              // Actualizar los datos del curso en el DOM
              const row = document.querySelector(`[data-course-id="${updatedCourse.id_curso}"]`);
              if (row) {
                  row.querySelector(".course-title").textContent = updatedCourse.titulo;
                  row.querySelector(".course-price").textContent = `$${updatedCourse.costo}`;
              }

              // Cerrar el modal
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
