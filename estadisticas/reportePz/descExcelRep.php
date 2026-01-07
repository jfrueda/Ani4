<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reportePazSalvo.xls");

foreach ($_GET as $key => $valor)    $$key = $valor;
foreach ($_POST as $key => $valor)    $$key = $valor;
foreach ($_SESSION as $key => $valor)    $$key = $valor;

include_once __DIR__.'/dataRepPz.php';
$obj = new DataRepPz();

$resQuery = $obj->getDetalle($codiUsa, $codiDoc, $depend, $tp_rad);
?>
<meta charset="UTF-8">
    <?php   if($resQuery["resp"] == "entrada"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "salida"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "resolucion"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "cir_int"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "cir_ext"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "autos"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "vobo"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "devueltos"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "jefe_area"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "memo_multip"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >Número de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=$valor['RADI_NUME_RADI']?></td>
                    <td><?=$valor['RADI_FECH_RADI']?></td>
                    <td><?=strtoupper($valor['USUA_NOMB'])?></td>
                    <td><?=$valor['DEPE_NOMB']?></td>
                    <td><?=$valor['RA_ASUN']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "borradores"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "memorando"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de radicado</th>
                        <th >usuario</th>
                        <th >Dependencia</th>
                        <th >Asunto</th>
                        <th >Descripcion anexos</th>
                        <th >TRD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['FECHA_RAD']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?=$valor['ASUNTO']?></td>
                    <td><?=$valor['ANEXOS_DESC']?></td>
                    <td><?=$valor['TRD']?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($resQuery["resp"] == "informados"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de radicado</th>
                        <th >Fecha de informado</th>
                        <th >usuario informado</th>
                        <th >Dependencia</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['RADICADO'])?></td>
                    <td><?=$valor['fecha_informado']?></td>
                    <td><?=$valor['USUARIO']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>


    <?php if($resQuery["resp"] == "expedientes"): ?>
        <div class="tabla-container-rad">
            <table id="dttable">
                <thead>
                    <tr>
                        <th >N&uacute;mero de expediente</th>
                        <th >Fecha de expediente</th>
                        <th >año de expediente</th>
                        <th >usuario responsable</th>
                        <th >Dependencia</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resQuery["query"] as $clave => $valor): ?>
                <tr>
                    <td><?=number_format($valor['NUMERO_EXP'])?></td>
                    <td><?=$valor['FECHA_EXP']?></td>
                    <td><?=$valor['ANIO_EXP']?></td>
                    <td><?=$valor['NOMB_RESP']?></td>
                    <td><?= htmlentities($valor['DEPENDENCIA'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>