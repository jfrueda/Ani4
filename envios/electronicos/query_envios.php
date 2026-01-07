<?php

function getEnviosQuery($filters = [], $limit = null, $offset = null, $orderColumn = null, $orderDirection = 'ASC') {
    $searchQuery = '';

    // Filtros por radicados
    if (!empty($filters['radicados'])) {
        $radicadosArray = array_map('intval', explode(',', $filters['radicados']));
        $radicadosList = implode(',', $radicadosArray);
        $searchQuery .= "AND (
            a.radi_nume_salida IN ($radicadosList) OR 
            a.anex_radi_nume IN ($radicadosList)
        ) ";
    }

    // Filtros por dependencia
    if (!empty($filters['dependencia'])) {
        $searchQuery .= "AND (
            r.radi_depe_actu IN (".$filters['dependencia'].")
        ) ";
    }

    // Filtros por usuarios
    if (!empty($filters['usuario'])) {
        $usuariosArray = array_map(function($item) {
            return "'".trim($item)."'";
        }, explode(',', $filters['usuario']));
        $usuariosList = implode(',', $usuariosArray);
        $searchQuery .= "AND a.anex_creador IN ($usuariosList)";
    }

    // Consulta base
    $query = "FROM sgd_rad_envios re 
                LEFT JOIN anexos a ON re.id_anexo = a.id
                LEFT JOIN radicado r ON (a.anex_radi_nume = r.radi_nume_radi OR a.radi_nume_salida = r.radi_nume_radi)
                LEFT JOIN sgd_dir_drecciones dir ON re.id_direccion = dir.id
              WHERE (re.estado = 1 and re.tipo = 'E-mail') and a.anex_estado = 3 $searchQuery";

    // Consulta de datos
    $dataQuery = "SELECT 
                    re.id as id,
                    a.radi_nume_salida as RADICADO_SALIDA, 
                    a.anex_radi_nume as RADICADO_PADRE,
                    r.radi_fech_radi as FECHA_RADICADO,
                    (
                        dir.sgd_dir_nomremdes || ' / ' || 
                        dir.sgd_dir_nombre || ' / ' || 
                        dir.sgd_dir_direccion
                    ) as DESCRIPCION,
                    a.sgd_fech_impres as FECHA_IMPRESION,
                    a.anex_creador as GENERADO_POR,
                    '' as CERTIFICADO,
                    dir.sgd_dir_mail as EMAILS,
                    re.registro as REGISTRO,
                    re.estado as ESTADO,
                    re.devuelto as DEVUELTO
                 $query";

    $validColumns = [
        'RADICADO_SALIDA' => 'a.radi_nume_salida',
        'RADICADO_PADRE' => 'a.anex_radi_nume',
        'FECHA_RADICADO' => 'r.radi_fech_radi'
    ];

    if (isset($validColumns[$orderColumn])) {
        $dataQuery .= " ORDER BY " . $validColumns[$orderColumn] . " " . strtoupper($orderDirection);
    } else {
        $dataQuery .= " ORDER BY r.radi_fech_radi DESC";
    }

    // Agregar límites si se especifican
    if ($limit !== null && $offset !== null) {
        $dataQuery .= " LIMIT $limit OFFSET $offset";
    }

    // Consulta para contar registros
    $countQuery = "SELECT COUNT(*) $query";

    return [
        'count' => $countQuery,
        'data' => $dataQuery
    ];
}
