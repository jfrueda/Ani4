<?php
/**
 * Clase estática para verificar si un usuario (depe_codi + usua_codi)
 * tiene registros configurados en la tabla SGD_USUA_FILTRO.
 *
 * Requisitos solicitados:
 *  - Consultar tabla SGD_USUA_FILTRO usando $_SESSION['dependencia'] => campo depe_codi
 *    y $_SESSION['codusuario'] => campo usua_codi
 *  - Retornar TRUE o FALSE si existe coincidencia
 *  - Métodos estáticos para invocarlos directamente
 *
 * Uso típico:
 *   require_once "$ruta_raiz/include/class/UsuarioFiltro.class.php";
 *   if (UsuarioFiltro::tieneFiltro($db)) { ... }
 */

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (!class_exists('UsuarioFiltro')) {
    class UsuarioFiltro
    {

        /**
         * Verifica si el usuario tiene al menos un registro en SGD_USUA_FILTRO.
         *
         * @param ConnectionHandler|null $db        Instancia de conexión (opcional). Si es null se crea una.
         * @param string|int|null        $depeCodi  (Opcional) dependencia; si null toma $_SESSION['dependencia'].
         * @param string|int|null        $usuaCodi  (Opcional) usuario; si null toma $_SESSION['codusuario'].
         * @return bool TRUE si existe al menos un registro, FALSE si no.
         */
        public static function tieneFiltro($db = null, $depeCodi = null, $usuaCodi = null): bool
        {
            // Cargar valores desde la sesión si no fueron suministrados
            if ($depeCodi === null && isset($_SESSION['dependencia'])) {
                $depeCodi = $_SESSION['dependencia'];
            }
            // Preferir usua_codi (requisito original), fallback a codusuario
            if ($usuaCodi === null) {
                if (isset($_SESSION['usua_codi'])) {
                    $usuaCodi = $_SESSION['usua_codi'];
                } elseif (isset($_SESSION['codusuario'])) {
                    $usuaCodi = $_SESSION['codusuario'];
                }
            }

            // Validaciones mínimas
            if ($depeCodi === null || $usuaCodi === null || $depeCodi === '' || $usuaCodi === '') {
                return false;
            }

            // Crear handler si no viene uno
            if ($db === null) {
                global $ruta_raiz, $db; // reutilizar si existe global
                if (!isset($db) || !$db) {
                    if (!$ruta_raiz) {
                        $ruta_raiz = dirname(__DIR__, 2); // subir a raiz del aplicativo
                    }
                    require_once "$ruta_raiz/include/db/ConnectionHandler.php";
                    $db = new ConnectionHandler($ruta_raiz);
                }
            }

            // Determinar driver
            $driver = strtolower($db->driver ?? '');
            switch ($driver) {
                case 'oci8':
                case 'oracle':
                    $sql = "SELECT 1 FROM SGD_USUA_FILTRO WHERE DEPE_CODI = ? AND USUA_CODI = ? AND ROWNUM = 1";
                    break;
                case 'mssql':
                case 'sqlsrv':
                    $sql = "SELECT TOP 1 1 FROM SGD_USUA_FILTRO WHERE DEPE_CODI = ? AND USUA_CODI = ?";
                    break;
                default: // postgres, mysql, etc.
                    $sql = "SELECT 1 FROM SGD_USUA_FILTRO WHERE DEPE_CODI = ? AND USUA_CODI = ? LIMIT 1";
                    break;
            }

            try {
                // Depuración opcional: definir en el bootstrap define('USUARIO_FILTRO_DEBUG', true);
                if (defined('USUARIO_FILTRO_DEBUG') && USUARIO_FILTRO_DEBUG) {
                    // Probar conexión básica
                    $testConn = $db->conn->Execute('SELECT 1');
                    var_dump([
                        'UF_DRIVER' => $db->driver ?? null,
                        'UF_TEST_CONN' => $testConn ? $testConn->fields : null,
                        'UF_PARAMS' => [$depeCodi, $usuaCodi]
                    ]);
                }

                $rs = $db->conn->Execute($sql, [$depeCodi, $usuaCodi]);

                if (!$rs) {
                    // Error al ejecutar la consulta
                    if (defined('USUARIO_FILTRO_DEBUG') && USUARIO_FILTRO_DEBUG) {
                        var_dump([
                            'UF_SQL_ERROR' => $db->conn->ErrorMsg(),
                            'UF_SQL' => $sql,
                            'UF_PARAMS' => [$depeCodi, $usuaCodi]
                        ]);
                    }
                    return false;
                }

                if ($rs->EOF) {
                    // No hay coincidencia
                    if (defined('USUARIO_FILTRO_DEBUG') && USUARIO_FILTRO_DEBUG) {
                        // Mostrar SQL con parámetros sustituidos para verificar coincidencia
                        $sqlDebug = $sql;
                        foreach ([$depeCodi, $usuaCodi] as $v) {
                            $sqlDebug = preg_replace('/\?/', "'" . addslashes($v) . "'", $sqlDebug, 1);
                        }
                        var_dump([
                            'UF_NO_MATCH' => true,
                            'UF_SQL_REAL' => $sqlDebug
                        ]);
                    }
                    return false;
                }

                // Tiene fila
                if (defined('USUARIO_FILTRO_DEBUG') && USUARIO_FILTRO_DEBUG) {
                    var_dump([
                        'UF_MATCH' => true,
                        'UF_ROW' => $rs->fields
                    ]);
                }
                return true;
            } catch (\Throwable $e) {
                // Fallback sin parámetros (precaución: usar solo si los datos vienen de sesión controlada)
                $depeCodiEsc = addslashes($depeCodi);
                $usuaCodiEsc = addslashes($usuaCodi);
                switch ($driver) {
                    case 'oci8':
                    case 'oracle':
                        $sqlFallback = "SELECT 1 FROM SGD_USUA_FILTRO WHERE depe_codi = '$depeCodiEsc' AND usua_codi = '$usuaCodiEsc' AND ROWNUM = 1";
                        break;
                    case 'mssql':
                    case 'sqlsrv':
                        $sqlFallback = "SELECT TOP 1 1 FROM SGD_USUA_FILTRO WHERE depe_codi = '$depeCodiEsc' AND usua_codi = '$usuaCodiEsc'";
                        break;
                    default:
                        $sqlFallback = "SELECT 1 FROM SGD_USUA_FILTRO WHERE depe_codi = '$depeCodiEsc' AND usua_codi = '$usuaCodiEsc' LIMIT 1";
                        break;
                }
                try {
                    $rs = $db->conn->Execute($sqlFallback);
                    return ($rs && !$rs->EOF);
                } catch (\Throwable $e2) {
                    return false;
                }
            }
        }

        /** Alias semántico de tieneFiltro */
        public static function existe($db = null, $depeCodi = null, $usuaCodi = null): bool
        {
            return self::tieneFiltro($db, $depeCodi, $usuaCodi);
        }

    // Método de compatibilidad previo; ahora solo referencia a tieneFiltro
    public static function limpiarCache(): void { /* sin cache */ }
    }
}
