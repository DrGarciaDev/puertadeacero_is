<?php
session_start();
ob_start();
if(!isset($_SESSION['usuario'])){
  header('Location: ingresar');
  exit();
}

include('config/conexion.php');
include('include/header.php');
if(empty($_GET['id'])){
  header('Location: ver_proveedores');
  exit();
} 

if(isset($_SESSION['usuario'])) { 
	if($_SESSION['tipo'] === "Administrador") {
		
		$query = "DELETE FROM proveedores WHERE id = ".$_GET['id'];
		$resultado = mysqli_query($enlace,$query);
		if($resultado){
			$_SESSION['flash'] = "ProE";
	  		echo '<script>
					location.href="ver_proveedores";
				</script>';
		}
	}
}
?>