<?php

namespace PHPMaker2024\mandrake;

// Page object
$Indicadores = &$Page;
?>
<?php
$Page->showMessage();
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>

<div class="container">
	<form class="form-inline" action="/action_page.php">
	  <div class="form-group">
		  <label for="desde">Desde:</label>
		  <input type="date" class="form-control" id="fechad" name="fechad" value="">
	  </div>
	  <div class="form-group">
		  <label for="desde">Hasta:</label>
		  <input type="date" class="form-control" id="fechah" name="fechah" value="">
	  </div>
	  <div class="form-group">
	  	<select class="form-control" id="tipo" name="tipo">
	  		<option value=""> </option>
			<option value="vendedor"> Asesor</option>
			<option value="ciudad"> Ciudad</option>
		</select>
	  </div>
	  <div class="form-group" id="selOption">
	  </div>
	  <div class="form-group">
	  	<select class="form-control" id="orden" name="orden">
	  		<option value="1">Orden col 1</option>
			<option value="2">Orden col 2</option>
			<option value="3">Orden col 3</option>
			<option value="4">Orden col 4</option>
		</select>
	  </div>
	  <div class="form-group">
	  	<select class="form-control" id="torden" name="torden">
	  		<option value="ASC">ASC</option>
			<option value="DESC">DESC</option>
		</select>
	  </div>
	  <button type="button" id="enviar" class="btn btn-default">Enviar</button>
	</form>
</div>

<hr>

<div>
	<div id="result">
	</div>
</div>

<script type="text/javascript">
	$("#tipo").change(function(){
		var tipo = $("#tipo").val();
		
		$.ajax({
		  url : "include/indicadores_tipo.php",
		  type: "GET",
		  data : {tipo: tipo},
		  beforeSend: function(){
		    $("#selOption").html("Espere. . . ");
		  }
		})
		.done(function(data) {
			//alert(data);
			$("#selOption").html(data);
		})
		.fail(function(data) {
			alert( "error" + data );
		})
		.always(function(data) {
			//alert( "complete" );
			//$("#result").html("Espere. . . ");
		});

		$("#result").html("");
	});	

	$("#enviar").click(function(){
		var orden = $("#orden").val();
		var torden = $("#torden").val();
		var fechad = $("#fechad").val();
		var fechah = $("#fechah").val();
		var tipo = $("#tipo").val();
		var tipo_name = "";
		if(tipo == "vendedor") tipo_name = $("#vendedor").val();
		else if(tipo == "ciudad") tipo_name = $("#ciudad").val();

		//alert(fechad + " - " + fechah + " - " + tipo + " - " + tipo_name);

		$.ajax({
		  url : "include/indicadores_find.php",
		  type: "GET",
		  data : {fechad: fechad, fechah, fechah, tipo: tipo, tipo_name: tipo_name, orden: orden, torden: torden},
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
