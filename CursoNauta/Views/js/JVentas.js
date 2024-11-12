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
  var addCourseBtn = document.getElementById("course-des");

  addCourseBtn.addEventListener("click", function (event) {
    var isConfirmed = confirm(
      "¿Está seguro de que desea deshabilitar el curso seleccionado un curso?"
    );

    if (!isConfirmed) {
      event.preventDefault();
    }
    var isConfirmed = confirm("Curso Eliminado");
  });
});

function confirmarAccion() {
  return confirm(
    "¿Estás seguro de que deseas cambiar el estado de este curso?"
  );
}
