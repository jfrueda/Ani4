<?php

/**
 * Clase para manejar la funcionalidad relacionada con jefes de área
 * 
 * @author Sistema Argo
 * @license GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyleft
 * 
 * Esta clase maneja las consultas relacionadas con los jefes de área,
 * obteniendo información de usuarios con rol de jefe (autg_id=2) para
 * una dependencia específica.
 * 
 * FORMA DE USO:
 * 1. Incluir la clase: include_once("include/class/JefeArea.class.php");
 * 2. Usar métodos estáticos:
 *    - JefeArea::getDatosJefe($db) // Usa dependencia de sesión
 *    - JefeArea::getDatosJefe($db, $dependencia) // Usa dependencia específica
 *    - JefeArea::getCodigoJefe($db, $dependencia)
 *    - JefeArea::getDocumentoJefe($db, $dependencia)
 *    - JefeArea::tieneJefe($db, $dependencia)
 * 
 * INTEGRACIÓN CON CONSULTAS EXISTENTES:
 * Esta clase encapsula la consulta SQL que se usa en txOrfeo.php, cuerpoJefe.php
 * y otros archivos para obtener los datos del jefe de una dependencia.
 */

class JefeArea
{
    /**
     * Obtiene los datos completos del jefe de una dependencia
     * 
     * @param object $db Objeto de conexión a la base de datos (ConnectionHandler)
     * @param int $dependencia Código de la dependencia (opcional, usa $_SESSION['dependencia'] si no se proporciona)
     * @return array|null Array con los datos del jefe o null si no se encuentra
     *                   - usua_codi: código del usuario jefe
     *                   - usua_doc: documento del usuario jefe
     *                   - existe: boolean indicando si se encontró un jefe
     */
    public static function getDatosJefe($db, $dependencia = null)
    {
        // Si no se proporciona dependencia, usar la de sesión
        if ($dependencia === null) {
            $dependencia = $_SESSION['dependencia'] ?? 0;
        }
        
        // Sanitizar parámetro
        $dependencia = intval($dependencia);
        
        if ($dependencia <= 0) {
            return array(
                'usua_codi' => null,
                'usua_doc' => null,
                'existe' => false
            );
        }
        
        $sql_jefe = "SELECT u.usua_codi, u.usua_doc
            FROM usuario u, autm_membresias a
            WHERE u.id = a.autu_id
            AND a.autg_id = 2
            AND u.depe_codi = $dependencia";
        
        try {
            $rs_jefe = $db->conn->Execute($sql_jefe);
            
            if ($rs_jefe && !$rs_jefe->EOF) {
                return array(
                    'usua_codi' => $rs_jefe->fields['USUA_CODI'],
                    'usua_doc' => $rs_jefe->fields['USUA_DOC'],
                    'existe' => true
                );
            }
            
        } catch (Exception $e) {
            error_log("Error en JefeArea::getDatosJefe - " . $e->getMessage());
        }
        
        return array(
            'usua_codi' => null,
            'usua_doc' => null,
            'existe' => false
        );
    }
    
    /**
     * Obtiene solo el código del usuario jefe
     * 
     * @param object $db Objeto de conexión a la base de datos
     * @param int $dependencia Código de la dependencia (opcional)
     * @return string|null Código del usuario jefe o null si no existe
     */
    public static function getCodigoJefe($db, $dependencia = null)
    {
        $datos = self::getDatosJefe($db, $dependencia);
        return $datos['usua_codi'];
    }
    
    /**
     * Obtiene solo el documento del usuario jefe
     * 
     * @param object $db Objeto de conexión a la base de datos
     * @param int $dependencia Código de la dependencia (opcional)
     * @return string|null Documento del usuario jefe o null si no existe
     */
    public static function getDocumentoJefe($db, $dependencia = null)
    {
        $datos = self::getDatosJefe($db, $dependencia);
        return $datos['usua_doc'];
    }
    
    /**
     * Verifica si una dependencia tiene jefe asignado
     * 
     * @param object $db Objeto de conexión a la base de datos
     * @param int $dependencia Código de la dependencia (opcional)
     * @return boolean True si tiene jefe, False en caso contrario
     */
    public static function tieneJefe($db, $dependencia = null)
    {
        $datos = self::getDatosJefe($db, $dependencia);
        return $datos['existe'];
    }
    
    /**
     * Obtiene el RecordSet completo del jefe (para compatibilidad con código existente)
     * 
     * @param object $db Objeto de conexión a la base de datos
     * @param int $dependencia Código de la dependencia (opcional)
     * @return object|null RecordSet con los datos del jefe o null si no existe
     */
    public static function getRecordSetJefe($db, $dependencia = null)
    {
        // Si no se proporciona dependencia, usar la de sesión
        if ($dependencia === null) {
            $dependencia = $_SESSION['dependencia'] ?? 0;
        }
        
        // Sanitizar parámetro
        $dependencia = intval($dependencia);
        
        if ($dependencia <= 0) {
            return null;
        }
        
        $sql_jefe = "SELECT u.usua_codi, u.usua_doc
            FROM usuario u, autm_membresias a
            WHERE u.id = a.autu_id
            AND a.autg_id = 2
            AND u.depe_codi = $dependencia";
        
        try {
            $rs_jefe = $db->conn->Execute($sql_jefe);
            return ($rs_jefe && !$rs_jefe->EOF) ? $rs_jefe : null;
            
        } catch (Exception $e) {
            error_log("Error en JefeArea::getRecordSetJefe - " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene información completa del jefe incluyendo datos adicionales del usuario
     * 
     * @param object $db Objeto de conexión a la base de datos
     * @param int $dependencia Código de la dependencia (opcional)
     * @return array|null Array con información extendida del jefe
     */
    public static function getInfoCompletaJefe($db, $dependencia = null)
    {
        // Si no se proporciona dependencia, usar la de sesión
        if ($dependencia === null) {
            $dependencia = $_SESSION['dependencia'] ?? 0;
        }
        
        // Sanitizar parámetro
        $dependencia = intval($dependencia);
        
        if ($dependencia <= 0) {
            return null;
        }
        
        $sql_jefe_completo = "SELECT 
            u.usua_codi,
            u.usua_doc,
            u.usua_nomb,
            u.usua_email,
            u.depe_codi,
            d.depe_nomb
            FROM usuario u
            INNER JOIN autm_membresias a ON u.id = a.autu_id
            INNER JOIN dependencia d ON u.depe_codi = d.depe_codi
            WHERE a.autg_id = 2
            AND u.depe_codi = $dependencia";
        
        try {
            $rs_jefe = $db->conn->Execute($sql_jefe_completo);
            
            if ($rs_jefe && !$rs_jefe->EOF) {
                return array(
                    'usua_codi' => $rs_jefe->fields['USUA_CODI'],
                    'usua_doc' => $rs_jefe->fields['USUA_DOC'],
                    'usua_nomb' => $rs_jefe->fields['USUA_NOMB'],
                    'usua_email' => $rs_jefe->fields['USUA_EMAIL'],
                    'depe_codi' => $rs_jefe->fields['DEPE_CODI'],
                    'depe_nomb' => $rs_jefe->fields['DEPE_NOMB'],
                    'existe' => true
                );
            }
            
        } catch (Exception $e) {
            error_log("Error en JefeArea::getInfoCompletaJefe - " . $e->getMessage());
        }
        
        return null;
    }
}