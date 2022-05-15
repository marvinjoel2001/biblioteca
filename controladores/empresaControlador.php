<?php 
if($peticionAjax){
	require_once "../modelos/empresaModelo.php";
}else{
	require_once "./modelos/empresaModelo.php";
}

class empresaControlador extends empresaModelo{

	public function agregar_empresa_controlador(){
		$codigo=mainModel::limpiar_cadena($_POST['dni-reg']);
		$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
		$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
		$email=mainModel::limpiar_cadena($_POST['email-reg']);
		$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);
		$director=mainModel::limpiar_cadena($_POST['director-reg']);
		$moneda=mainModel::limpiar_cadena($_POST['moneda-reg']);
		$year=mainModel::limpiar_cadena($_POST['year-reg']);

		$consulta1=mainModel::ejecutar_consulta_simple("SELECT EmpresaCodigo FROM empresa WHERE EmpresaCodigo='$codigo'");

		if($consulta1->rowCount()<=0){
			$consulta2=mainModel::ejecutar_consulta_simple("SELECT EmpresaNombre FROM empresa WHERE EmpresaNombre='$nombre'");
			if($consulta2->rowCount()<=0){

				$datosEmpresa=[
					"Codigo"=>$codigo,
					"Nombre"=>$nombre,
					"Telefono"=>$telefono,
					"Email"=>$email,
					"Direccion"=>$direccion,
					"Director"=>$director,
					"Moneda"=>$moneda,
					"Year"=>$year
				];



				$guardarEmpresa=empresaModelo::agregar_empresa_modelo($datosEmpresa);

				if($guardarEmpresa->rowCount()>=1){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Empresa registrada!! ",
						"Texto"=>"Los datos de la empresa se registraron con exito",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio Un Error Inesperado",
						"Texto"=>"No hemos podido guardar los datos de la empresa, por favor intente de nuevo",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio Un Error Inesperado",
					"Texto"=>"El Nombre de la empresa que acaba de ingresar ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
			}
		}else{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio Un Error Inesperado",
				"Texto"=>"El codigo de registro que acaba de ingresar ya se encuentra registrado en el sistema",
				"Tipo"=>"error"
			];
		}
		return mainModel::sweet_alert($alerta);
	}


	public function datos_empresa_controlador($tipo,$codigo){
		$codigo=mainModel::decryption($codigo);
		$tipo=mainModel::limpiar_cadena($tipo);

		return empresaModelo::datos_empresa_modelo($tipo,$codigo);
	}


	public function paginador_empresa_controlador($pagina, $registros, $privilegio){

		$pagina=mainModel::limpiar_cadena($pagina);
		$registros=mainModel::limpiar_cadena($registros);
		$privilegio=mainModel::limpiar_cadena($privilegio);
		$tabla="";

		$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1; 
		$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

		$consulta=" SELECT SQL_CALC_FOUND_ROWS * FROM empresa ORDER BY empresaNombre ASC LIMIT $inicio,$registros";
		$paginaurl="companylist";

		$conexion=mainModel::conectar();

		$datos = $conexion->query($consulta);
		$datos = $datos->fetchAll();

		$total=$conexion->query("SELECT FOUND_ROWS()");
		$total= (int) $total->fetchColumn();

		$Npaginas=ceil($total/$registros);

		$tabla.='
		<div class="table-responsive">
			<table class="table table-hover text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">CÓDIGO DE REGISTRO</th>
						<th class="text-center">NOMBRE</th>
						<th class="text-center">EMAIL</th>
						';
						if ($privilegio<=2) {
						$tabla.='<th class="text-center">ACTUALIZAR</th>
						';	
						}
						if ($privilegio==1) {
						$tabla.='<th class="text-center">ELIMINAR</th>
						';	
						}
						

						
		$tabla.='</tr>
				</thead>
				<tbody>
		';

		if ($total>=1 && $pagina<=$Npaginas) {
			$contador=$inicio+1;
			foreach ($datos as $rows) {
			$tabla.=
					'<tr>
						<td>'.$contador.'</td>
						<td>'.$rows['EmpresaCodigo'].'</td>
						<td>'.$rows['EmpresaNombre'].'</td>
						<td>'.$rows['EmpresaEmail'].'</td>';
						if ($privilegio<=2) {
							$tabla.='
							<td>
								<a href="'.SERVERURL.'companyinfo/'.mainModel::encryption($rows['id']).'/" class="btn btn-success btn-raised btn-xs">
									<i class="zmdi zmdi-refresh"></i>
								</a>
							</td>
							';
						}
						if ($privilegio==1){
							$tabla.='	
							<td>
								<form action="'.SERVERURL.'ajax/empresaAjax.php" method="POST" class="FormularioAjax" data-form="delete" entype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['EmpresaCodigo']).'">
									<input type="hidden" name="privilegio-admin" value="'.mainModel::encryption($privilegio).'">
									<button type="submit" class="btn btn-danger btn-raised btn-xs">
										<i class="zmdi zmdi-delete"></i>
									</button>
									<div class="RespuestaAjax"></div>
								</form>
							</td>
							';
						}
					$tabla.='</tr>';	
				$contador++;
			}
		}else{
			if($total>=1){
				$tabla.='
					<tr>
						<td colspan="6"> 
							<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
								Haga clic aca para recargar el listado
							</a>	
						</td>
					</tr>			
				';
			}else{
			$tabla.='<tr>
					<td colspan="6"> No hay registros en el sistema </td>
					</tr>		
			';
			}
		}
		$tabla.='</tbody></table></div>';
		if ($total>=1 && $pagina<=$Npaginas){
			$tabla.='<nav class="text-center">
				<ul class="pagination pagination-sm">';
				if ($pagina==1) {
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>';
				} else {
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina-1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>';
				}
				for ($i=1; $i <=$Npaginas ; $i++) { 
					if ($pagina==$i) {
						$tabla.='<li class="active"><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					} else {
						$tabla.='<li ><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					}	
				}
				if ($pagina==$Npaginas) {
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>';
				} else {
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina+1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>';
				}
			$tabla.='</ul></nav>';	
		}
		return $tabla;
	}


	
	public function eliminar_empresa_controlador(){
		$codigo=mainModel::decryption($_POST['codigo-del']);
		$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);	

		$codigo=mainModel::limpiar_cadena($codigo);
		$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

		if($adminPrivilegio==1){
			$consulta1=mainModel::ejecutar_consulta_simple("SELECT EmpresaCodigo FROM libro WHERE EmpresaCodigo='$codigo'");
			if($consulta1->rowCount()<=0){
				$DelEmpresa=empresaModelo::eliminar_empresa_modelo($codigo);
				if($DelEmpresa->rowCount()==1){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Empresa Eliminada ",
						"Texto"=>"La empresa fue eliminada con exito",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado ",
						"Texto"=>"Lo sentimos no hemos podido elimar la empresa",
						"Tipo"=>"error"
					];	
				}

			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado ",
					"Texto"=>"Lo sentimos no podemos eliminar la empresa porque tiene libros asociados",
					"Tipo"=>"error"
				];
			}
		}else{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado!! ",
				"Texto"=>"Tu no tienes los permisos necesarios para eliminar registros del sistema",
				"Tipo"=>"error"
			];
		}
		return mainModel::sweet_alert($alerta);
	}
}