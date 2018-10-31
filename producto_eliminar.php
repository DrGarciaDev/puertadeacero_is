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
  header('Location: ver_productos');
  exit();
} 

if(isset($_SESSION['usuario'])) { 
	if($_SESSION['tipo'] === "Administrador") {
		$id = htmlspecialchars( mysqli_real_escape_string($enlace, $_GET['id']) );

		$query = "DELETE FROM productos WHERE codigo = ".$id;
		$resultado = mysqli_query($enlace,$query);
		if($resultado){
			$_SESSION['flash'] = "ProdE";
	  		echo '<script>
					location.href="ver_productos";
				</script>';
		}
		else {
			echo "<script>alert('Producto no eliminado...');</script>";
		}
	}
	else {
		echo "<script>alert('No tienes los permisos...');</script>";
	}
}
?>