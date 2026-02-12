<?php

/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

 SIIM2 Models are the data definition of SIIM2 Information System
 Copyright (C) 2013 Infometrika Ltda.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

$ruta_raiz = ".";
if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
}

foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}

$direcTor = "$ruta_raiz/bodega/plantillas/";
$archivo1 = $direcTor . "combiSencilla.xml";
$archivo2 = $direcTor . "combiMasiva.xml";
$archivo3 = $direcTor . "plantillas.xml";

$doc    = new DOMDocument();

if (file_exists($archivo3)) {
    $doc->load($archivo3);
    $campos     = $doc->getElementsByTagName("campo");
    foreach ($campos as $campo) {
        $campTemp1 = $campo->getElementsByTagName("nombre");
        $campTemp2 = $campo->getElementsByTagName("ruta");
        $temp1     = $campTemp1->item(0)->nodeValue;
        $temp2     = $campTemp2->item(0)->nodeValue;

        $plantill  .= "&nbsp; &nbsp;<a href='" . $direcTor . $temp2 . "'>" . $temp1 . "</a><br/></br>";
    }
} else {
    $msg = "
        <tr align='left'>
            <td>
                No se abrio el archivo {$archivo3} generado desde la administracion de plantillas
            </td>
        </tr>
    ";
}

if (file_exists($archivo2)) {
    $doc->load($archivo2);
    $campos     = $doc->getElementsByTagName("campo");
    foreach ($campos as $campo) {
        $campTemp = $campo->getElementsByTagName("nombre");
        $valor    = $campTemp->item(0)->nodeValue;
        $nombMa   .= empty($nombSe) ? " &nbsp; &nbsp; $valor" : "&nbsp; &nbsp;  &nbsp; &nbsp; $valor ";
    }
} else {
    $msg = "
        <tr align='left'>
            <td>
                No se abrio el archivo {$archivo2} generado desde la administracion de plantillas
            </td>
        </tr>
    ";
}

if (file_exists($archivo1)) {
    $doc->load($archivo1);
    $campos     = $doc->getElementsByTagName("campo");
    foreach ($campos as $campo) {
        $campTemp = $campo->getElementsByTagName("nombre");
        $valor    = $campTemp->item(0)->nodeValue;
        $nombSe   .= empty($nombSe) ? "&nbsp; &nbsp;$valor" : " &nbsp; &nbsp; $valor ";
    }
} else {
    $msg = "
        <tr align='left'>
            <td>
                No se abrio el archivo {$archivo1} generado desde la administracion de plantillas
            </td>
        </tr>
    ";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <title>Plantillas A Usar en Orfeo</title>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body>
    <div class="col-12 ">
        <section id="widget-grid">
            <div class="row">
                <article class="col-12">
                    <div class="card shadow border-0 overflow-hidden" id="wid-id-1">
                        <div class="card-header bg-orfeo bg-gradient text-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fa fa-tags me-2"></i> Campos de masiva combinación y plantillas
                            </h5>
                        </div>
                        <div class="card-body bg-light p-4">
                            <?php if (!empty($msg)) echo "<div class='mb-3'>$msg</div>"; ?>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-secondary text-white py-2">
                                            <small class="fw-bold">CONFIGURACIÓN DE CAMPOS</small>
                                        </div>
                                        <div class="card-body bg-white">
                                            <div class="mb-4">
                                                <h6 class="text-primary border-bottom pb-2">
                                                    <i class="fa fa-list-alt me-2"></i>Combinación Masiva
                                                </h6>
                                                <div class="p-2 bg-light rounded font-monospace small">
                                                    <?= $nombSe ?>
                                                </div>
                                            </div>

                                            <div>
                                                <h6 class="text-primary border-bottom pb-2">
                                                    <i class="fa fa-share-alt me-2"></i>Combinación Sencilla
                                                </h6>
                                                <div class="p-2 bg-light rounded font-monospace small">
                                                    <?= $nombMa ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 shadow-sm border-start border-warning border-4">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3"><i class="fa fa-barcode me-2"></i>Fuentes de Código de Barras</h6>
                                            <p class="text-muted small">
                                                Para una visualización correcta en el PC o dispositivo móvil, siga estas instrucciones:
                                            </p>
                                            <ul class="small text-secondary mb-4">
                                                <li>Descargue e instale directamente con el Administrador de Fuentes.</li>
                                                <li>O copie los archivos en el directorio del sistema: <code>\fonts</code></li>
                                            </ul>

                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="include/fuentes/free3of9.ttf" class="btn btn-sm btn-outline-dark shadow-none">
                                                    <i class="fa fa-download me-1"></i> free3of9.ttf
                                                </a>
                                                <a href="include/fuentes/fre30f9x.ttf" class="btn btn-sm btn-outline-dark shadow-none">
                                                    <i class="fa fa-download me-1"></i> free3of9x.ttf
                                                </a>
                                                <a href="include/fuentes/free3of9.txt" class="btn btn-sm btn-link text-decoration-none text-muted small">
                                                    <i class="fa fa-file-text-o me-1"></i> Ver Licencia
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-primary text-white py-2">
                                            <h6 class="mb-0 small fw-bold text-center uppercase tracking-wider">Listado de Plantillas Disponibles</h6>
                                        </div>
                                        <div class="card-body bg-white text-center">
                                            <div class="table-responsive py-2">
                                                <?= $plantill ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</body>

</html>