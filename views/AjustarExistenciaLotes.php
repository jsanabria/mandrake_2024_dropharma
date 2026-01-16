<?php

namespace PHPMaker2024\mandrake;

// Page object
$AjustarExistenciaLotes = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="container">
  <h2>Ajustar Existencia de Lotes</h2>
  <h4>Para ejecutar este proceso debe existir un ajuste de salida nuevo.</h4>
  <form class="form-horizontal">
	<div class="form-group">
	  <label class="control-label col-sm-2" for="email">Ajuste de Salida</label>
	  <div class="col-sm-10">
	  	<select id="ajuste_salida" name="ajuste_salida" class="form-control">
	  		<option value=""></option>
	  	<?php
	  	$sql = "SELECT 
	  				COUNT(id) AS cantidad 
	  			FROM 
	  				salidas 
	  			WHERE 
	  				tipo_documento = 'TDCASA' AND estatus = 'NUEVO' 
	  				AND IFNULL(unidades, 0) = 0 AND factura = 'N' 
	  			ORDER BY fecha DESC;";
	  	$cant = ExecuteScalar($sql);

	  	for($i=0; $i<$cant; $i++) {
	  		$sql = "SELECT 
	  					id, nro_documento, date_format(fecha, '%d/%m/%Y') AS fecha_ajuste, username 
	  				FROM 
	  					salidas 
		   			WHERE 
	  					tipo_documento = 'TDCASA' AND estatus = 'NUEVO' 
	  					AND IFNULL(unidades, 0) = 0 AND factura = 'N' 
	  				ORDER BY fecha DESC LIMIT $i, 1;";
	  		$row = ExecuteRow($sql);
	  		echo '<option value="' . $row["id"] . '">' . $row["nro_documento"] . ' -- ' . $row["fecha_ajuste"] . ' -- ' . $row["username"]  . '</option>';
	  	}
	  	?>
	  	</select>
	  </div>
	</div>

	<div class="form-group">        
	  <div class="col-sm-offset-2 col-sm-10">
		<button type="button" class="btn btn-primary" id="btnAjustar" name="btnAjustar">Ejecutar Proceso</button>
	  </div>
	</div>
  </form>
</div>

<div>
	<div id="result">
	</div>
</div>

<script type="text/javascript">
	$("#btnAjustar").click(function(){
		var ajuste_salida = $("#ajuste_salida").val();

		if(ajuste_salida=="") {
			alert("Debe seleccionar un Ajuste de Salida!");
			return false;
		}
		
		$.ajax({
		  url : "ajuste_lotes_diferencia_con_existencia.php",
		  type: "GET",
		  data : {id: ajuste_salida},
		  beforeSend: function(){
		    $("#result").html("Por Favor Espere. . . Este proceso puede demorar en ejecutarse.");
		  }
		})
		.done(function(data) {
			//alert(data);
			$("#result").html(data);
		})
		.fail(function(data) {
			alert( "error" + data );
		})
		.always(function(data) {
			//alert( "complete" );
			//$("#result").html("Espere. . . ");
		});
	});
</script>

<?= GetDebugMessage() ?>
