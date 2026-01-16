<?php

namespace PHPMaker2024\mandrake;

// Page object
$VentasPorLaboratorio = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"];
$url = "listado_master_buscar.php";
$titulo = $id;

?>
<button type="button" class="btn btn-primary" id="regresar" name="regresar" onClick="js:window.history.back();">Regresar a Reportes</button>
<h3><?php echo "Reporte: " . $titulo; ?></h3>
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
	  <label for="tipo">Tipo</label>
	  <select id="tipo" name="tipo" class="form-control">
	  	<option value="">TODOS</option>
	  	<?php
	if($id=="CLIENTES IMS" or $id=="ARTICULOS IM" or $id=="FACTURAS IMS") {
  		$sql = "SELECT COUNT(id) AS cantidad FROM tarifa WHERE activo = 'S';";
  		$cantidad = ExecuteScalar($sql);
  		for($i=0; $i<$cantidad; $i++) {
  			$sql = "SELECT id, nombre FROM tarifa WHERE activo = 'S' LIMIT $i, 1;";
  			$row = ExecuteRow($sql);
  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
  		}
  	}
  	elseif($id=="LIBRO COMPRA") {

  	}
  	elseif($id=="LIBRO VENTA") {
  		echo '<option value="FC">FACTURA</option>';
  		echo '<option value="NC">NOTA DE CREDITO</option>';
  		echo '<option value="ND">NOTA DE DEBITO</option>';
  	}
  	elseif($id=="VENTAS POR LABORATORIO") {
  		$sql = "SELECT COUNT(id) AS cantidad FROM fabricante;";
  		$cantidad = ExecuteScalar($sql);
  		for($i=0; $i<$cantidad; $i++) {
  			$sql = "SELECT id, nombre FROM fabricante ORDER BY nombre LIMIT $i, 1;";
  			$row = ExecuteRow($sql);
  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
  		}
  	}
  	elseif($id=="VENTAS POR ARTICULO") {
  		$sql = "SELECT COUNT(id) AS cantidad FROM fabricante;";
  		$cantidad = ExecuteScalar($sql);
  		for($i=0; $i<$cantidad; $i++) {
  			$sql = "SELECT id, nombre FROM fabricante ORDER BY nombre LIMIT $i, 1;";
  			$row = ExecuteRow($sql);
  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
  		}
  	}
	  	?>
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
		var tipo = $("#tipo").val();

		if(fecha_desde=="" || fecha_hasta=="") {
			alert("Fecha Incorrectas!");
			return false;
		}
		$.ajax({
		  url : "<?php echo $url; ?>",
		  type: "GET",
		  data : {id: '<?php echo $id;?>', fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, tipo: tipo, proveedor : 0},
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
