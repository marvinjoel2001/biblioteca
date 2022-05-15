<?php 
	if($_SESSION['tipo_sbp']!="Administrador"){
		echo $lc->forzar_cierre_sesion_controlador();
	}
 ?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Usuarios <small>CLIENTES</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL ?>client/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVO CLIENTE
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL ?>clientlist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE CLIENTES
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL ?>clientsearch/" class="btn btn-primary">
	  			<i class="zmdi zmdi-search"></i> &nbsp; BUSCAR CLIENTE
	  		</a>
	  	</li>
	</ul>
</div>


<?php if(!isset($_SESSION['busqueda_cliente']) && empty($_SESSION['busqueda_cliente'])): ?>
<div class="container-fluid">
	<form class="well FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form="default"  autocomplete="off" enctype="multipart/form-data">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="form-group label-floating">
					<span class="control-label">¿A quién estas buscando?</span>
					<input class="form-control" type="text" name="busqueda_inicial_cliente" required="">
				</div>
			</div>
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-search"></i> &nbsp; Buscar</button>
				</p>
			</div>
		</div>
		<div class="RespuestaAjax"></div>
	</form>
</div>

<?php else: ?>
<div class="container-fluid">
	<form class="well FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form="default"  autocomplete="off" enctype="multipart/form-data">
		<p class="lead text-center">Su última búsqueda  fue <strong><?php echo $_SESSION['busqueda_cliente']; ?></strong></p>
		<div class="row">
			<input type="hidden" name="eliminar_busqueda_cliente" value="destruir">
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-danger btn-raised btn-sm"><i class="zmdi zmdi-delete"></i> &nbsp; Eliminar búsqueda</button>
				</p>
			</div>
		</div>
		<div class="RespuestaAjax"></div>
	</form>
</div>
<!-- Panel listado de busqueda de clientes -->
<div class="container-fluid">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-search"></i> &nbsp; BUSCAR CLIENTE</h3>
		</div>
		<div class="panel-body">
		<?php 
		require_once "./controladores/clienteControlador.php";
		$insCliente= new clienteControlador();

		$pagina= explode("/", $_GET['views']);
		echo $insCliente->paginador_cliente_controlador($pagina[1], 10, $_SESSION['privilegio_sbp'],$_SESSION['busqueda_cliente']);
		 ?>
		</div>
	</div>
</div>
<?php endif; ?>