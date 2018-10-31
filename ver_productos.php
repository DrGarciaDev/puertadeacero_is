<?php
	session_start();
	ob_start();
	if( !isset($_SESSION['usuario']) ){
	header('Location: ingresar');
	exit();
	}
	$titulo = "PRODUCTOS";
	include('config/conexion.php');
	include('include/header.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		//###### FILTRO anti-XSS
		$busqueda = htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Busqueda']) );

		$sql = "SELECT * FROM productos WHERE 
	    descripcion LIKE '%".$busqueda."%' OR 
	    costo LIKE '%".$busqueda."%' OR  
	    existencia LIKE '%".$busqueda."%' ";	  
		
		$resultadoEsp = mysqli_query($enlace, $sql);
		$count = mysqli_num_rows($resultadoEsp);
	}//Fin del if SERVER


//$query = "SELECT * FROM productos;";
//$resultado = mysqli_query($enlace, $query);
?>
    <div class="container z-depth-5">
      <?php
      if(isset($_SESSION['flash'])){
        if($_SESSION['flash']=='ProdE'){
			echo '<div class="card red lighten-5 center">
					<div class="card-content red-text">
    					<p>Producto Eliminado Correctamente...</p>
  					</div>
				</div>';
		}
        unset($_SESSION['flash']);
      }
      ?>


		<div class="row center">
	        <div class="col s12">
				<h2 class="z-depth-3 blue lighten-2">Administración de productos</h2>

				<br>
				<br>
				<?php if(isset($_SESSION['usuario'])) { 
					if($_SESSION['tipo'] === "Administrador") { ?>
				
					<a href="producto_agregar" class="waves-effect blue lighten-2 btn"><i class="material-icons left">input</i>Agregar productos</a>

					<!-- Modal Trigger -->
					<a class="waves-effect orange btn modal-trigger" href="#modal1"><i class="material-icons left">search</i>Todos los productos</a>

					<br>
					<br>

					<!-- Modal Structure -->
					<div id="modal1" class="modal bottom-sheet">
						<div class="modal-content">
							<h4>Todos los productos</h4>
							<?php
								$query = "SELECT * FROM productos;";
								$resultado = mysqli_query($enlace, $query);
							?>
							<table class="bordered highlight striped centered responsive-table">
								<thead>
									<tr>
										<th>Codigo de Producto</th>
										<th>Descripcion(s)</th>
										<th>Costo</th>
										<th>Existencia</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
								<?php while ($row = $resultado->fetch_object()){ ?>
									<tr>
										<td><?php echo $row->codigo ?>&nbsp;</td>
										<td><?php echo $row->descripcion ?>&nbsp;</td>
										<td><?php echo $row->costo ?>&nbsp;</td>
										<td><?php echo $row->existencia ?>&nbsp;</td>
										<td>
											<a href="producto_editar?id=<?= $row->codigo ?>" class="btn orange darken-1" title="Editar producto">Editar</a>
											<button type="button" class="btn red" onclick="confirmacion('<?php echo $row->descripcion; ?>', '<?php echo $row->costo; ?>', <?php echo $row->codigo; ?> )" title="Eliminar producto">Borrar</button>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
						</div>
					</div>

					<form action="" method="POST">

						<div class="input-field">
						  <i class="material-icons prefix">perm_identity</i>
						  <input type="text" name="Busqueda" id="Busqueda" class="validate" required>
						  <label for="Busqueda" data-error="Error" data-success="Correcto">Buscar Producto</label>
						</div>

						<br>
						<br>
						<button class="btn waves-effect brown" type="submit">Buscar
							<i class="material-icons right">send</i>
						</button>
						<!--<a href="ver_productos" class="btn waves-effect waves-light red" role="button">Cancelar</a>-->
					</form>
					<!--<button type="button" class="btn btn-primary btn-sm" onclick="location.href='clientes_agregar'">Agregar Nueva</button><br><br>
					-->
					<br>
					<br>
					<?php if(isset($count)) { 
						if($count > 0) { ?> 
							<table class="bordered highlight striped centered responsive-table">
								<thead>
									<tr>
										<th>Código de Producto</th>
										<th>Descripcion(s)</th>
										<th>Costo</th>
										<th>Existencia</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
								<?php while ($row = $resultadoEsp->fetch_object()){ ?>
									<tr>
										<td><?php echo $row->codigo ?>&nbsp;</td>
										<td><?php echo $row->descripcion ?>&nbsp;</td>
										<td><?php echo $row->costo ?>&nbsp;</td>
										<td><?php echo $row->existencia ?>&nbsp;</td>
										<td>
											<a href="producto_editar?id=<?= $row->codigo ?>" class="btn orange darken-1" title="Editar producto">Editar</a>
											<button type="button" class="btn red" onclick="confirmacion('<?php echo $row->descripcion; ?>', '<?php echo $row->costo; ?>', <?php echo $row->codigo; ?> )" title="Eliminar producto">Borrar</button>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						<?php }else{
							echo '<div class="card amber lighten-4 center">
	             						<div class="card-content red-text">
	                    				<p>Busca de nuevo; casa inexistente...</p>
	                  				</div>
	                			</div>';
						} 
					}?>

					<br>
					<br>
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
    </div><!-- CONTAINER -->

     <script>
		function confirmacion(nomb, apee, idd) {
			if(confirm("Realmente deseas eliminar el producto-> " + nomb + " con el costo-> " + apee + " ?"))
			{
				window.location.href="producto_eliminar?id=" + idd;
			}
		}

		//<!--A CONTINUACION SCRIPT PARA INICIALIZAR ELMODAL-->
		$(document).ready(function(){
			// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
			$('.modal').modal();
		});
    </script>

<?php include('include/footer.php') ?>
