<?php
	session_start();
	ob_start();
	if( !isset($_SESSION['usuario']) ){
	header('Location: ingresar');
	exit();
	}
	$titulo = "REGISTRAR PROVEEDOR";
	include('config/conexion.php');
	include('include/header.php');
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		/*
		Filtro anti-XSS
		$usuario = htmlspecialchars(mysqli_real_escape_string($enlace, $usuario));
		$contrasena = htmlspecialchars(mysqli_real_escape_string($enlace, $contrasena));
		*/

		//###### FILTRO anti-XSS
		$nombre = trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Nombres']) ) ) );
		$ape_pat = trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Ape_pat']) ) ) );
		$ape_mat = trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Ape_mat']) ) ) );
      	$empresa = trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Empresa']) ) ) );
		$correo = trim(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Correo']) ) );
		$telefono = trim(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Telefono']) ) );

		$sql_Check_Mail = "SELECT * FROM proveedores WHERE correo = 'htmlentities($correo)'; ";
		
		$result = mysqli_query($enlace, $sql_Check_Mail);
      //la siguiente linea funciona igual a la que continúa después
      //$count = $result->num_rows;
		$count = mysqli_num_rows($result);


		if($count > 0){
			echo '<div class="alert alert-danger"><strong>Error!</strong> El correo ya existe...</div>';
		}else {
			if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
				/*
				if ($tipo == "Administrador"){
					$tip = 1;
				}elseif($tipo == "Usuario") {
					$tip = 2;
				}
				*/
				$sql_insert = "INSERT INTO proveedores(nombres,ape_paterno,ape_materno,empresa,correo,telefono) 
												VALUES('$nombre','$ape_pat','$ape_mat','$empresa','$correo','$telefono');";

				//DEVUELVE TRUE SI LA CONSULTA CON INSERT SE REALIZA CON EXITO
				if (mysqli_query($enlace,$sql_insert) === TRUE) {
					echo ' 	<script>
								alert("Proveedor registrado con éxito");
								location.href="ver_proveedores";
							</script>';
				}
				else {
					echo ' 	<script>
								alert("Proveedor No Registrado, intentalo nuevamente");
							</script>';
				}
			}//FIN DEL if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
			else{
				echo '<div class="alert alert-danger"><strong>Error!</strong> No es un correo...</div>';
			}
			
		}// fin del if count

   }//Fin del if SERVER
?>

    <div class="container">
	    <?php
	    	if(isset($_SESSION['flash'])){
	    	if($_SESSION['flash']=='Error'){
	        	echo '<span class="new badge red" data-badge-caption="custom caption">4</span>';
	        }
	        unset($_SESSION['flash']);
	    }
	    ?>
	    <div class="row">
	    	
        <?php if(isset($_SESSION['usuario'])) { 
					if($_SESSION['tipo'] === "Administrador") { ?>

			<div class="col s3">
			  
			</div>
			<div class="col 16 s6 center">
				<h2 class="header orange-text">Registrar Nuevo Proveedor</h2>

				<form action="" method="POST">

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Nombres" id="Nombres" class="validate" required>
					  <label for="Nombres" data-error="Error" data-success="Correcto">Nombre(s)</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Ape_pat" id="Ape_pat" class="validate" required>
					  <label for="Ape_pat" data-error="Error" data-success="Correcto">Apellido Materno</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Ape_mat" id="Ape_mat" class="validate" required>
					  <label for="Ape_mat" data-error="Error" data-success="Correcto">Apellido Materno</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">https</i>
					  <input type="text" name="Empresa" id="Empresa" class="validate" required>
					  <label for="Empresa" data-error="Error" data-success="Correcto">Empresa</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">email</i>
					  <input type="text" name="Correo" id="Correo" class="validate" required>
					  <label for="Correo" data-error="Error" data-success="Correcto">Correo</label>
					</div>
					
					<div class="input-field">
					  <i class="material-icons prefix">email</i>
					  <input type="text" name="Telefono" id="Telefono" class="validate" required>
					  <label for="Telefono" data-error="Error" data-success="Correcto">Teléfono</label>
					</div>

					<br>
					<br>
					<button class="btn waves-effect waves-light" type="submit">Agregar
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
    					<p>ERROR...</p>
  					</div>
				</div>';
		} ?>

      </div>

    </div>
<?php include('include/footer.php') ?>