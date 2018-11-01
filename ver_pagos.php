<?php
	session_start();
	ob_start();
	if( !isset($_SESSION['usuario']) ){
	header('Location: ingresar');
	exit();
	}
	$titulo = "PAGOS";
	include('config/conexion.php');
	include('include/header.php');

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		//###### FILTRO anti-XSS
		$busqueda = htmlspecialchars( mysqli_real_escape_string($enlace, $_POST['Busqueda']) );

		$sql = "SELECT * FROM pagos WHERE 
	    folio LIKE '%".$busqueda."%' OR 
	    fecha LIKE '%".$busqueda."%' OR  
	    monto LIKE '%".$busqueda."%' ";	  
		
		$resultadoEsp = mysqli_query($enlace, $sql);
		$count = mysqli_num_rows($resultadoEsp);
	}//Fin del if SERVER


//$query = "SELECT * FROM casas;";
//$resultado = mysqli_query($enlace, $query);
?>
    <div class="container z-depth-5">
      <?php
      if(isset($_SESSION['flash'])){
        if($_SESSION['flash']=='CaE'){
			echo '<div class="card red lighten-5 center">
					<div class="card-content red-text">
    					<p>Casa Eliminada Correctamente...</p>
  					</div>
				</div>';
		}
        unset($_SESSION['flash']);
      }
      ?>


		<div class="row center">
	        <div class="col s12">
				<h2 class="z-depth-3 teal darken-2">Administración de Pagos</h2>

				<br>
				<br>
				<?php if(isset($_SESSION['usuario'])) { 
					if($_SESSION['tipo'] === "Administrador") { ?>
				
					<a href="pago_agregar" class="waves-effect blue lighten-2 btn"><i class="material-icons left">input</i>Realizar pago</a>

					<!-- Modal Trigger -->
					<a class="waves-effect orange btn modal-trigger" href="#modal1"><i class="material-icons left">search</i>Todas los pagos</a>

					<br>
					<br>

					<!-- Modal Structure -->
					<div id="modal1" class="modal bottom-sheet">
						<div class="modal-content">
							<h4>Todos los pagos</h4>
							<?php
								$query = "SELECT * FROM pagos;";
								$resultado = mysqli_query($enlace, $query);
							?>
							<table class="bordered highlight striped centered responsive-table">
								<thead>
									<tr>
										<th>FOLIO</th>
										<th>FECHA</th>
										<th>MONTO</th>
										<th>ACCIONES</th>
									</tr>
								</thead>
								<tbody>
								<?php while ($row = $resultado->fetch_object()){ ?>
									<tr>
										<td><?php echo $row->folio ?>&nbsp;</td>
										<td><?php echo $row->fecha ?>&nbsp;</td>
										<td><?php echo $row->monto ?>&nbsp;</td>
										<td>
											<a href="casa_editar?id=<?= $row->folio ?>" class="btn orange darken-1" title="Editar casa">editar</a>
											<button type="button" class="btn red" onclick="confirmacion('<?php echo $row->fecha; ?>', '<?php echo $row->monto; ?>', <?php echo $row->folio; ?> )" title="Eliminar casa">Borrar</button>
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
						  <label for="Busqueda" data-error="Error" data-success="Correcto">Buscar pago</label>
						</div>

						<br>
						<br>
						<button class="btn waves-effect brown" type="submit">Buscar
							<i class="material-icons right">send</i>
						</button>
						<!--<a href="ver_casas" class="btn waves-effect waves-light red" role="button">Cancelar</a>-->
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
										<th>FOLIO</th>
										<th>FECHA</th>
										<th>MONTO</th>
										<th>ACCIONES</th>
									</tr>
								</thead>
								<tbody>
								<?php while ($row = $resultadoEsp->fetch_object()){ ?>
									<tr>
										<td><?php echo $row->folio ?>&nbsp;</td>
										<td><?php echo $row->fecha ?>&nbsp;</td>
										<td><?php echo $row->monto ?>&nbsp;</td>
										<td>
											<a href="casa_editar?id=<?= $row->folio ?>" class="btn orange darken-1" title="Editar casa">Editar</a>
											<button type="button" class="btn red" onclick="confirmacion('<?php echo $row->fecha; ?>', '<?php echo $row->monto; ?>', <?php echo $row->folio; ?> )" title="Eliminar casa">Borrar</button>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						<?php }else{
							echo '<div class="card amber lighten-4 center">
	             						<div class="card-content red-text">
	                    				<p>Busca de nuevo; pago inexistente...</p>
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
			if(confirm("Realmente deseas eliminar la casa-> " + nomb + " con el adeudo-> " + apee + " ?"))
			{
				window.location.href="casa_eliminar?id=" + idd;
			}
		}

		//<!--A CONTINUACION SCRIPT PARA INICIALIZAR ELMODAL-->
		$(document).ready(function(){
			// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
			$('.modal').modal();
		});
    </script>

<?php include('include/footer.php') ?>
