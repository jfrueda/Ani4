<?php
session_start();
// Retrieve session variables
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre = $_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tip3img = $_SESSION["tip3img"];

$ruta_raiz = "..";
// Include necessary files
include "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

// Establish database connection
$db = new ConnectionHandler("$ruta_raiz");

$dependenciaSeleccionada = isset($_POST['dependencia']) ? $_POST['dependencia'] : '';

// Si no se ha seleccionado "Todas", filtramos por la dependencia seleccionada
$filtDep = '';
if ($dependenciaSeleccionada != 'Todas') {
    $filtDep = " AND d.depe_codi = '$dependenciaSeleccionada' ";
}

$query = "SELECT " .
    $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . " as NOMBRE, d.DEPE_CODI
        FROM
          DEPENDENCIA d
        WHERE
          d.depe_estado = 1
        ORDER BY d.DEPE_CODI, d.DEPE_NOMB";

// Handle form submission for Create and Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $textoEncoded = htmlspecialchars($_POST['texto'], ENT_QUOTES, 'UTF-8');
    $fecha_inicial = $_POST['fecha_inicial'];
    $fecha_final = $_POST['fecha_final'];
    $htmlEncoded = htmlspecialchars($_POST['html'], ENT_QUOTES, 'UTF-8');
    $imagenbase64 = '';  // Initialize variable to hold image

    // Check if an image was uploaded
    if (isset($_FILES['imagenbase64']) && $_FILES['imagenbase64']['error'] == 0) {
        $imagenbase64 = base64_encode(file_get_contents($_FILES['imagenbase64']['tmp_name']));
    } else {
        // Use previous image if no new one was uploaded and delete checkbox is not checked
        if (isset($alertas['IMAGENBASE64']) && !isset($_POST['delete_image'])) {
            $imagenbase64 = $alertas['IMAGENBASE64'];
        }
    }

    if ($id) {
        // Update existing record
        if (!empty($imagenbase64)) {
            $sql = "UPDATE alertas SET html='$htmlEncoded' , texto='$textoEncoded', fecha_inicial='$fecha_inicial', fecha_final='$fecha_final', imagenbase64='$imagenbase64', depe_codi=$dependenciaSeleccionada WHERE id=$id";
        } else {
            if (isset($_POST['delete_image'])) {
                $sql = "UPDATE alertas SET html='$htmlEncoded', imagenbase64='', texto='$textoEncoded', fecha_inicial='$fecha_inicial', fecha_final='$fecha_final', depe_codi=$dependenciaSeleccionada WHERE id=$id";
            } else {
                $sql = "UPDATE alertas SET html='$htmlEncoded', texto='$textoEncoded', fecha_inicial='$fecha_inicial', fecha_final='$fecha_final', depe_codi=$dependenciaSeleccionada WHERE id=$id";
            }
        }
        if ($db->conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo  $db->conn->ErrorMsg();
        }
    } else {
        // Insert new record

        $sql = "INSERT INTO alertas (html,texto, fecha_inicial, fecha_final, imagenbase64, depe_codi) VALUES ('$htmlEncoded','$textoEncoded', '$fecha_inicial', '$fecha_final', '$imagenbase64', $dependenciaSeleccionada)";
        if ($db->conn->Execute($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo $db->conn->ErrorMsg();
        }
    }

    header("Location: notificacionesArgo.php");
    exit();
}

// Handle Delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM alertas WHERE id=$id";
    if ($db->conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $db->conn->ErrorMsg();
    }

    header("Location: notificacionesArgo.php");
    exit();
}

