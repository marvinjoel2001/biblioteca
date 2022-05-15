<!-- Content page -->
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-settings zmdi-hc-fw"></i> MI CUENTA</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<?php
	$datos=explode("/", $_GET['views']);

	if(isset($datos[1]) && ($datos[1]=="admin" || $datos[1]=="user")):

		require_once "./controladores/cuentaControlador.php";
		$classCuenta = new cuentaControlador();

		$filesC=$classCuenta->datos_cuenta_controlador($datos[2],$datos[1]);

		if($filesC->rowCount()==1){
			$campos=$filesC->fetch();
?>
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> &nbsp; MI CUENTA</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo SERVERURL; ?>ajax/cuentaAjax.php" method="POST" data-form="update" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data">
		    	<fieldset>
		    		<?php 
		    			if($_SESSION['codigo_cuenta_sbp']!=$campos['CuentaCodigo']){
		    				if($_SESSION['tipo_sbp']!="Administrador" || $_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2){
		    					echo $lc->forzar_cierre_sesion_controlador();
		    				}else{
		    					echo '<input type="hidden" name="privilegio-up" value="verdadero">';
		    				}
		    			}
		    		 ?>
		    		 <input type="hidden" name="CodigoCuenta-up" value="<?php echo $datos[2];?>">
		    		 <input type="hidden" name="tipoCuenta-up" value="<?php echo $lc->encryption($datos[1]);?>">
		    		<legend><i class="zmdi zmdi-key"></i> &nbsp; Datos de la cuenta</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Nombre de usuario</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]{1,15}" class="form-control" type="text" name="usuario-up" value="<?php echo $campos['CuentaUsuario']; ?>" required="" maxlength="15">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">E-mail</label>
								  	<input class="form-control" type="email" name="email-up" value="<?php echo $campos['CuentaEmail']; ?>" maxlength="50">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group">
									<label class="control-label">Genero</label>
									<div class="radio radio-primary">
										<label>
											<input type="radio" name="optionsGenero-up" <?php if($campos['CuentaGenero']=="Masculino"){ echo 'checked=""';} ?> value="Masculino" >
											<i class="zmdi zmdi-male-alt"></i> &nbsp; Masculino
										</label>
									</div>
									<div class="radio radio-primary">
										<label>
											<input type="radio" name="optionsGenero-up"<?php if($campos['CuentaGenero']=="Femenino"){ echo 'checked=""';} ?> value="Femenino" >
											<i class="zmdi zmdi-female"></i> &nbsp; Femenino
										</label>
									</div>
								</div>
		    				</div>
		    				<?php 
		    				if($_SESSION['tipo_sbp']=="Administrador" && $_SESSION['privilegio_sbp']==1 && $campos['id']!=1):
		    				 ?>
							<div class="col-xs-12 col-sm-6">
								<div class="form-group">
									<label class="control-label">Estado de la cuenta</label>
									<div class="radio radio-primary">
										<label>
											<input type="radio" name="optionsEstado-up" <?php if($campos['CuentaEstado']=="Activo"){ echo 'checked=""';} ?> value="Activo" >
											<i class="zmdi zmdi-lock-open"></i> &nbsp; Activo
										</label>
									</div>
									<div class="radio radio-primary">
										<label>
											<input type="radio" name="optionsEstado-up"<?php if($campos['CuentaEstado']=="Deshabilitado"){ echo 'checked=""';} ?> value="Deshabilitado"  >
											<i class="zmdi zmdi-lock"></i> &nbsp; Deshabilitado
										</label>
									</div>
								</div>
		    				</div>
		    				<?php endif; ?>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-lock"></i> &nbsp; Actualizar Contraseña</legend>
		    		<p>
		    			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo minima cupiditate tempore nobis. Dolor, blanditiis, mollitia. Alias fuga fugiat molestias debitis odit, voluptatibus explicabo quia sequi doloremque numquam dignissimos quis.
		    		</p>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Nueva contraseña *</label>
								  	<input class="form-control" type="password" name="newPassword1-up" maxlength="50">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Repita la nueva contraseña *</label>
								  	<input class="form-control" type="password" name="newPassword2-up" maxlength="50">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
		    	<?php 
    				if($_SESSION['tipo_sbp']=="Administrador" && $_SESSION['privilegio_sbp']==1 && $campos['id']!=1 && $campos['CuentaTipo']=="Administrador" && $datos[1]=="admin"):
    			 ?>
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-star"></i> &nbsp; Nivel de privilegios</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
					    		<p class="text-left">
			                        <div class="label label-success">Nivel 1</div> Control total del sistema
			                    </p>
			                    <p class="text-left">
			                        <div class="label label-primary">Nivel 2</div> Permiso para registro y actualización
			                    </p>
			                    <p class="text-left">
			                        <div class="label label-info">Nivel 3</div> Permiso para registro
			                    </p>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="radio radio-primary">
									<label>
										<input type="radio" name="optionsPrivilegio-up" value="<?php echo $lc->encryption(1); ?>" <?php if($campos['CuentaPrivilegio']==1){ echo 'checked=""'; } ?> >
										<i class="zmdi zmdi-star"></i> &nbsp; Nivel 1
									</label>
								</div>
								<div class="radio radio-primary">
									<label>
										<input type="radio" name="optionsPrivilegio-up"value="<?php echo $lc->encryption(2); ?>" <?php if($campos['CuentaPrivilegio']==2){ echo 'checked=""'; } ?> >
										<i class="zmdi zmdi-star"></i> &nbsp; Nivel 2
									</label>
								</div>
								<div class="radio radio-primary">
									<label>
										<input type="radio" name="optionsPrivilegio-up" value="<?php echo $lc->encryption(3); ?>" <?php if($campos['CuentaPrivilegio']==3){ echo 'checked=""'; } ?>>
										<i class="zmdi zmdi-star"></i> &nbsp; Nivel 3
									</label>
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
				<br>
				<?php endif; ?>
				<fieldset>
		    		<legend><i class="zmdi zmdi-account-circle"></i> &nbsp; Datos de la cuenta</legend>
		    		<p>
						Para poder actualizar los datos de la cuenta por favor ingrese su nombre de usuario y contraseña.
		    		</p>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Nombre de usuario</label>
								  	<input class="form-control" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]{1,15}" type="text" name="user-log" maxlength="15" required="" >
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Contraseña</label>
								  	<input class="form-control" type="password" name="password-log" maxlength="50" required="">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
			    <p class="text-center" style="margin-top: 20px;">
			    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> Actualizar</button>
			    </p>
				<div class="RespuestaAjax"></div>
		    </form>
		</div>
	</div>
</div>

<?php   }else{  ?>
<div class="alert alert-dismissible alert-warning text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i>
	<h4>¡Lo sentimos!</h4>
	<p>No podemos mostrar la información de la cuenta debido a un error</p>
</div>
<?php
		}
	else:
?>
<div class="alert alert-dismissible alert-warning text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i>
	<h4>¡Lo sentimos!</h4>
	<p>Ocurrió un error inesperado</p>
</div>
<?php endif; ?>