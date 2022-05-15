<?php
if($peticionAjax){
	require_once "../core/mainModel.php";
}else{
	require_once "./core/mainModel.php";
}
		
class empresaModelo extends mainModel{

	protected function agregar_empresa_modelo($datos){
		$query=mainModel::conectar()->prepare("INSERT INTO empresa( EmpresaCodigo,EmpresaNombre,EmpresaTelefono,EmpresaEmail,EmpresaDireccion,EmpresaDirector,EmpresaMoneda,EmpresaYear) VALUES (:Codigo,:Nombre,:Telefono,:Email,:Direccion,:Director,:Moneda,:Year)");
		$query->bindParam(":Codigo",$datos['Codigo']);
		$query->bindParam(":Nombre",$datos['Nombre']);
		$query->bindParam(":Telefono",$datos['Telefono']);
		$query->bindParam(":Email",$datos['Email']);
		$query->bindParam(":Direccion",$datos['Direccion']);
		$query->bindParam(":Director",$datos['Director']);
		$query->bindParam(":Moneda",$datos['Moneda']);
		$query->bindParam(":Year",$datos['Year']);
		$query->execute();
		return $query;
	}
	//

	protected function datos_empresa_modelo($tipo,$codigo){
		if($tipo=="Unico"){
			$query=mainModel::conectar()->prepare("SELECT * FROM empresa WHERE id=:Codigo");
			$query->bindParam(":Codigo",$codigo);
		}elseif($tipo=="Conteo"){
			$query=mainModel::conectar()->prepare("SELECT id FROM empresa");
		}elseif($tipo=="Select"){
			$query=mainModel::conectar()->prepare("SELECT EmpresaCodigo,EmpresaNombre FROM empresa ORDER BY EmpresaNombre ASC");
		}
		$query->execute();
		return $query;
	}


	protected function eliminar_empresa_modelo($codigo){
		$query=mainModel::conectar()->prepare("DELETE FROM empresa WHERE EmpresaCodigo=:Codigo");
		$query->bindParam(":Codigo",$codigo);
		$query->execute();
		return $query;
	}

	//Terminar el codigo no actualiza los datos de la empresa falta el controlador
	protected function actualizar_empresa_modelo($datos){
		$query=mainModel::conectar()->prepare("UPDATE empresa SET EmpresaCodigo=:Codigo,EmpresaNombre=:Nombre,EmpresaTelefono=:Telefono,EmpresaEmail=:Email,EmpresaDireccion=:Direccion,EmpresaDirector=:Director,EmpresaMoneda=:Moneda,EmpresaYear=:Year WHERE id=:ID");
		$query->bindParam(":Codigo",$datos['Codigo']);
		$query->bindParam(":Nombre",$datos['Nombre']);
		$query->bindParam(":Telefono",$datos['Telefono']);
		$query->bindParam(":Email",$datos['Email']);
		$query->bindParam(":Direccion",$datos['Direccion']);
		$query->bindParam(":Director",$datos['Director']);
		$query->bindParam(":Moneda",$datos['Moneda']);
		$query->bindParam(":Year",$datos['Year']);
		$query->bindParam(":ID",$datos['ID']);
		$query->execute();
		return $query;
	}
}
