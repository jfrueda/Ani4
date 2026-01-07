<?php

session_start();

$ruta_raiz = "../..";
require_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once "$ruta_raiz/processConfig.php";
require_once "$ruta_raiz/vendor/autoload.php";

echo '
<div class="row">
    <div class="col-md-12">
        <button id="reporte_17_export" class="btn btn-success btn-sm">Exportar a csv</button>
        <button id="reporte_17_excel" class="btn btn-success btn-sm">Exportar a excel</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <br>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="reporte_17" class="table table-bordered">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Código Dependencia Antecesora de la creadora</th>
                    <th>Nombre Dependencia Antecesora de la creadora</th>
                    <th>Código Dependencia creadora</th>
                    <th>Dependencia creadora</th>
                    <th>Código Serie Documental</th>
                    <th>Nombre Serie Documental</th>
                    <th>Código Subserie Documental</th>
                    <th>Nombre Subserie Documental</th>
                    <th>Número de expediente</th>
                    <th>Fecha de creación</th>
                    <th>Nombre del expediente</th>
                    <th>Responsable del expediente</th>
                    <th>Creador del expediente</th>
                    <th>Estado</th>
                    <th>Fecha de cierre</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
';