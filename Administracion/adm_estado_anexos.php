<?php

if (!$ruta_raiz)
    $ruta_raiz = "..";

session_start();

if (!$_SESSION['dependencia'] || !$_SESSION["usua_admin_sistema"]>=1) {
    header ("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

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

    require_once($ruta_raiz."/include/db/ConnectionHandler.php");
    require_once($ruta_raiz."/processConfig.php");
    require_once("$ruta_raiz/include/tx/Historico.php");
    $db = new ConnectionHandler($ruta_raiz);
    $hist = new Historico($db);

    $TX_COMENTARIO = "Cambio estado anexo ({$_POST['estado']})";
    $TX_CODIGO = 92;

    $db->conn->StartTrans();
    $query = "update anexos set anex_estado = {$_POST['estado']} where radi_nume_salida in ($radicados);";
    $hist->insertarHistorico($numeros, $_SESSION['dependencia'] , $_SESSION["codusuario"], $_SESSION['dependencia'], $_SESSION['codusuario'], $TX_COMENTARIO, $TX_CODIGO);
    $rs = $db->conn->Execute($query);
    $db->conn->CompleteTrans();

    if ($rs) {
        $n = $db->conn->Affected_Rows();
        echo json_encode(['data' => $n]);
    }
    else {
        $error = $db->conn->ErrorMsg();
        echo json_encode(['error' => $error]);
    }
    exit;
}
?>
<html>
    <head>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

    </head>
    <body>
        <h1 class="mt-2">Estado anexos</h1>
        <div class="tab-content borderNav" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                </br>
                <form id = "fmGeneral">
                    <div class="container">

                        <div class="row">
                            <div class="col">

                                <div class="row">
                                    <label for="estado" class="col-sm-4 col-form-label form-control-sm">Estado*</label>
                                    <div class="col-sm-8">
                                        <select class="form-select form-select-sm" id="estado" name="estado" required>
                                            <option value="0" selected>Seleccione una opción</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col">

                                <div class="row">
                                    <label for="radicados" class="col-sm-4 col-form-label form-control-sm">Radicados*</label>
                                    <div class="col-sm-8">
                                        <textarea maxlength="2000" class="form-control" id="radicados" name="radicados" rows="3" required></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    </br>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btBorrarForm">Borrar formulario</button>
                        <button type="button" class="btn btn-primary" id="btActualizar">Actualizar</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="alert d-none" role="alert" id="dvForm"></div>

        <script type="text/javascript">
        function alerta(mensaje, tipo='info') {
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

        $( "#btActualizar" ).click(function() {
            if ($('#estado').val()==0 || !$('#radicados').val()) {
                alerta('Completar campos', 'warning');
                return;
            }

            $.ajax({
                type: "POST",
                data: $("#fmGeneral").serialize(),
                success: function(result){
                    if (result.error) {
                        alerta("Error: "+result.error, 'danger');
                    }
                    else {
                        alerta(result.data+" registros actualizados", result.data==0?'danger':'success');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alerta("Ocurrió un error", 'danger');
                }
            });
        });

        $( "#btBorrarForm" ).click(function() {
            $('#fmGeneral')[0].reset();
            $("#dvForm").hide();
        });
        </script>

    </body>
</html>
