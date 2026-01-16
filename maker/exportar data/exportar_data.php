<h2>Importar Parcelas desde AS400 - Preceso efectuado</h2>
<?php
$salida = shell_exec('sh /home/parcelassh.sh');
echo '<div class="alert alert-success">
  		<strong>Proceso finalizado!</strong> Ir al M&oacute;dulo de parcelas para verificar.
  	</div>';
?>
<?php
/*if(isset($_REQUEST["error"])) {
	if($_REQUEST["error"]=="NO") {
		?>
		<div class="alert alert-success" role="alert">
		  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
		  <span class="sr-only">Exito:</span>
		  Proceso de Cargar de Archivo Exitoso
		</div>
		<?php
	}
	else {
		?>
		<div class="alert alert-danger" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  <span class="sr-only">Error:</span>
		  Error en el Proceso de Carga de Archivo: <?php echo $_REQUEST["error"]; ?>
		</div>
		<?php
	}
}*/
?>