<?php
session_start();

$ruta_raiz = "../..";

if (!$_SESSION['dependencia']) {
    header("Location: " . $ruta_raiz . "/cerrar_session.php");
    exit;
}

if (!$_SESSION["usua_admin_sistema"]) {
    header("Location: " . $ruta_raiz . "/cerrar_session.php");
    exit;
}

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include $ruta_raiz . "/config.php";
include $ruta_raiz . "/htmlheader-old.inc.php";

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$dependencias = $db->conn->getAll('SELECT depe_codi, depe_nomb FROM dependencia ORDER BY depe_codi');
$gruposRequeridos = $db->conn->getAll('SELECT id, nombre FROM autg_grupos WHERE nombre IN (?, ?) ORDER BY nombre', ['Profesional', 'Contratista']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Creación masiva de usuarios</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; }
        .container { max-width: 1300px; margin: 16px auto; padding: 0 12px; }
        .card { background: #fff; border: 1px solid #d9dee7; border-radius: 6px; margin-bottom: 14px; }
        .card-head { background: #043074; color: #fff; padding: 10px 14px; font-weight: 700; border-radius: 6px 6px 0 0; }
        .card-body { padding: 14px; }
        .help { margin: 0 0 8px 0; color: #333; }
        .row { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { border: 1px solid #20467c; background: #043074; color: #fff; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .btn.secondary { background: #fff; color: #043074; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .table-wrap { overflow: auto; border: 1px solid #d9dee7; }
        table { border-collapse: collapse; width: 100%; min-width: 1200px; }
        th, td { border: 1px solid #d9dee7; padding: 6px; text-align: left; }
        th { background: #eef3fb; color: #043074; }
        input, select, textarea { width: 100%; box-sizing: border-box; border: 1px solid #bec8d8; border-radius: 4px; padding: 6px; }
        textarea { min-height: 130px; font-family: monospace; }
        .status { margin-top: 10px; padding: 10px; border-radius: 4px; display: none; }
        .status.ok { display: block; background: #e7f7ec; border: 1px solid #89c89d; color: #116b2c; }
        .status.err { display: block; background: #fde9e9; border: 1px solid #e3a1a1; color: #922; }
        .muted { color: #666; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-head">Creación masiva de usuarios</div>
        <div class="card-body">
            <p class="help">Use los mismos campos de la pantalla estándar: <strong>usuario, nuevo, nombres, documento, dependencia, correo, estado, nivel, ldap_login, grupo</strong>.</p>
            <p class="muted">`dependencia` debe ser código de dependencia (DEPE_CODI). `grupo` debe ser ID de perfil por defecto (Contratista o Profesional).</p>
            <div class="row">
                <button class="btn secondary" id="addRow" type="button">Agregar fila</button>
                <button class="btn secondary" id="addExample" type="button">Cargar ejemplo</button>
                <button class="btn" id="sendRows" type="button">Crear usuarios (tabla)</button>
            </div>
            <div class="table-wrap" style="margin-top:10px;">
                <table id="bulkTable">
                    <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Usuario</th>
                        <th>Nuevo</th>
                        <th>Nombres</th>
                        <th>Documento</th>
                        <th>Dependencia</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Nivel</th>
                        <th>LDAP</th>
                        <th>Grupo</th>
                    </tr>
                    </thead>
                    <tbody id="bulkBody"></tbody>
                </table>
            </div>
            <div id="statusTable" class="status"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">Pegado CSV (opcional)</div>
        <div class="card-body">
            <p class="help">Pegue filas CSV separadas por coma o punto y coma, en este orden:</p>
            <p class="muted">usuario,nuevo,nombres,documento,dependencia,correo,estado,nivel,ldap_login,grupo</p>
            <textarea id="csvInput" placeholder="USR1,0,Nombre Apellido,123456,900,correo@dominio.gov.co,1,5,0,3"></textarea>
            <div class="row" style="margin-top:10px;">
                <button class="btn secondary" id="parseCsv" type="button">Pasar CSV a tabla</button>
            </div>
            <div id="statusCsv" class="status"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">Ayudas rápidas</div>
        <div class="card-body">
            <div class="row">
                <div style="flex:1 1 460px;">
                    <strong>Dependencias</strong>
                    <div class="table-wrap" style="max-height:250px;">
                        <table>
                            <thead><tr><th>Código</th><th>Nombre</th></tr></thead>
                            <tbody>
                            <?php foreach ($dependencias as $depe): ?>
                                <tr>
                                    <td><?php echo (int)$depe['DEPE_CODI']; ?></td>
                                    <td><?php echo htmlspecialchars($depe['DEPE_NOMB']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="flex:1 1 280px;">
                    <strong>Perfiles permitidos</strong>
                    <div class="table-wrap" style="max-height:250px;">
                        <table>
                            <thead><tr><th>ID</th><th>Nombre</th></tr></thead>
                            <tbody>
                            <?php foreach ($gruposRequeridos as $grupo): ?>
                                <tr>
                                    <td><?php echo (int)$grupo['ID']; ?></td>
                                    <td><?php echo htmlspecialchars($grupo['NOMBRE']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var body = document.getElementById('bulkBody');
    var statusTable = document.getElementById('statusTable');
    var statusCsv = document.getElementById('statusCsv');

    function rowTemplate(values) {
        values = values || {};
        return '<tr>' +
            '<td><button type="button" class="btn secondary removeRow">Quitar</button></td>' +
            '<td><input name="usuarios" value="' + esc(values.usuarios || '') + '"></td>' +
            '<td><select name="nuevo"><option value="">Sel</option><option value="0" ' + sel(values.nuevo, '0') + '>Actual</option><option value="1" ' + sel(values.nuevo, '1') + '>Nuevo</option></select></td>' +
            '<td><input name="nombres" value="' + esc(values.nombres || '') + '"></td>' +
            '<td><input name="documento" value="' + esc(values.documento || '') + '"></td>' +
            '<td><input name="dependencia" value="' + esc(values.dependencia || '') + '"></td>' +
            '<td><input name="correo" value="' + esc(values.correo || '') + '"></td>' +
            '<td><select name="estado"><option value="">Sel</option><option value="1" ' + sel(values.estado, '1') + '>Activo</option><option value="0" ' + sel(values.estado, '0') + '>Inactivo</option></select></td>' +
            '<td><select name="nivel"><option value="">Sel</option><option value="1" ' + sel(values.nivel, '1') + '>1</option><option value="2" ' + sel(values.nivel, '2') + '>2</option><option value="3" ' + sel(values.nivel, '3') + '>3</option><option value="4" ' + sel(values.nivel, '4') + '>4</option><option value="5" ' + sel(values.nivel, '5') + '>5</option></select></td>' +
            '<td><select name="ldap_login"><option value="null">No definido</option><option value="0" ' + sel(values.ldap_login, '0') + '>Inactivo</option><option value="1" ' + sel(values.ldap_login, '1') + '>Activo</option></select></td>' +
            '<td><input name="grupo" value="' + esc(values.grupo || '') + '"></td>' +
            '</tr>';
    }

    function esc(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function sel(a, b) {
        return String(a) === String(b) ? 'selected' : '';
    }

    function setStatus(el, ok, html) {
        el.className = 'status ' + (ok ? 'ok' : 'err');
        el.innerHTML = html;
    }

    function addRow(values) {
        body.insertAdjacentHTML('beforeend', rowTemplate(values));
    }

    function collectRows() {
        var rows = [];
        var trList = body.querySelectorAll('tr');
        for (var i = 0; i < trList.length; i++) {
            var tr = trList[i];
            var row = {
                usuarios: tr.querySelector('[name="usuarios"]').value.trim(),
                nuevo: tr.querySelector('[name="nuevo"]').value,
                nombres: tr.querySelector('[name="nombres"]').value.trim(),
                documento: tr.querySelector('[name="documento"]').value.trim(),
                dependencia: tr.querySelector('[name="dependencia"]').value.trim(),
                correo: tr.querySelector('[name="correo"]').value.trim(),
                estado: tr.querySelector('[name="estado"]').value,
                nivel: tr.querySelector('[name="nivel"]').value,
                ldap_login: tr.querySelector('[name="ldap_login"]').value,
                grupo: tr.querySelector('[name="grupo"]').value.trim()
            };
            rows.push(row);
        }
        return rows;
    }

    function parseCsvLine(line) {
        var sep = line.indexOf(';') !== -1 ? ';' : ',';
        return line.split(sep).map(function(v){ return v.trim(); });
    }

    function validateRows(rows) {
        var errors = [];
        for (var i = 0; i < rows.length; i++) {
            var r = rows[i];
            if (!r.usuarios || !r.nombres || !r.documento || !r.dependencia || !r.correo || r.nuevo === '' || r.estado === '' || r.nivel === '' || !r.grupo) {
                errors.push('Fila ' + (i + 1) + ': faltan campos obligatorios.');
            }
        }
        return errors;
    }

    function sendRows() {
        var rows = collectRows();
        if (!rows.length) {
            setStatus(statusTable, false, 'No hay filas para procesar.');
            return;
        }

        var errs = validateRows(rows);
        if (errs.length) {
            setStatus(statusTable, false, errs.join('<br>'));
            return;
        }

        var btn = document.getElementById('sendRows');
        btn.disabled = true;
        setStatus(statusTable, true, 'Procesando ' + rows.length + ' filas...');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajaxCreacionMasiva.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) {
                return;
            }
            btn.disabled = false;
            if (xhr.status !== 200) {
                setStatus(statusTable, false, 'Error HTTP al procesar el lote.');
                return;
            }
            try {
                var res = JSON.parse(xhr.responseText);
                if (!res.estado) {
                    setStatus(statusTable, false, esc(res.mensaje || 'Error procesando lote'));
                    return;
                }
                var html = 'Proceso finalizado. Creados/actualizados: <strong>' + res.total_ok + '</strong>. Errores: <strong>' + res.total_error + '</strong>.';
                if (res.errores && res.errores.length) {
                    html += '<hr>' + res.errores.map(function(e){ return esc(e); }).join('<br>');
                }
                setStatus(statusTable, res.total_error === 0, html);
            } catch (e) {
                setStatus(statusTable, false, 'Respuesta inválida del servidor.');
            }
        };
        xhr.send('rows=' + encodeURIComponent(JSON.stringify(rows)));
    }

    document.getElementById('addRow').addEventListener('click', function() {
        addRow();
    });

    document.getElementById('addExample').addEventListener('click', function() {
        addRow({
            usuarios: 'NUEVOUSR1',
            nuevo: '0',
            nombres: 'USUARIO DE PRUEBA',
            documento: '123456789',
            dependencia: '900',
            correo: 'usuario.prueba@entidad.gov.co',
            estado: '1',
            nivel: '5',
            ldap_login: '0',
            grupo: '<?php echo isset($gruposRequeridos[0]['ID']) ? (int)$gruposRequeridos[0]['ID'] : ''; ?>'
        });
    });

    document.getElementById('sendRows').addEventListener('click', sendRows);

    body.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            var tr = e.target.closest('tr');
            if (tr) {
                tr.remove();
            }
        }
    });

    document.getElementById('parseCsv').addEventListener('click', function() {
        var raw = document.getElementById('csvInput').value || '';
        var lines = raw.split(/\r?\n/).map(function(v){ return v.trim(); }).filter(Boolean);
        if (!lines.length) {
            setStatus(statusCsv, false, 'No hay contenido CSV.');
            return;
        }

        var added = 0;
        for (var i = 0; i < lines.length; i++) {
            var cols = parseCsvLine(lines[i]);
            if (cols.length < 10) {
                continue;
            }
            addRow({
                usuarios: cols[0],
                nuevo: cols[1],
                nombres: cols[2],
                documento: cols[3],
                dependencia: cols[4],
                correo: cols[5],
                estado: cols[6],
                nivel: cols[7],
                ldap_login: cols[8],
                grupo: cols[9]
            });
            added++;
        }

        if (!added) {
            setStatus(statusCsv, false, 'No se cargaron filas: verifique que cada línea tenga 10 columnas.');
            return;
        }
        setStatus(statusCsv, true, 'Se cargaron ' + added + ' filas a la tabla.');
    });

    addRow();
})();
</script>
</body>
</html>
