<?php
session_start();
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
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



$ruta_raiz = '../../';
if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once("$ruta_raiz/include/tx/roles.php");

$db       = new ConnectionHandler("$ruta_raiz");
$roles    = new Roles($db);

header('Content-Type: application/json');

switch ($_POST['accion']){

    /**************************************
     * ********** Edición de grupos ********
     * ************************************/

    // Borrar registros de las distintas acciones..........................................
    case 'borrar':
        $id = $_POST['id'];

        switch($_POST['tipo']){

            case 'grupos':
                if($roles->borrarGrupo($id)){
                    $resultado = array('estado' => 1, 'valor' => $roles->id);
                }else{
                    $resultado = array('estado' => 0, 'valor' => '');
                }
                break;

            case 'permisos':
                if($roles->borrarPermiso($id)){
                    $resultado = array('estado' => 1, 'valor' => $roles->id);
                }else{
                    $resultado = array('estado' => 0, 'valor' => '');
                }
                break;

            case 'usuarios':
                if($roles->borrarUsuario($id)){
                    $resultado = array('estado' => 1, 'valor' => $roles->id);
                }else{
                    $resultado = array('estado' => 0, 'valor' => '');
                }
                break;
        }
        break;


    // Guardar registros...........................................
    case 'guardar':

        $id = $_POST['id'];

        switch($_POST['tipo']){

            case 'grupos':
                $nombre      = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                if($roles->creaEditaGrupo($nombre,$descripcion, $id)){
                    $resultado = array('estado' => 1, 'valor' => $roles->id, 'nombre' => $nombre);
                }else{
                    $resultado = array('estado' => 0, 'valor' => '');
                }
                break;


            case 'permisos':
                $nombre      = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $crud        = $_POST['crud'];
                $grupo       = $_POST['grupo'];

                if($roles->creaEditaPermiso($nombre, $descripcion, $crud, $grupo, $id)){
                    $resultado = array('estado' => 1, 'valor' => $roles->id);
                }else{
                    $resultado = array('estado' => 0, 'valor' => $roles->error);
                }
                break;


            case 'usuarios':

                $nombres     = $_POST['nombres'];
                $nuevo       = $_POST['nuevo'];
                $correo      = $_POST['correo'];
                $documento   = $_POST['documento'];
                $usuario     = $_POST['usuarios'];
                $estado      = $_POST['estado'];
                $depen       = $_POST['dependencia'];
                $nivel_seg   = $_POST['nivel'];
                $ldap_login   = $_POST['ldap_login'];
                $grupo_por_defecto = $_POST['grupo'];

                if($roles->creaEditaUsuario($usuario, $nombres, $nuevo, trim($correo), $estado, $depen, $id, trim($documento), $nivel_seg, $ldap_login)){

                    $usuario_id = $db->conn->getOne('SELECT id FROM usuario WHERE usua_login = ?', [strtoupper($usuario)]);
                    $contratista = $db->conn->getOne('SELECT id FROM autg_grupos WHERE nombre = ?', ['Contratista']);
                    $profesional = $db->conn->getOne('SELECT id FROM autg_grupos WHERE nombre = ?', ['Profesional']);
                    $roles->modificarMembresia($contratista, $usuario_id, false);
                    $roles->modificarMembresia($profesional, $usuario_id, false);
                    if ($grupo_por_defecto == $contratista) {
                        $roles->modificarMembresia($contratista, $usuario_id, true);
                    } else if ($grupo_por_defecto == $profesional) {
                        $roles->modificarMembresia($profesional, $usuario_id, true);
                    }

                    $resultado = array('estado' => 1, 'valor' => $roles->id, 'grupo' => $grupo, 'usuario' => $usuario,
                        'nombre' => $nombres );
                }else{
                    $resultado = array('estado' => 0, 'valor' => $roles->error);
                }
                break;
        }
        break;



    // Buscar registros de los usuairos del grupo para realizar las memebresias.............
    case 'buscarUsuariosDelGrupo':
        $grupo  = $_POST['grupo'];
        if($roles->buscarUsuariosGrupo($grupo)){
            $resultado = array('estado' => 1, 'valor' => $roles->users);
        }else{
            $resultado = array('estado' => 0, 'valor' => '');
        }
        break;

    // Guardar registros cuando el usuario seleccione un usuario en un grupo................
    case 'grabarUsuariosDelGrupo':
        $usuario  = $_POST['usuario'];
        $estado   = $_POST['estado'];
        $grupo    = $_POST['grupo'];

		$isqlIsBoss="select nombre from autg_grupos where id ='$grupo'";
		$rsIsBoss=$db->conn->GetArray($isqlIsBoss);
		if($rsIsBoss[0]["NOMBRE"]=="LIDER DE GRUPO"){
			$isqlIsTask="select count(*) from radicado r, usuario u where r.radi_usua_actu =u.usua_codi and r.radi_depe_actu=u.depe_codi and u.id='$usuario'";
			$rsIsTask=$db->conn->GetArray($isqlIsTask);
			if ($estado=="true"){
				$_isqlIsBoss="select id, usua_codi, depe_codi, usua_login from usuario where usua_codi=1 and depe_codi=(select depe_codi from usuario where id='$usuario')";
				$_rsIsBoss=$db->conn->GetArray($_isqlIsBoss);
				if ($_rsIsBoss){
					if ($_rsIsBoss[0]["ID"]==$usuario)
					{
						$newBoss=true;
					}else{
						$currentBoss=$_rsIsBoss[0]["ID"];
            			$resultado = array('estado' => 0, 'valor' => 'Error, la dependencia <b>'.$_rsIsBoss[0]["DEPE_CODI"].'</b> ya tiene como lider al usuario <b>'.$_rsIsBoss[0]["USUA_LOGIN"].'</b>');
						die(json_encode($resultado));
					}
				}
				if (!$_rsIsBoss or $newBoss===true){
					if ($rsIsTask[0]["COUNT"]==0){
						$isqlNewBoss="update usuario set usua_codi=1 where id='$usuario'";
						$db->conn->Execute($isqlNewBoss);
					}else{
            			$resultado = array('estado' => 0, 'valor' => 'Error, El usuario <b>'.$_rsIsBoss[0]["USUA_LOGIN"].'</b> Tiene '.$rsIsTask[0]["COUNT"].' radicados en sus bandejas');
						die(json_encode($resultado));
					}
				}
			}else if ($estado=="false"){
				if ($rsIsTask[0]["COUNT"]==0){
					$isqlDelBoss="update usuario set usua_codi=(select max(usua_codi)+1 from usuario where depe_codi=(select depe_codi from usuario where id='$usuario')) where id='$usuario'";
					$db->conn->Execute($isqlDelBoss);
				}else{
            			$resultado = array('estado' => 0, 'valor' => 'Error, El usuario <b>'.$_rsIsBoss[0]["USUA_LOGIN"].'</b> Tiene '.$rsIsTask[0]["COUNT"].' radicados en sus bandejas');
						die(json_encode($resultado));
				}
			}
		}

        if($grupo == 2) {

            $estadoRol = $roles->modificarMembresiaJefeArea($grupo,$usuario,$estado);
            if($estadoRol == 0) {
                $resultado = array('estado' => 0, 'valor' => 'Error!');
            } elseif ($estadoRol == 1) {
                $resultado = array('estado' => 1, 'valor' => 'Guardado.');
            } elseif ($estadoRol == 2) {
                $resultado = array('estado' => 1, 'valor' => 'Eliminado correctamente.');
            } elseif ($estadoRol == 3) {
                $resultado = array('estado' => 0, 'valor' => 'No se puede eliminar ya que no se puede quedar sin Jéfe de área.');
            } elseif ($estadoRol == 4) {
                $resultado = array('estado' => 0, 'valor' => 'No se puede asigar al usuario por que esta inactivo.');
            }

                  
        } else {
            if($roles->modificarMembresia($grupo,$usuario,$estado)){
                $resultado = array('estado' => 1, 'valor' => 'Guardado');
            }else{
                $resultado = array('estado' => 0, 'valor' => 'Error!');
            }
        }

        break;
}

echo json_encode($resultado);
