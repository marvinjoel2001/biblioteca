<?php
if($peticionAjax){
			require_once "../core/mainModel.php";
		}else{
			require_once "./core/mainModel.php";
		}
		
	class administradorModelo extends mainModel{
		protected function agregar_administrador_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO admin(AdminDNI,AdminNombre,AdminApellido,AdminTelefono,AdminDireccion,CuentaCodigo)VALUES(:DNI,:Nombre,:Apellido,:Telefono,:Direccion,:Codigo)");
			$sql->bindParam(":DNI",$datos['DNI']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Apellido",$datos['Apellido']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Direccion",$datos['Direccion']);
			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->execute();
			return $sql;
		}

		protected function eliminar_administrador_modelo($codigo){
			$query=mainModel::conectar()->prepare("DELETE FROM admin WHERE CuentaCodigo=:Codigo");
			$query->bindParam(":Codigo",$codigo);
			$query->execute();
			return $query;
		}





		protected function datos_administrador_modelo($tipo,$codigo){
			if($tipo=="Unico"){
				$query=mainModel::conectar()->prepare("SELECT * FROM admin WHERE CuentaCodigo=:Codigo");
				$query->bindParam(":Codigo",$codigo);
			}elseif($tipo=="Conteo"){
				$query=mainModel::conectar()->prepare("SELECT * FROM admin WHERE id!='1'");
			}
			$query->execute();
			return $query;
		}


		protected function actualizar_administrador_modelo($datos){
			$query=mainModel::conectar()->prepare("UPDATE admin SET AdminDNI=:DNI,AdminNombre=:Nombre,AdminApellido=:Apellido,AdminTelefono=:Telefono,AdminDireccion=:Direccion WHERE CuentaCodigo=:Codigo");
			$query->bindParam(":DNI",$datos['DNI']);
			$query->bindParam(":Nombre",$datos['Nombre']);
			$query->bindParam(":Apellido",$datos['Apellido']);
			$query->bindParam(":Telefono",$datos['Telefono']);
			$query->bindParam(":Direccion",$datos['Direccion']);
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->execute();
			return $query;
		}
	}











