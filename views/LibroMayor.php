<?php

namespace PHPMaker2024\mandrake;

// Page object
$LibroMayor = &$Page;
?>
<?php
$Page->showMessage();
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
<h3>Libro Mayor Anal&iacute;tico</h3>
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
	<div class="form-group">
	  <input type="text" class="form-control" id="texto" name="texto" size="10" placeholder="Buscar Cta">
	</div>
	<div class="form-group" id="cta">
		<select class="form-control" id="cuenta" name="cuenta">
			<option value=""></option>
		</select>
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
		var cuenta = $("#cuenta").val();

		if(fecha_desde=="" || fecha_hasta=="") {
			alert("Fecha Incorrectas!");
			return false;
		}

		var cuenta = $("#cuenta").val();
		if(cuenta=="") {
			alert("Debe Seleccionar una Cuenta Contable!");
			return false;
		}

		$.ajax({
		  url : "include/libro_mayor_listar.php",
		  type: "POST",
		  data : {fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, cuenta: cuenta},
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

	$("#texto").change(function(){
		var texto = $("#texto").val();

		$.ajax({
		  url : "include/buscar_cta.php",
		  type: "POST",
		  data : {texto: texto},
		  beforeSend: function(){
		  	$("#buscar").prop("disabled", true);
		    $("#cta").html("Espere. . . ");
		  }
		})
		.done(function(data) {
			//alert(data);
			$("#cta").html(data);
			$("#buscar").prop("disabled", false);
		})
		.fail(function(data) {
			alert( "error" + data );
		})
		.always(function(data) {
			//alert( "complete" );
			//$("#cta").html("Espere. . . ");
		});
	});
</script>

<?= GetDebugMessage() ?>
