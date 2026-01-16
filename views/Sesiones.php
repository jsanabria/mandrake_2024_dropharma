<?php

namespace PHPMaker2024\mandrake;

// Page object
$Sesiones = &$Page;
?>
<?php
$Page->showMessage();
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
<div class="container">
	<div class="form-group">
	  <label for="desde">Fecha:</label>
	  <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date("Y-m-d"); ?>">
	</div>
</div>

<div>
	<div id="result">
		<?php include_once("include/sesiones_buscar.php"); ?>
	</div>
</div>

<script type="text/javascript">
	$("#fecha").change(function(){
		var fecha = $("#fecha").val();

		$.ajax({
		  url : "include/sesiones_buscar.php",
		  type: "GET",
		  data : {fecha: fecha},
		  beforeSend: function(){
		    $("#result").html("Espere. . . ");
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
