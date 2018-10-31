<?php
session_start();
ob_start();
if( !isset($_SESSION['usuario']) ){
  header('Location: ingresar');
  exit();
}

$titulo = "EDITAR PRODUCTO";

include('config/conexion.php');
include ('include/header.php');

$id_recibido = htmlspecialchars( mysqli_real_escape_string($enlace, $_GET['id']) );

$query = "SELECT * FROM productos WHERE codigo = ".$id_recibido;

$resultado = mysqli_query($enlace, $query);
//var_dump($query);
$producto = $resultado->fetch_object();

if (empty($producto)) {
	exit();
}

if (!empty($_POST)) {

	$id 			= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Id']) );
	$descripcion 	= trim(strtoupper(htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Descripcion']) ) ) );
	$costo 			= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Costo']) );
	$existencia 	= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Existencia']) );
	$proveedor 		= htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Proveedor']) );

		$query = "UPDATE productos SET descripcion = '".$descripcion."', costo = ".$costo.", existencia = ".$existencia.", proveedor_id = ".$proveedor." WHERE codigo = ".$id ;
		
		$resultado = mysqli_query($enlace, $query);
		
		if ($resultado) {
			echo ' 	<script>
						alert("Producto editado con éxito");
						location.href="ver_productos";
					</script>';
		}
		else {
			echo ' 	<script>
						alert("Producto No editado, intentalo nuevamente");
					</script>';
		}
}
?>

<script>
	function habilita_boton() {
		//alert('Elige un usuario valido...');
		document.getElementById('actualizador').disabled = false;
	}
</script>

    <div class="container">
	    <div class="row">
	    	
        <?php if(isset($_SESSION['usuario'])) { 
					if($_SESSION['tipo'] === "Administrador") { ?>

			<div class="col s3">
			  
			</div>
			<div class="col 16 s6 center">
				<h2 class="header orange-text">Editar Producto: <?php if(isset($producto->descripcion)) echo $producto->descripcion; ?></h2>

				<p class="teal-text">Puedes modificar cualquier campo...</p>
				<form action="" method="POST">

					<input type="hidden" name="Id" id="Id" value="<?php if(isset($producto->codigo)) echo $producto->codigo; ?>">

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Descripcion" id="Descripcion" class="validate" value="<?php if(isset($producto->descripcion)) echo $producto->descripcion; ?>" required>
					  <label for="Descripcion" data-error="Error" data-success="Correcto">Descripcion</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Costo" id="Costo" class="validate" value="<?php if(isset($producto->costo)) echo $producto->costo; ?>" required>
					  <label for="Costo" data-error="Error" data-success="Correcto">Costo</label>
					</div>

					<div class="input-field">
					  <i class="material-icons prefix">perm_identity</i>
					  <input type="text" name="Existencia" id="Existencia" class="validate" value="<?php if(isset($producto->existencia)) echo $producto->existencia; ?>" required>
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
					<button class="btn waves-effect waves-light" type="submit" disabled id="actualizador" name="actualizador">Actualizar
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
    					<p>Error, inicia session...</p>
  					</div>
				</div>';
		} ?>

      </div>

    </div>
<?php include('include/footer.php') ?>