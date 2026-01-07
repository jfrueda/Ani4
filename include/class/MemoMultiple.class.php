<?php

class MemoMultiple
{
    /**
     * Valida si el radicado es un memo múltiple.
     */
    public static function isMemoMultiple($db, $radicado)
    {
        $sql = "SELECT COUNT(*) as total
                FROM SGD_DIR_DRECCIONES
                WHERE radi_nume_radi = '$radicado'";
        $rs = $db->conn->Execute($sql);
        $sqlTipo = "SELECT sgd_trad_codigo FROM radicado WHERE radi_nume_radi = '$radicado'";
        $rsTipo = $db->conn->Execute($sqlTipo);
        return ($rs && intval($rs->fields['TOTAL']) > 1 && $rsTipo && intval($rsTipo->fields['SGD_TRAD_CODIGO']) == 3);
    }

    /**
     * Valida si el usuario es destinatario del memo múltiple.
     */
    public static function isDestinatario($db, $radicado, $usua_doc = null, $usua_codi = null, $depe_codi = null)
    {
        $where = [];
        if ($usua_doc) {
            $where[] = "SGD_DIR_DOC = '$usua_doc'";
        }
        if ($usua_codi && $depe_codi) {
            $sqlUsuario = "SELECT USUA_DOC FROM USUARIO WHERE USUA_CODI = '$usua_codi' and DEPE_CODI = '$depe_codi'";
            $rsUsuario = $db->conn->Execute($sqlUsuario);
            
            if ($rsUsuario && ($usua_doc_from_codi = $rsUsuario->fields['USUA_DOC'] ?? $rsUsuario->fields['USUA_DOC'] ?? null)) {
                $where[] = "SGD_DIR_DOC = '$usua_doc_from_codi'";
            }
        }
        if (empty($where)) return false;

        $sql = "SELECT COUNT(*) as TOTAL FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = '$radicado' AND (" . implode(' OR ', $where) . ")";
        $rs = $db->conn->Execute($sql);

        $total = $rs ? ($rs->fields['TOTAL'] ?? intval($rs->fields['TOTAL']) ?? 0) : 0;
        return ($total > 0);
    }

    /**
     * Verifica si un proceso está finalizado para un usuario específico
     * 
     * @param object $db Conexión a la base de datos
     * @param int $numeroRadicado Número del radicado
     * @param int $codusuario Código del usuario
     * @param int $dependencia Código de la dependencia
     * @param string $usuaDoc Documento del usuario
     * @return boolean True si está finalizado, False si aún está pendiente
     */
    public static function estaFinalizado($db, $numeroRadicado, $codusuario = null, $dependencia = null, $usuaDoc = null)
    {
        if (empty($numeroRadicado)) {
            return false;
        }

        // Si no se proporcionan parámetros, usar variables de sesión
        if ($codusuario === null) {
            $codusuario = $_SESSION['codusuario'] ?? 0;
        }
        if ($dependencia === null) {
            $dependencia = $_SESSION['dependencia'] ?? 0;
        }
        if ($usuaDoc === null) {
            $usuaDoc = $_SESSION['usua_doc'] ?? '';
        }

        // Sanitizar parámetros
        $numeroRadicado = intval($numeroRadicado);
        $codusuario = intval($codusuario);
        $dependencia = intval($dependencia);
        $usuaDoc = $db->conn->qstr($usuaDoc);

        $iSqlFinalizado = "SELECT COUNT(*) as TOTAL
            FROM hist_eventos t 
            WHERE t.radi_nume_radi = $numeroRadicado 
            AND (
                (t.usua_codi = $codusuario AND t.depe_codi = $dependencia) 
                OR t.usua_doc = $usuaDoc
            ) 
            AND t.sgd_ttr_codigo IN (9,13)";

        try {
            $rs = $db->conn->Execute($iSqlFinalizado);
            
            if ($rs && !$rs->EOF) {
                $total = intval($rs->fields['TOTAL']);
                // Si hay registros en hist_eventos con códigos 9 o 13, significa que está finalizado
                return ($total > 0);
            }
            
        } catch (Exception $e) {
            error_log("Error en MemoMultiple::estaFinalizado - " . $e->getMessage());
        }

        return false;
    }

    /**
     * Obtiene información completa del memo múltiple incluyendo destinatarios
     * 
     * @param object $db Conexión a la base de datos
     * @param int $numeroRadicado Número del radicado
     * @return array Array con información completa del memo múltiple
     */
    public static function getInfoCompleta($db, $numeroRadicado)
    {
        $info = array(
            'es_multiple' => false,
            'total_destinatarios' => 0,
            'destinatarios' => '',
            'total_anexos' => 0
        );

        if (empty($numeroRadicado)) {
            return $info;
        }

        // Sanitizar el número de radicado
        $numeroRadicado = intval($numeroRadicado);

        $iSqlMemorandoMultipleCuerpo = "SELECT 
            count(*) as TOTAL,
            string_agg(DISTINCT SGD_DIR_DRECCIONES.sgd_dir_nombre, ', ') AS DESTINATARIOS,
            (SELECT count(*) FROM ANEXOS WHERE ANEXOS.radi_nume_salida = '$numeroRadicado' AND ANEX_ESTADO >= 2) AS RADICADO 
        FROM
            SGD_DIR_DRECCIONES 
        WHERE
            radi_nume_radi = '$numeroRadicado' 
            AND sgd_trad_codigo = 3";

        try {
            $rs = $db->conn->Execute($iSqlMemorandoMultipleCuerpo);
            
            if ($rs && !$rs->EOF) {
                $info['total_destinatarios'] = intval($rs->fields['TOTAL']);
                $info['destinatarios'] = trim($rs->fields['DESTINATARIOS'] ?? '');
                $info['total_anexos'] = intval($rs->fields['RADICADO']);
                $info['es_multiple'] = ($info['total_destinatarios'] > 1);
            }
            
        } catch (Exception $e) {
            error_log("Error en MemoMultiple::getInfoCompleta - " . $e->getMessage());
        }

        return $info;
    }
}