<?php include 'Views\Parciales\Head.php'; ?>

<link rel="stylesheet" href="Views/css/SAdmi.css">

<?php include 'Views\Parciales\Nav.php'; ?>


<!------------ SECCION DE OBTENER CATEGORIAS PARA MOSTRAR --------------->

<?php
$userId = $_SESSION['user_id'];

require_once 'Controllers\CategoriaController.php';

$categorias = ($userId) ? $controller->mostrarCategorias($userId) : [];
?>

<!------------ SECCION DE OBTENER USUARIOS --------------->
<?php
require_once 'Controllers/UsuarioController.php';

$usuarioController = new UsuarioController();
$usuarios = $usuarioController->mostrarUsuarios();
$usuarioController->cambiarEstadoUsuario();
?>

<!------------ SECCION DE CURSOS --------------->
<?php
require_once 'Controllers/CursoController.php';

$cursoController = new CursoController();
$cursos = $cursoController->mostrarCursos();
$cursoController->cambiarEstadoCurso();
?>

<!------------ SECCION DE REPORTES --------------->
<?php
require_once 'Controllers/ReportesController.php';

$reportesController = new ReportesController();
$reporteInstructores = $reportesController->mostrarReporteInstructores();
$reporteEstudiantes = $reportesController->mostrarReporteEstudiantes();
?>
<!--------------------------- SECCION DE HTML --------------------------------->

<div class="admin-container">
    <div class="main-content">
        <div class="left-pane">
            <button class="active" onclick="showContent('usuarios')">Usuarios</button>
            <button onclick="showContent('cursos')">Cursos</button>
            <button onclick="showContent('categorias')">Categorías</button>
            <button onclick="showContent('reportes')">Reportes</button>
        </div>

        <!-- Contenido Principal -->
        <div class="right-pane">
            <!-- Contenido para Lista de Usuarios -->
            <div class="section" id="usuarios">
                <h1>Lista de Usuarios</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Fecha de Ingreso</th>
                            <th>Rol</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['fecha_registro']); ?></td>
                                    <td><?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                                    <td>
                                        <?php if ($usuario['idUsuario'] == $userId): ?>
                                            <span>No puedes hacer esto</span>
                                        <?php else: ?>
                                            <form method="POST" onsubmit="return confirmarAccion()">
                                                <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">
                                                <input type="hidden" name="nuevoEstado" value="<?php echo $usuario['activo'] ? 0 : 1; ?>">
                                                <button type="submit">
                                                    <?php echo $usuario['activo'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Contenido para Lista de Cursos -->
            <div class="section" id="cursos" style="display: none;">
                <h1>Lista de Cursos</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Creador</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $curso): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($curso['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($curso['descripcion']); ?></td>
                                    <td><?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                                    <td><?php echo htmlspecialchars($curso['instructor_nombre']); ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirmarAccion()">
                                            <input type="hidden" name="idCurso" value="<?php echo $curso['id_curso']; ?>">
                                            <input type="hidden" name="nuevoEstado" value="<?php echo $curso['activo'] ? 0 : 1; ?>">
                                            <input type="hidden" name="action" value="cambiarEstadoCurso">
                                            <button type="submit">
                                                <?php echo $curso['activo'] ? 'Deshabilitar' : 'Habilitar'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay cursos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Contenido para Lista de Categorías -->
            <div class="section" id="categorias" style="display: none;">
                <h1>Lista de Categorías</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></td>
                                    <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                                    <td><?php echo $categoria['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                                    <td>
                                        <!-- Botón de Editar -->
                                        <button onclick="editarCategoria(
                                <?php echo $categoria['id_categoria']; ?>, 
                                '<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>', 
                                '<?php echo htmlspecialchars($categoria['descripcion']); ?>'
                            )">Editar</button>

                                        <!-- Botón de Activar/Desactivar -->
                                        <form method="POST" style="display:inline" onsubmit="return confirmarAccion()">
                                            <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
                                            <input type="hidden" name="nuevoEstado" value="<?php echo $categoria['activo'] ? 0 : 1; ?>">
                                            <input type="hidden" name="action" value="toggle">
                                            <button type="submit">
                                                <?php echo $categoria['activo'] ? 'Desactivar' : 'Activar'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No tienes categorías registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Formulario para agregar nueva categoría -->
                <button type="button" onclick="toggleCategoryForm(true)">Agregar Nueva Categoría</button>

                <div id="add-category-form" style="display: none;">
                    <h2>Agregar Nueva Categoría</h2>
                    <form id="category-form" method="post" action="index.php?page=CC">
                        <input type="hidden" id="id_creador" name="id_creador" value="<?php echo $userId; ?>">
                        <input type="hidden" name="action" value="add">

                        <label for="category-title">Título:</label>
                        <input type="text" id="category-title" name="nombre_categoria">

                        <label for="category-description">Descripción:</label>
                        <textarea id="category-description" name="descripcion" rows="4"></textarea>

                        <div class="button-group">
                            <button type="submit">Guardar Categoría</button>
                            <button type="button" onclick="toggleCategoryForm(false)">Cancelar</button>
                        </div>
                    </form>
                </div>

                <!-- Formulario para editar categoría -->
                <div id="edit-category-form" style="display: none;">
                    <h2>Editar Categoría</h2>
                    <form id="category-edit-form" method="post" action="index.php?page=CC">
                        <input type="hidden" name="id_categoria" id="edit-id_categoria">
                        <input type="hidden" name="action" value="edit">

                        <label for="edit-category-title">Título:</label>
                        <input type="text" id="edit-category-title" name="nombre_categoria">

                        <label for="edit-category-description">Descripción:</label>
                        <textarea id="edit-category-description" name="descripcion" rows="4"></textarea>

                        <div class="button-group">
                            <button type="submit">Guardar Cambios</button>
                            <button type="button" onclick="toggleEditCategoryForm(false)">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Sección de Reportes -->
            <div class="section" id="reportes" style="display: none;">
                <h1>Obtener Reportes</h1>
                <label for="user-type">Tipo de Usuario:</label>
                <select id="user-type" name="user-type">
                    <option value="instructor">Instructor</option>
                    <option value="estudiante">Estudiante</option>
                </select>
                <button onclick="generateReport()">Generar Reporte</button>

                <!-- Reporte de Instructores -->
                <div class="course-details" id="instructor-report" style="display: none;">
                    <h2>Reporte de Instructores</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Fecha de Ingreso</th>
                                <th>Cantidad de Cursos Ofrecidos</th>
                                <th>Total de Ganancias</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reporteInstructores as $instructor): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($instructor['id_instructor']); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['nombre_instructor']); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['fecha_ingreso']); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['cantidad_cursos_ofrecidos']); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['total_ganancias']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Reporte de Estudiantes -->
                <div class="course-details" id="student-report" style="display: none;">
                    <h2>Reporte de Estudiantes</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Fecha de Ingreso</th>
                                <th>Cantidad de Cursos Inscritos</th>
                                <th>% de Cursos Terminados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reporteEstudiantes as $estudiante): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($estudiante['id_estudiante']); ?></td>
                                    <td><?php echo htmlspecialchars($estudiante['nombre_estudiante']); ?></td>
                                    <td><?php echo htmlspecialchars($estudiante['fecha_ingreso']); ?></td>
                                    <td><?php echo htmlspecialchars($estudiante['cantidad_cursos_inscritos']); ?></td>
                                    <td><?php echo htmlspecialchars($estudiante['porcentaje_cursos_terminados']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="Views\js\JAdmi.js"> </script>

<?php include 'Views\Parciales\Footer.php'; ?>