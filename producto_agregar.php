<?php
	session_start();
	ob_start();
	if( !isset($_SESSION['usuario']) ){
	header('Location: ingresar');
	exit();
	}
	$titulo = "REGISTRAR PRODUCTO";
	include('config/conexion.php');
	include('include/header.php');
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		/*
		Filtro anti-XSS
		$usuario = htmlspecialchars(mysqli_real_escape_string($enlace, $usuario));
		$contrasena = htmlspecialchars(mysqli_real_escape_string($enlace, $contrasena));
		*/

		//###### FILTRO anti-XSS
		$descripcion = trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Descripcion']) ) ) );
		$costo = htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Costo']) );
		$existencia = htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Existencia']) );
		$proveedor = htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Proveedor']) );

		/*
		if ($tipo == "Administrador"){
			$tip = 1;
		}elseif($tipo == "Usuario") {
			$tip = 2;
		}
		*/
		$sql_insert = "INSERT INTO productos(descripcion, costo, existencia, usuario_id, proveedor_id) VALUES('$descripcion', $costo, $existencia, ".$_SESSION['usuario'].", $proveedor);";

		//DEVUELVE TRUE SI LA CONSULTA CON INSERT SE REALIZA CON EXITO
		if (mysqli_query($enlace, $sql_insert) === TRUE) {
			echo ' 	<script>
						alert("Producto registrado con éxito");
						location.href="ver_productos";
					</script>';
		}
		else {
			echo ' 	<script>
						alert("Producto No registrado, intentalo nuevamente");
					</script>';
		}

   }//Fin del if SERVER
?>
<script>
	function habilita_boton() {
		//alert('Elige un usuario valido...');
		document.getElementById('actualizador').disabled = false;
	}
</script>

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
				<h2 class="header orange-text">Registrar Nuevo Producto</h2>

				<form action="" method="POST">
					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Descripcion" id="Descripcion" class="validate" required>
					  <label for="Descripcion" data-error="Error" data-success="Correcto">Descripción</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Costo" id="Costo" class="validate" required>
					  <label for="Costo" data-error="Error" data-success="Correcto">Costo</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Existencia" id="Existencia" class="validate" required>
					  <label for="Existencia" data-error="Error" data-success="Correcto">Existencia</label>
					</div>

					<?php 
						$sql_find_proveedor = "SELECT 
													id, 
													CONCAT(nombres,' ',ape_paterno,' ',ape_materno) AS nombre_proveedor 
												FROM proveedores ";
						
						$resultado = mysqli_query($enlace, $sql_find_proveedor);
				      //la siguiente linea funciona igual a la que continúa después
				      //$count = $resultado->num_rows;
						$count = mysqli_num_rows($resultado);
					?>
					<select class="browser-default" name="Proveedor" id="Proveedor">
						<option value="0" disabled selected>Elige un Proveedor</option>
						<?php 
							if ($count > 0) {
								while ($fila = mysqli_fetch_assoc($resultado) ) {
						?>
								<option onclick="habilita_boton();" value="<?php echo $fila['id']; ?>"><?php echo $fila['nombre_proveedor']; ?></option>
						<?php
								}//FIN DEL WHILE
							}//FIN DEL IF COUNT
						?>
				    </select>
				    <label>Asignar Proveedor</label>

					<br>
					<br>
					<button class="btn waves-effect waves-light" type="submit" disabled id="actualizador" name="actualizador">Agregar
						<i class="material-icons right">send</i>
					</button>
					<a href="ver_productos" class="btn waves-effect waves-light red" role="button">Cancelar</a>
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