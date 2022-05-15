<?php 
	if($peticionAjax){
				require_once "../modelos/administradorModelo.php";
			}else{
				require_once "./modelos/administradorModelo.php";
			}
class administradorControlador extends administradorModelo{
	//Controlador para agragar administrador
	public function agregar_administrador_controlador(){
		$dni=mainModel::limpiar_cadena($_POST['dni-reg']);
		$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
		$apellido=mainModel::limpiar_cadena($_POST['apellido-reg']);
		$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
		$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);
		$usuario=mainModel::limpiar_cadena($_POST['usuario-reg']);
		$password1=mainModel::limpiar_cadena($_POST['password1-reg']);
		$password2=mainModel::limpiar_cadena($_POST['password2-reg']);
		$email=mainModel::limpiar_cadena($_POST['email-reg']);
		$genero=mainModel::limpiar_cadena($_POST['optionsGenero']);

		$privilegio=mainModel::decryption($_POST['optionsPrivilegio']);
		$privilegio=mainModel::limpiar_cadena($privilegio);

		if($genero=="Masculino"){
			$foto="Male3Avatar.png";
		}else{
			$foto="Female3Avatar.png";
		}
		if ($privilegio<1 || $privilegio>3) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio Un Error Inesperado",
					"Texto"=>"El nivel de privilegio que intenta asignar es incorrecto",
					"Tipo"=>"error"
				];
		} else {
				if($password1 != $password2){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio Un Error Inesperado",
					"Texto"=>"Las Contraseñas Que Acabas De Ingresar No Coinciden, Por Favor Intente De Nuevamente",
					"Tipo"=>"error"
				];
				}else{
					$consulta1=mainModel::ejecutar_consulta_simple("SELECT AdminDNI FROM admin WHERE AdminDNI='$dni'");
					if($consulta1->rowCount()>=1){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrio Un Error Inesperado",
							"Texto"=>"El DNI que intenta registrar ya se encuentra registrado en el sistema",
							"Tipo"=>"error"
							];
					}else{
						if($email!=""){
							$consulta2=mainModel::ejecutar_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$email'");
							$ec=$consulta2->rowCount();
						}else{
							$ec=0;
						}
						if($ec>=1){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrio Un Error Inesperado",
								"Texto"=>"El E-mail que intenta registrar ya se encuentra registrado en el sistema",
								"Tipo"=>"error"
							];
						}else{
							$consulta3=mainModel::ejecutar_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
							if($consulta3->rowCount()>=1){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrio Un Error Inesperado",
								"Texto"=>"El Usuario que intenta registrar ya se encuentra registrado en el sistema",
								"Tipo"=>"error"
								];
							}else{
							$consulta4=mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta");
							$numero=($consulta4->rowCount())+1;

							$codigo=mainModel::generar_codigo_aleatorio("AC",7,$numero);
							$clave=mainModel::encryption($password1);
								$dataAC=[
									"Codigo"=>$codigo,
									"Privilegio"=>$privilegio,
									"Usuario"=>$usuario,
									"Clave"=>$clave,
									"Email"=>$email,
									"Estado"=>"Activo",
									"Tipo"=>"Administrador",
									"Genero"=>$genero,
									"Foto"=>$foto
								];
							$guardarCuenta=mainModel::agregar_cuenta($dataAC);

							if($guardarCuenta->rowCount()>=1) {
								$dataAD=[
									"DNI"=>$dni,
									"Nombre"=>$nombre,
									"Apellido"=>$apellido,
									"Telefono"=>$telefono,
									"Direccion"=>$direccion,
									"Codigo"=>$codigo
								];
								$guardarAdmin=administradorModelo::agregar_administrador_modelo($dataAD);
								if($guardarAdmin->rowCount()>=1){
									$alerta=[
										"Alerta"=>"limpiar",
										"Titulo"=>"Administrador Registrado",
										"Texto"=>"El administrador se registro con exito en el sistema",
										"Tipo"=>"success"
									];
								}else{
									mainModel::eliminar_cuenta($codigo);
									$alerta=[
										"Alerta"=>"simple",
										"Titulo"=>"Ocurrio Un Error Inesperado",
										"Texto"=>"No hemos podido registrar el administrador",
										"Tipo"=>"error"
									];
								}
							}else{
								$alerta=[
									"Alerta"=>"simple",
									"Titulo"=>"Ocurrio Un Error Inesperado",
									"Texto"=>"No hemos podido registrar el administrador",
									"Tipo"=>"error"
								];
							}	
						}	
					}	
				}
			}		
		}
		return mainModel::sweet_alert($alerta);
	}
	
	//Controlador para paginar administrador
	public function paginador_administrador_controlador($pagina, $registros, $privilegio, $codigo,$busqueda){
		$pagina=mainModel::limpiar_cadena($pagina);
		$registros=mainModel::limpiar_cadena($registros);
		$privilegio=mainModel::limpiar_cadena($privilegio);	
		$codigo=mainModel::limpiar_cadena($codigo);
		$busqueda=mainModel::limpiar_cadena($busqueda);
		$tabla="";

		$pagina= (isset($pagina) && $pagina>0) ? (int) $pagina : 1; 
		$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

		if(isset($busqueda) && $busqueda!=""){
			$consulta=" SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE ((CuentaCodigo!='$codigo' AND id!='1') AND (AdminNombre LIKE '%$busqueda%' OR AdminApellido LIKE '%$busqueda%' OR AdminDNI LIKE '%$busqueda%' OR AdminTelefono LIKE '%$busqueda%')) ORDER BY AdminNombre ASC LIMIT $inicio,$registros";
			$paginaurl="adminsearch";
		}else{
			$consulta=" SELECT SQL_CALC_FOUND_ROWS * FROM admin WHERE CuentaCodigo!='$codigo' AND id!='1' ORDER BY AdminNombre ASC LIMIT $inicio,$registros";
			$paginaurl="adminlist";
		}
		

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
						<th class="text-center">DNI</th>
						<th class="text-center">NOMBRES</th>
						<th class="text-center">APELLIDOS</th>
						<th class="text-center">TELÉFONO</th>';
						if ($privilegio<=2) {
						$tabla.='
						<th class="text-center">A. CUENTA</th>
						<th class="text-center">A. DATOS</th>
						';	
						}
						if ($privilegio==1) {
						$tabla.='
							<th class="text-center">ELIMINAR</th>
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
						<td>'.$rows['AdminDNI'].'</td>
						<td>'.$rows['AdminNombre'].'</td>
						<td>'.$rows['AdminApellido'].'</td>
						<td>'.$rows['AdminTelefono'].'</td>';
						
						if ($privilegio<=2) {
							$tabla.='
							<td>
								<a href="'.SERVERURL.'myaccount/admin/'.mainModel::encryption($rows['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
									<i class="zmdi zmdi-refresh"></i>
								</a>
							</td>
							<td>
								<a href="'.SERVERURL.'mydata/admin/'.mainModel::encryption($rows['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
									<i class="zmdi zmdi-refresh"></i>
								</a>
							</td>
							';
						}
						if ($privilegio==1){
							$tabla.='	
							<td>
								<form action="'.SERVERURL.'ajax/administradorAjax.php" method="POST" class="FormularioAjax" data-form="delete" entype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['CuentaCodigo']).'">
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
						<td colspan="5"> 
							<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
								Haga clic aca para recargar el listado
							</a>	
						</td>
					</tr>			
				';
			}else{
			$tabla.='<tr>
					<td colspan="5"> No hay registros en el sistema </td>
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

	public function eliminar_administrador_controlador(){
		$codigo=mainModel::decryption($_POST['codigo-del']);
		$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

		$codigo=mainModel::limpiar_cadena($codigo);
		$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

		if($adminPrivilegio==1){
			$query1=mainModel::ejecutar_consulta_simple("SELECT id FROM admin WHERE CuentaCodigo='$codigo'");
			$datosAdmin=$query1->fetch();
			if($datosAdmin['id']!=1){
				$DelAdmin=administradorModelo::eliminar_administrador_modelo($codigo);
				mainModel::eliminar_bitacora($codigo);
				if($DelAdmin->rowCount()>=1){
					$DelCuenta=mainModel::eliminar_cuenta($codigo);
					if($DelCuenta->rowCount()>=1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"Administrador Eliminado",
							"Texto"=>"El administrador fue eliminado con exito del sistema",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrio Un Error Inesperado",
							"Texto"=>"No podemos eliminar esta cuenta en este momento",
							"Tipo"=>"error"
						];	
					}
					
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio Un Error Inesperado",
						"Texto"=>"No podemos eliminar este administrador en este momento",
						"Tipo"=>"error"
					];
				}
				
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio Un Error Inesperado",
					"Texto"=>"No podemos eliminar el administrador principal del sistema",
					"Tipo"=>"error"
				];	
			}
			
		}else{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio Un Error Inesperado",
				"Texto"=>"Tu no tienes los permisos necesarios para realizar esta operacion",
				"Tipo"=>"error"
			];	
		}
		return mainModel::sweet_alert($alerta);
		
	}





	public function datos_administrador_controlador($tipo,$codigo){
		$codigo=mainModel::decryption($codigo);
		$tipo=mainModel::limpiar_cadena($tipo);

		return administradorModelo::datos_administrador_modelo($tipo,$codigo);
	}


	public function actualizar_administrador_controlador(){
		$cuenta=mainModel::decryption($_POST['cuenta_up']);

		$dni=mainModel::limpiar_cadena($_POST['dni-up']);
		$nombre=mainModel::limpiar_cadena($_POST['nombre-up']);
		$apellido=mainModel::limpiar_cadena($_POST['apellido-up']);
		$telefono=mainModel::limpiar_cadena($_POST['telefono-up']);
		$direccion=mainModel::limpiar_cadena($_POST['direccion-up']);

		$query1=mainModel::ejecutar_consulta_simple("SELECT * FROM admin WHERE CuentaCodigo='$cuenta'");
		$DatosAdmin=$query1->fetch();

		if($dni!=$DatosAdmin['AdminDNI']){
			$consulta1=mainModel::ejecutar_consulta_simple("SELECT AdminDNI FROM admin WHERE AdminDNI='$dni'");
			if($consulta1->rowCount()==1){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio Un Error Inesperado",
					"Texto"=>"El DNI que acaba de ingesar ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];	
			
				return mainModel::sweet_alert($alerta);
				exit();
			}
		}

		$dataAd=[
			"DNI"=>$dni,
			"Nombre"=>$nombre,
			"Apellido"=>$apellido,
			"Telefono"=>$telefono,
			"Direccion"=>$direccion,
			"Codigo"=>$cuenta
		];

		if(administradorModelo::actualizar_administrador_modelo($dataAd)){
			$alerta=[
				"Alerta"=>"recargar",
				"Titulo"=>"Datos Actualizados!",
				"Texto"=>"Tus datos han sido actualizados con exito!!",
				"Tipo"=>"success"
			];	
		}else{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio Un Error Inesperado",
				"Texto"=>"No hemos podido actualizar tus datos porfavor intente nuevamente",
				"Tipo"=>"error"
			];	
		}
		return mainModel::sweet_alert($alerta);

	}

		
}

