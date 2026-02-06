<?php

if (!$ruta_raiz) {
    $ruta_raiz = "..";
}

session_start();

if (!$_SESSION['dependencia'] || !$_SESSION["usua_admin_sistema"] >= 1) {
    header("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

$nivel = [
    0 => 'Público',
    1 => 'Reservado',
    2 => 'Clasificado',
];

if (isset($_POST['radicados'])) {
    header('Content-Type: application/json');

    $numeros = explode(',', $_POST['radicados']);
    $numeros = array_map('trim', $numeros);
    $numeros = array_filter($numeros);

    $error = null;
    foreach ($numeros as $numero) {
        if (!ctype_digit($numero)) {
            $error = "número: [$numero]";
            break;
        }
    }

    if ($error) {
        echo json_encode(['error' => $error]);
        exit;
    }

    $radicados = implode(',', $numeros);

    require_once($ruta_raiz . "/include/db/ConnectionHandler.php");
    require_once($ruta_raiz . "/processConfig.php");
    require_once("$ruta_raiz/include/tx/Historico.php");
    $db = new ConnectionHandler($ruta_raiz);
    $hist = new Historico($db);

    $TX_COMENTARIO = "Cambio seguridad radicado a {$nivel[$_POST['estado']]}";
    $TX_CODIGO = 54;

    $db->conn->StartTrans();
    $query = "update radicado set sgd_spub_codigo = {$_POST['estado']} where radi_nume_radi in ($radicados);";
    $hist->insertarHistorico($numeros, $_SESSION['dependencia'], $_SESSION["codusuario"], $_SESSION['dependencia'], $_SESSION['codusuario'], $TX_COMENTARIO, $TX_CODIGO);
    $rs = $db->conn->Execute($query);
    $db->conn->CompleteTrans();

    if ($rs) {
        $n = $db->conn->Affected_Rows();
        echo json_encode(['data' => $n]);
    } else {
        $error = $db->conn->ErrorMsg();
        echo json_encode(['error' => $error]);
    }
    exit;
}
?>
<html>

<head>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-fluid mb-4">
        <div class="row justify-content-center">
            <div class="col-12 ">
                <div class="card shadow border-0 overflow-hidden">
                    <div class="card-header bg-orfeo bg-gradient text-white py-3">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="fa fa-shield me-2 "></i> Seguridad radicado
                        </h5>
                    </div>

                    <div class="card-body bg-light p-0">
                        <form id="fmGeneral">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light-subtle h-100">
                                        <label for="estado" class="form-label fw-semibold text-secondary small mb-2">
                                            Nivel de seguridad*
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted border-end-0">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                            <select class="form-select border-start-0 ps-0 shadow-none" id="estado" name="estado" required>
                                                <option value="-1" selected disabled>Seleccione una opción</option>
                                                <?php foreach ($nivel as $k => $v) { ?>
                                                    <option value="<?= $k ?>"><?= $v ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light-subtle h-100">
                                        <label for="radicados" class="form-label fw-semibold text-secondary small mb-2">
                                            Radicados*
                                        </label>
                                        <textarea maxlength="2000" class="form-control shadow-none bg-light-subtle" id="radicados" name="radicados" rows="3" placeholder="Escriba los números de radicado aquí..." required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="my-4 border-top border-light-subtle"></div>

                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary px-4 fw-medium border-2" id="btBorrarForm">
                                    <i class="fa fa-eraser me-1"></i> Borrar formulario
                                </button>
                                <button type="button" class="btn btn-primary px-5 fw-bold shadow" id="btActualizar">
                                    <i class="fa fa-refresh me-1"></i> Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white py-2">
                        <small class="text-muted italic px-2">
                            <i class="fa fa-info-circle me-1 text-primary"></i> Los campos marcados con (*) son obligatorios.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert d-none" role="alert" id="dvForm"></div>

    <script type="text/javascript">
        function alerta(mensaje, tipo = 'info') {
            const $alerta = $('#dvForm');
            $alerta.attr('class', `alert alert-${tipo}`);
            $alerta.text(mensaje);
            if ($alerta.is(':visible')) {
                $alerta.fadeOut(200, function() {
                    $(this).fadeIn(200);
                });
            } else {
                $alerta.show();
            }
        }

        $("#btActualizar").click(function() {
            if ($('#estado').val() == -1 || !$('#radicados').val()) {
                alerta('Completar campos', 'warning');
                return;
            }

            $.ajax({
                type: "POST",
                data: $("#fmGeneral").serialize(),
                success: function(result) {
                    if (result.error) {
                        alerta("Error: " + result.error, 'danger');
                    } else {
                        alerta(result.data + " registros actualizados", result.data == 0 ? 'danger' : 'success');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest, textStatus, errorThrown);
                    alerta("Ocurrió un error", 'danger');
                }
            });
        });

        $("#btBorrarForm").click(function() {
            $('#fmGeneral')[0].reset();
            $("#dvForm").hide();
        });
    </script>
</body>

</html>