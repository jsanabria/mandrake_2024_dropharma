<?php

namespace PHPMaker2024\mandrake;

// Page object
$BalanceGeneral = &$Page;
?>
<?php
$Page->showMessage();
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
<h3>Balance General</h3>
<div class="container">
  <form class="form-inline">
	<div class="form-group">
	  <label for="desde">Rango de Fecha:</label>
	  <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
	</div>
	<div class="form-group">
	  <label for="hasta"> - </label>
	  <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
	</div>
	<button type="button" class="btn btn-primary" id="buscar" name="buscar">Buscar</button>
  </form>
</div>

<div>
	<div id="result">
	</div>
</div>

<script type="text/javascript">
	$("#buscar").click(function(){
		var fecha_desde = $("#fecha_desde").val();
		var fecha_hasta = $("#fecha_hasta").val();
		var nivel = $("#nivel").val();

		if(fecha_desde=="" || fecha_hasta=="") {
			alert("Fecha Incorrectas!");
			return false;
		}

		$.ajax({
		  url : "include/balance_general_listar.php",
		  type: "POST",
		  data : {fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, nivel: nivel},
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
