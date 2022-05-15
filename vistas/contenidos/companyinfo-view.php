<?php 
	if($_SESSION['tipo_sbp']!="Administrador" || $_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2){
		echo $lc->forzar_cierre_sesion_controlador();
	}
 ?>
<!-- Content page -->
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-balance zmdi-hc-fw"></i> Administración <small>EMPRESA</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>company/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVA EMPRESA
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>companylist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE EMPRESAS
	  		</a>
	  	</li>
	</ul>
</div>
<?php 
	require_once "./controladores/empresaControlador.php";
 	
 	$iEm= new empresaControlador();

 	$datos=explode("/", $_GET['views']);

 	$filesEm=$iEm->datos_empresa_controlador("Unico",$datos[1]);

 	if($filesEm->rowCount()==1){
 		$campos=$filesEm->fetch();
 	//if(true){ Cambiar por este codigo para registrar varias empresas
 	//y que en el option nos muestre todos
 ?>
<!-- panel datos de la empresa -->
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> &nbsp; ACTUALIZAR DATOS DE LA EMPRESA</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo SERVERURL;?>ajax/empresaAjax.php" method="POST" data-form="update" class="FormularioAjax"  autocomplete="off" enctype="multipart/form-data">
				<input type="hidden" name="codigo" value="<?php echo $datos[1];?>">
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-assignment"></i> &nbsp; Datos básicos</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">DNI/CÓDIGO/NÚMERO DE REGISTRO *</label>
								  	<input pattern="[0-9-]{1,30}" class="form-control" type="text" name="dni-up" required="" maxlength="30" value="<?php echo $campos['EmpresaCodigo']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Nombre de la empresa *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" type="text" name="nombre-up" required="" maxlength="40" value="<?php echo $campos['EmpresaNombre']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Teléfono</label>
								  	<input pattern="[0-9+]{1,15}" class="form-control" type="text" name="telefono-up" maxlength="15" value="<?php echo $campos['EmpresaTelefono']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">E-mail</label>
								  	<input class="form-control" type="email" name="email-up" maxlength="50" value="<?php echo $campos['EmpresaEmail']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12">
								<div class="form-group label-floating">
								  	<label class="control-label">Dirección</label>
								  	<input class="form-control" type="text" name="direccion-up" maxlength="170"value="<?php echo $campos['EmpresaDireccion']; ?>" >
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Otros datos</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Nombre del gerente o director *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,50}" class="form-control" type="text" name="director-up" required="" maxlength="50" value="<?php echo $campos['EmpresaDirector']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Símbolo de moneda *</label>
								  	<input class="form-control" type="text" name="moneda-up" required="" maxlength="1" value="<?php echo $campos['EmpresaMoneda']; ?>">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Año *</label>
								  	<input pattern="[0-9]{4,4}" class="form-control" type="text" name="year-up" required="" maxlength="4" value="<?php echo $campos['EmpresaYear']; ?>">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
			    <p class="text-center" style="margin-top: 20px;">
			    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> Actualizar</button>
			    </p>
			    <div class="RespuestaAjax"></div>
		    </form>
		</div>
	</div>
</div>
<?php }else{?>
<div class="alert alert-dismissible alert-warning text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i>
	<h4>¡Lo sentimos!</h4>
	<p>No podemos mostrar información de la empresa en este momento </p>
</div>	
 <?php } ?>