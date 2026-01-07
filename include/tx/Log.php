<?php

class Log {

    private $db;
    function __construct($db) {
        $this->db = $db;
    }

    public function insert($modelo, $transaccion, $registroAntes, $registroDespues, $usuario) {
        $sql = "INSERT INTO log 
                (modelo, transaccion, registro_antes, registro_despues, usuario, fecha)
                VALUES 
                (?, ?, ?, ?, ?, NOW())";

        // Convertir arrays/objetos a JSON si no vienen en string
        if (is_array($registroAntes) || is_object($registroAntes)) {
            $registroAntes = json_encode($registroAntes, JSON_UNESCAPED_UNICODE);
        }

        if (is_array($registroDespues) || is_object($registroDespues)) {
            $registroDespues = json_encode($registroDespues, JSON_UNESCAPED_UNICODE);
        }

        return $this->db->Execute($sql, [
            $modelo,
            $transaccion,
            $registroAntes == '[]' ? null : $registroAntes,
            $registroDespues == '[]' ? null : $registroDespues,
            $usuario
        ]);
    }
} 

class Modelo {
    const USUARIO = 'Usuario';
    const GRUPO = 'Grupo';
    const PERMISO = 'Permiso';
}

class Transaccion {
    const USUARIO_CREAR = 'Crear usuario';
    const USUARIO_ACTUALIZAR = 'Actualizar usuario';
    const CAMBIO_JEFE_AREA = 'Cambio jefe de area';
    const INACTIVAR_USUARIO = 'Inactivar usuario';
    const USUARIO_ACTUALIZAR_GRUPOS = 'Actualizar grupos usuario';

    const GRUPO_CREAR = 'Crear grupo';
    const GRUPO_ACTUALIZAR = 'Actualizar grupo';
    const GRUPO_ELIMINAR = 'Eliminar grupo';

    const PERMISO_CREAR = 'Crear permiso';
    const PERMISO_ACTUALIZAR = 'Actualizar permiso';
    const PERMISO_ELIMINAR = 'Eliminar permiso';
}