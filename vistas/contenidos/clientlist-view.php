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

<?php 
	require_once "./controladores/clienteControlador.php";
	$insCliente= new clienteControlador();

 ?>
<!-- Panel listado de clientes -->
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE CLIENTES</h3>
		</div>
		<div class="panel-body">
				<?php 
			$pagina= explode("/", $_GET['views']);
			echo $insCliente->paginador_cliente_controlador($pagina[1], 10, $_SESSION['privilegio_sbp'],"");
			 ?>		
		</div>
	</div>
</div>