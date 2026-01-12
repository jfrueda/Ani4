<?php

if (!isset($tipoMed)) {
    $tipoMed = "";
}
if (!isset($buscar_por_cuentai)) {
    $buscar_por_cuentai = "";
}
if (!isset($buscar_por_radicado)) {
    $buscar_por_radicado = "";
}
if (!isset($buscar_por_asunto)) {
    $buscar_por_asunto = "";
}
if (!isset($buscar_por_correo)) {
    $buscar_por_correo = "";
}
if (!isset($buscar_por_exp)) {
    $buscar_por_exp = "";
}
if (!isset($buscar_por_doc)) {
    $buscar_por_doc = "";
}
if (!isset($buscar_por_nombres)) {
    $buscar_por_nombres = "";
}
if (!isset($pnomb)) {
    $pnomb = "";
}

?>
<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
<script>
    function solonumeros() {
        jh = document.getElementById('buscar_por_radicado').value;
        if (jh) {
            var1 = parseInt(jh);
            if (var1 != jh) {
                alert("Atencion: El numero de Radicado debe ser de solo Numeros. ");
                return false;
            } else {
                document.getElementById('buscar_por_radicado').value = jh;
                numCaracteres = document.getElementById('buscar_por_radicado').value.length;
                <?php
                $ln = $_SESSION["digitosDependencia"];
                $lnr = 11 + $ln;
                ?>

                if (numCaracteres >= 13) {
                    document.formulario.submit();
                } else {
                    alert("Atencion: El numero de Caracteres del radicado es de <?php echo $lnr; ?>. (Digito :" + numCaracteres + ")");
                }
            }
        } else {
            document.formulario.submit();
        }
    }
</script>

<div class="row g-3">
    <div class="col-md-4">
        <label for="cuentai" class="form-label">
            Referencia (Cuenta Interna, Número de Oficio)
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba para buscar por Referencia (Cuenta Interna, Número de Oficio)"
            name="buscar_por_cuentai"
            type="text"
            class="form-control ecajasfecha"
            id="cuentai"
            value="<?= $buscar_por_cuentai ?>">
    </div>

    <div class="col-md-4">
        <label for="buscar_por_radicado" class="form-label">
            Referenciado
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba número de radicado a referenciar"
            name="buscar_por_radicado"
            type="text"
            class="form-control ecajasfecha"
            id="buscar_por_radicado"
            value="<?= $buscar_por_radicado ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">
            Expediente
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba para buscar por Expediente"
            name="buscar_por_exp"
            type="text"
            class="form-control ecajasfecha"
            id="buscar_por"
            value="<?= $buscar_por_exp ?>">
    </div>

    <div class="col-md-8">
        <label for="asunto" class="form-label">
            Asunto
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba para buscar por Asunto"
            name="buscar_por_asunto"
            type="text"
            class="form-control ecajasfecha"
            id="asunto"
            value="<?= $buscar_por_asunto ?>">
    </div>

    <div class="col-md-4">
        <label for="correo" class="form-label">
            Correo
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba para buscar por Correo electrónico"
            name="buscar_por_correo"
            type="text"
            class="form-control ecajasfecha"
            id="correo"
            value="<?= $buscar_por_correo ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">
            Identificación (T.I., C.C., NIT)
        </label>
        <input
            data-toggle="tooltip"
            title="Escriba para buscar por número de documento"
            name="buscar_por_doc"
            type="text"
            class="form-control ecajasfecha"
            id="cuentai"
            value="<?= $buscar_por_doc ?>">
    </div>

    <? if ($ent != 22) { ?>
        <div class="col-md-4">
            <label class="form-label">
                Nombres
            </label>
            <input
                name="buscar_por_nombres"
                data-toggle="tooltip"
                title="Escriba para buscar por Nombres"
                type="text"
                class="form-control ecajasfecha"
                id="buscar_por_nombres"
                value="<?= $buscar_por_nombres ?>">
        </div>
    <? } ?>

    <div class="col-md-4">
        <label class="form-label">
            Rango de Fechas de Radicación
        </label>

        <div class="row g-2">
            <div class="col-6">
                <input
                    type="text"
                    data-toggle="tooltip"
                    title="Fecha inicial: AAAA/MM/DD"
                    name="fecha_ini"
                    id="startdate"
                    class="form-control"
                    placeholder="Fecha inicial"
                    value="<?= $fecha_ini ?>">
            </div>

            <div class="col-6">
                <input
                    type="text"
                    data-toggle="tooltip"
                    title="Fecha final: AAAA/MM/DD"
                    name="fecha_fin"
                    id="finishdate"
                    class="form-control"
                    placeholder="Fecha final"
                    value="<?= $fecha_fin ?>">
            </div>
        </div>
    </div>

    <?php if (isset($mostrar_dep) && $mostrar_dep == "ddd") { ?>
        <div class="col-md-4">
            <label class="form-label">
                Dependencia de Radicación
            </label>
            <input
                name="buscar_por_dep_rad"
                type="text"
                class="form-control"
                id="cuentai"
                value="<?= $buscar_por_dep_rad ?>">
        </div>
    <? } ?>
</div>

<footer class="d-flex justify-content-between align-items-center mt-4">
    <div>
        <input
            type="submit"
            name="Submit"
            onClick="solonumeros();"
            title="Diligencie los campos para realizar una búsqueda previa"
            value="Buscar"
            onSelect="solonumeros();"
            class="btn btn-primary px-4">

        <a
            class="btn btn-success ms-2 px-4"
            title="Seleccione Nuevo si su comunicación no se relaciona con otra comunicación existente"
            href="./NEW.php?<?= session_name() . "=" . trim(session_id()) ?>&dependencia=<?= $dependencia ?>&ent=<?= $ent ?>">
            Nuevo
        </a>
    </div>
</footer>

<input type="hidden" name="pnom" value="<?= $pnomb ?>">