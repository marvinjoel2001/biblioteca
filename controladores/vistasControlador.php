<?php 
	include "./modelos/vistasModelo.php";
	class vistasControlador extends vistasModelo{

		public function obtener_plantilla_controlador(){
			return require_once "./vistas/plantilla.php";
		}
		public function obtener_vistas_controlador(){
			if (isset($_GET["views"])) {
					$ruta=explode("/",$_GET["views"]);
					$respuesta=vistasModelo::obtener_vistas_modelo($ruta[0]);
				}else
					{
						$respuesta="login";
					}
				return $respuesta;
		}
	}
 ?>