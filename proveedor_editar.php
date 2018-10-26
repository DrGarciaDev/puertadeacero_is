<?php
session_start();
ob_start();
if( !isset($_SESSION['usuario']) ){
  header('Location: ingresar');
  exit();
}

$titulo = "EDITAR PROVEEDOR";

include('config/conexion.php');
include ('include/header.php');

$id_recibido = htmlspecialchars( mysqli_real_escape_string($enlace, $_GET['id']) );

$query = "SELECT * FROM proveedores WHERE id = ".$id_recibido;

$resultado = mysqli_query($enlace, $query);

$proveedor = $resultado->fetch_object();

if (empty($proveedor)) {
	exit();
}

if (!empty($_POST)) {

	$id 		= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Id']) );
	$nombres 	= trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Nombres']) ) ) );
	$ape_pat 	= trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Ape_pat']) ) ) );
	$ape_mat 	= trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Ape_mat']) ) ) );
	$empresa 	= trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Empresa']) ) ) );
	$correo 	= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Correo']) );
	$telefono 	= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Telefono']) );
	
	if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

		$query = "UPDATE proveedores SET nombres = '".$nombres."', ape_paterno = '".$ape_pat."', ape_materno = '".$ape_mat."', empresa = '".$empresa."', correo = '".$correo."', telefono = '".$telefono."' WHERE id = ".$id ;
		
		$resultado = mysqli_query($enlace, $query);
		
		if ($resultado) {
			echo ' 	<script>
						alert("Proveedor editado con éxito");
						location.href="ver_proveedores";
					</script>';
		}
		else {
			echo ' 	<script>
						alert("Proveedor No editado, intentalo nuevamente");
					</script>';
		}
	}
	else{
		echo ' <script>alert("ingrese correo valido, vuelve a intentarlo");</script> ';
	}
}
?>
    <div class="container">
	    <div class="row">
	    	
        <?php if(isset($_SESSION['usuario'])) { 
					if($_SESSION['tipo'] === "Administrador") { ?>

			<div class="col s3">
			  
			</div>
			<div class="col 16 s6 center">
				<h2 class="header orange-text">Editar proveedor <?php if(isset($proveedor->nombres)) echo $proveedor->nombres; ?></h2>

				<p class="teal-text">Puedes modificar cualquier campo...</p>
				<form action="" method="POST">

					<input type="hidden" name="Id" id="Id" value="<?php if(isset($proveedor->id)) echo $proveedor->id; ?>">

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Nombres" id="Nombres" class="validate" value="<?php if(isset($proveedor->nombres)) echo $proveedor->nombres; ?>" required>
					  <label for="Nombres" data-error="Error" data-success="Correcto">Nombre(s)</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Ape_pat" id="Ape_pat" class="validate" value="<?php if(isset($proveedor->ape_paterno)) echo $proveedor->ape_paterno; ?>" required>
					  <label for="Ape_pat" data-error="Error" data-success="Correcto">Apellido paterno</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Ape_mat" id="Ape_mat" class="validate" value="<?php if(isset($proveedor->ape_materno)) echo $proveedor->ape_materno; ?>" required>
					  <label for="Ape_mat" data-error="Error" data-success="Correcto">Apellido materno</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">email</i>
					  <input type="text" name="Empresa" id="Empresa" class="validate" value="<?php if(isset($proveedor->empresa)) echo $proveedor->empresa; ?>" required>
					  <label for="Empresa" data-error="Error" data-success="Correcto">Empresa</label>
					</div>	

					<div class="input-field">
					  <i class="material-icons prefix">email</i>
					  <input type="text" name="Correo" id="Correo" class="validate" value="<?php if(isset($proveedor->correo)) echo $proveedor->correo; ?>" required>
					  <label for="Correo" data-error="Error" data-success="Correcto">Correo</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">email</i>
					  <input type="text" name="Telefono" id="Telefono" class="validate" value="<?php if(isset($proveedor->telefono)) echo $proveedor->telefono; ?>" required>
					  <label for="Telefono" data-error="Error" data-success="Correcto">Telefono</label>
					</div>					

					<br>
					<br>
					<button class="btn waves-effect waves-light" type="submit">Actualizar
						<i class="material-icons right">send</i>
					</button>
					<a href="ver_proveedores" class="btn waves-effect waves-light red" role="button">Cancelar</a>
					<br>
				</form>
			</div>
			<div class="col s3">
			  
			</div>


		<?php 	}else{
					echo '<div class="card red center">
							<div class="card-content white-text">
		    					<p>ERROR NO tienes los permisos necesarios...</p>
		  					</div>
						</div>';
				}
		}else{
			echo '<div class="card red lighten-5 center">
					<div class="card-content red-text">
    					<p>Error, inicia session...</p>
  					</div>
				</div>';
		} ?>

      </div>

    </div>
<?php include('include/footer.php') ?>