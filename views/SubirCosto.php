<?php

namespace PHPMaker2024\mandrake;

// Page object
$SubirCosto = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="container">
	<?php
		if (isset($_SESSION['message']) && $_SESSION['message']) {
			echo '<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Muy bien!</strong> ' . $_SESSION['message'] . '
					</div>';
			//printf('<b>%s</b>', $_SESSION['message']);
			unset($_SESSION['message']);
		}
	?>
	<?php
		if (isset($_SESSION['error']) && $_SESSION['error']) {
			echo '<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Error!</strong> ' . $_SESSION['error'] . '
					</div>';
			// printf('<b>%s</b>', $_SESSION['error']);
			unset($_SESSION['error']);
		}
	?>

	<form class="form-horizontal" method="POST" action="SubirCostoGuardar" enctype="multipart/form-data">
		<div class="form-group">
			<a href="ArticuloAnteriorList" class="btn btn-primary">Ver resultado de la carga</a> 
			<a href="ViewCostoArticulosNoEncontradosList" class="btn btn-primary">Ver art&iacute;culos no encontrados</a>
		</div>
		<div class="form-group">
			<label for="archivo">Seleccione un archivo .csv: </label>
			<input class="form-control" type="file" name="uploadedFile" />
		</div>
		<button type="submit" name="uploadBtn" class="btn btn-primary">Submit</button>
	</form>
</div>

<?= GetDebugMessage() ?>
