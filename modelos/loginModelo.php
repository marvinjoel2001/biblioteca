<?php
if($peticionAjax){
			require_once "../core/mainModel.php";
		}else{
			require_once "./core/mainModel.php";
		}
		class loginModelo extends mainModel{
			protected function iniciar_sesion_modelo($datos){
				$sql=self::conectar()->prepare("SELECT * FROM cuenta WHERE CuentaUsuario=:Usuario AND CuentaClave=:Clave AND CuentaEstado='Activo'");
					$sql->bindParam(":Usuario",$datos['Usuario']);
					$sql->bindParam(":Clave",$datos['Clave']);
					$sql->execute();
					return $sql;
			}

			protected function cerrar_sesion_modelo($datos){
				if ($datos['Usuario']!="" && $datos['Token_S']==$datos['Token']){
					$Abitacora=mainModel::actualizar_bitacora($datos['Codigo'],$datos['Hora']);
					if ($Abitacora->rowcount()==1) {
						session_unset();
						session_destroy();
						$respuesta="true";
					} else {
						$respuesta="false";
					}
					
				} else {
					$respuesta="false";
				}
				return $respuesta;
			}
		}