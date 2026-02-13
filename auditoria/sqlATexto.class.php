<?php

class SQLAnalyzer {
    public static function analyze($sql) {
        // Patrones para buscar la tabla y los valores asignados a cada campo
        $patronTabla = '/UPDATE\s+(\w+)\s+|INSERT\s+INTO\s+(\w+)\s+/i';
        $patronValores = '/SET\s+(.*)\s+WHERE|VALUES\s+\((.*)\)/i';
        
        // Buscar la tabla afectada
        preg_match($patronTabla, $sql, $matchesTabla);
        $tablaAfectada = isset($matchesTabla[1]) ? $matchesTabla[1] : $matchesTabla[2];
        
        // Buscar los valores asignados a cada campo
        preg_match($patronValores, $sql, $matchesValores);
        $valores = isset($matchesValores[1]) ? $matchesValores[1] : $matchesValores[2];
        
        // Convertir los valores en un array asociativo
        $valoresArray = [];
        $valoresExplode = explode(',', $valores);
        foreach ($valoresExplode as $valor) {
            $valor = trim($valor);
            $asignacion = explode('=', $valor);
            $campo = trim($asignacion[0]);
            $valorCampo = trim($asignacion[1], " ^");
            $valoresArray[$campo] = $valorCampo;
        }
        
        // Devolver la tabla afectada y los valores asignados
        return array('tabla' => $tablaAfectada, 'valores' => $valoresArray);
    }
}