// Fetch data for display and edit
$alertas = [];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM alertas WHERE id=$id";
    $result = $db->conn->query($sql);
    if ($result) {
        $alertas = $result->fetchRow();
    } else {
        echo "Error fetching record: " . $db->conn->ErrorMsg();
    }
} else {
    $sql = "SELECT * FROM alertas";
    $result = $db->conn->query($sql);
    if ($result) {
        while ($row = $result->fetchRow()) {
            $alertas[] = $row;
        }
    } else {
        echo "Error fetching records: " . $db->conn->ErrorMsg();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones del sistema</title>
    <!-- Bootstrap CSS -->
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <script src="../include/ckeditor/ckeditor.js"></script>
    <script>
        // Initialize CKEditor when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            CKEDITOR.config.height = '400';
            CKEDITOR.replace('html');
        });
    </script>
    <style>
        /* Estilos para la imagen en grande */
        #myModal {
            display: none;
            /* Inicialmente está oculto */
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.9);
            /* Fondo oscuro */
        }

        /* Imagen dentro del modal */
        #img01 {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Botón de cerrar */
        .close {
            position: absolute;
            top: 20px;
            right: 25px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container-fluid my-5">
        <div class="card shadow border-secondary">
            <div class="card-header bg-orfeo text-white p-3">
                <h1 class="text-center">Notificaciones Super Argo</h1>
            </div>
            <div class="card-body bg-light p-4">
                <?php if (!isset($_GET['edit'])): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Crear notificación
                    </button>
                <?php endif; ?>
                <div class="collapse <?php echo isset($_GET['edit']) ? 'show' : ''; ?>" id="collapseExample">
                    <div class="container-fluid">
                        <form action="notificacionesArgo.php" method="post" class="mt-4" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo isset($alertas['ID']) ? $alertas['ID'] : ''; ?>">
                            <div class="mb-3">
                                <label for="texto" class="form-label">Texto (Resumen)</label>
                                <textarea class="form-control" id="texto" name="texto" required rows="3"><?php echo isset($alertas['TEXTO']) ? $alertas['TEXTO'] : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="html" class="form-label">Contenido HTML</label>
                                <textarea class="form-control" id="html" name="html"><?php echo isset($alertas['HTML']) ? $alertas['HTML'] : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_inicial" class="form-label">Fecha Inicial</label>
                                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" required value="<?php echo isset($alertas['FECHA_INICIAL']) ? $alertas['FECHA_INICIAL'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_final" class="form-label">Fecha Final</label>
                                <input type="date" class="form-control" id="fecha_final" name="fecha_final" required value="<?php echo isset($alertas['FECHA_FINAL']) ? $alertas['FECHA_FINAL'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="imagenbase64" class="form-label">Imagen</label>
                                <input type="file" class="form-control" id="imagenbase64" name="imagenbase64" accept="image/*">
                                <?php if (isset($alertas['IMAGENBASE64']) && $alertas['IMAGENBASE64']) : ?>
                                    <small class="form-text text-muted">Si no desea cambiar la imagen, deje este campo vacío.</small>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image">
                                        <label class="form-check-label" for="delete_image">Eliminar imagen</label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="dependencia" class="form-label">Dependencia</label>
                                <select class="form-control" id="dependencia" name="dependencia">
                                    <option value="null">Todas</option>
                                    <?php
                                    // Ejecutar la consulta para obtener las dependencias
                                    $result = $db->conn->query($query);
                                    if ($result) {
                                        while ($row = $result->fetchRow()) {
                                            $dependenciaValue = $row['DEPE_CODI'];
                                            $dependenciaText = $row["NOMBRE"];
                                            echo "<option value='$dependenciaValue' " . ($dependenciaSeleccionada == $dependenciaValue ? 'selected' : '') . ">$dependenciaText</option>";
                                        }
                                    } else {
                                        echo "Error fetching dependencies: " . $db->conn->ErrorMsg();
                                    }
                                    ?>
                                </select>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary"><?php echo isset($alertas['ID']) ? 'Actualizar' : 'Guardar'; ?></button>
                            <?php if (isset($_GET['edit'])): ?>
                                <a href="notificacionesArgo.php" class="btn btn-danger">Cancelar</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow border-secondary mt-4">
            <?php if (!isset($_GET['edit'])): ?>
                <div class="card-header bg-orfeo text-white p-3">
                    <h2 class="text-center">Alertas</h2>
                </div>
                <!-- Buscador simplificado -->
                <div class="card-body bg-light p-4">
                    <div class="row mb-6 mt-3">
                        <div class="col-md-4">
                            <input type="text" id="buscarGeneral" class="form-control" placeholder="Buscar por texto o fechas (YYYY-MM-DD)...">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="limpiarFiltro()">Limpiar</button>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light p-4">
                    <div class="table-responsive rounded border bg-white shadow-sm">
                        <table class="table table-sm table-borderless text-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Texto (resumen)</th>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha Final</th>
                                    <th>Dependencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertas as $alerta): ?>
                                    <tr>
                                        <td><?php echo $alerta['ID']; ?></td>
                                        <td><?php echo !empty($alerta['HTML']) ? 'HTML' : 'Texto'; ?></td>
                                        <td><textarea readonly class="form-control"><?php echo $alerta['TEXTO']; ?></textarea></td>
                                        <td><?php echo $alerta['FECHA_INICIAL']; ?></td>
                                        <td><?php echo $alerta['FECHA_FINAL']; ?></td>
                                        <td><?php echo isset($alerta['DEPE_CODI']) ? $alerta['DEPE_CODI'] : 'Todas'; ?></td>
                                        <td>
                                            <a href="notificacionesArgo.php?edit=<?php echo $alerta['ID']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="notificacionesArgo.php?delete=<?php echo $alerta['ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Seguro deseas borrar la alerta?');">Borrar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Modal para la imagen grande -->
            <div id="myModal">
                <span class="close" onclick="closeModal()">&times;</span>
                <img class="modal-content" id="img01">
            </div>
        </div>
    </div>
    <script>
        // Función para abrir el modal con la imagen seleccionada
        function openModal(base64Image) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");
            modal.style.display = "block";
            modalImg.src = "data:image;base64," + base64Image;
        }

        // Función para cerrar el modal
        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
        const MAXIMO_TAMANIO_BYTES = 5000000;
        $('body').delegate('input[type=file]', "change", function() {
            if (this.files.length <= 0) return;
            const archivo = this.files[0];
            console.log(this.files)
            let letter = archivo.name.charAt(0);
            if (letter == '.') {
                alert(`El nombre del archivo no puede iniciar con un .`);
                this.value = null;
            }
            if (archivo.size > MAXIMO_TAMANIO_BYTES) {
                const tamanioEnMb = MAXIMO_TAMANIO_BYTES / 1000000;
                alert(`El tamaño máximo es ${tamanioEnMb} MB`);
                this.value = null;
            }
        });

        function filtrarTabla() {
            const busqueda = document.getElementById('buscarGeneral').value.toLowerCase();
            const filas = document.querySelectorAll('tbody tr');

            filas.forEach(fila => {
                const texto = fila.querySelector('textarea').value.toLowerCase();
                const fechaInicial = fila.cells[3].textContent;
                const fechaFinal = fila.cells[4].textContent;
                const dependencia = fila.cells[5].textContent.toLowerCase();
                const tipo = fila.cells[1].textContent.toLowerCase();

                // Buscar en todos los campos
                const coincide = texto.includes(busqueda) ||
                    fechaInicial.includes(busqueda) ||
                    fechaFinal.includes(busqueda) ||
                    dependencia.includes(busqueda) ||
                    tipo.includes(busqueda);

                fila.style.display = coincide ? '' : 'none';
            });
        }

        function limpiarFiltro() {
            document.getElementById('buscarGeneral').value = '';
            filtrarTabla();
        }

        // Agregar evento de escucha para el campo de búsqueda
        document.getElementById('buscarGeneral').addEventListener('input', filtrarTabla);
    </script>
</body>

</html>

<?php
$db->conn->close();
?>