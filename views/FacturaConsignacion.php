<?php

namespace PHPMaker2024\mandrake;

// Page object
$FacturaConsignacion = &$Page;
?>
<?php
$Page->showMessage();
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>

<?php

$id = $_REQUEST["id"];

$sql = "SELECT 
			a.id, c.descripcion AS documento, date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			b.nombre AS cliente, a.nro_documento, a.nota, a.tipo_documento
		FROM 
			salidas AS a 
			JOIN cliente AS b ON b.id = a.cliente 
			JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
		WHERE a.id = $id;";
$row = ExecuteRow($sql);
$documento = $row["documento"];
$fecha = $row["fecha"]; 
$cliente = $row["cliente"];
$nro_documento = $row["nro_documento"];
$nota = $row["nota"];
$tipo_documento = $row["tipo_documento"];

?>

<form id="frm" name="frm" method="post" action="FacturaConsignacionGuardar">
  <div class="form-group row">
	<label for="documento" class="col-sm-2 col-form-label">Documento</label>
	<div class="col-sm-10">
	  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">
	  <input type="text" readonly class="form-control" id="documento" name="documento" value="<?php echo $documento; ?>">
	</div>
  </div>
  <div class="form-group row">
	<label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
	<div class="col-sm-10">
	  <input type="text" readonly class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
	</div>
  </div>
  <div class="form-group row">
	<label for="cliente" class="col-sm-2 col-form-label">Cliente</label>
	<div class="col-sm-10">
	  <input type="text" readonly class="form-control" id="cliente" name="cliente" value="<?php echo $cliente; ?>">
	</div>
  </div>
  <div class="form-group row">
	<label for="nro_documento" class="col-sm-2 col-form-label">Nro. Documento</label>
	<div class="col-sm-10">
	  <input type="text" readonly class="form-control" id="nro_documento" name="nro_documento" value="<?php echo $nro_documento; ?>">
	</div>
  </div>
  <div class="form-group row">
	<label for="nota" class="col-sm-2 col-form-label">Nota</label>
	<div class="col-sm-10">
	  <textarea readonly class="form-control" id="nota" name="nota" rows="3" cols="30"><?php echo $nota; ?></textarea>
	</div>
  </div>
  <div id="grilla" class="container form-group row">
	  <h3>Detalle Nota de Entrega</h3>
	  <table class="table table-condensed table-bordered table-striped table-hover">
		<thead>
		  <tr>
		  	<th>&nbsp;</th>
			<th>CODIGO</th>
			<th>LABORATORIO</th>
			<th>ARTICULO</th>
			<th>LOTE</th>
			<th>VENCIM</th>
			<th>ENTREGADO</th>
			<th>FACTURADO</th>
			<th>PEDIENTE</th>
			<th>REPORTADO</th>
		  </tr>
		</thead>
		<tbody>
		<?php 
		include 'include/connect.php';

		$sql = "SELECT a.id, 
					b.codigo, 
					IFNULL(c.nombre, '') AS laboratorio, 
					CONCAT(IFNULL(b.nombre_comercial, ''), IF(IFNULL(b.nombre_comercial, '')='', '', ' - '), IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ') AS articulo, 
					a.cantidad_articulo AS cantidad, 
					a.cantidad_movimiento_consignacion, 
					(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
					(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
					a.costo_unidad, 
					a.costo, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento 
				FROM 
					entradas_salidas AS a 
					LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
					LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
				WHERE 
					a.id_documento = $id AND a.tipo_documento = '$tipo_documento' 
				ORDER BY c.nombre, b.principio_activo, b.presentacion;"; 
		$rs = mysqli_query($link, $sql) or die(mysqli_error());
		$items = 0;
		$labt = "";
		$sw = false;
		$i = 0;
		$y = 0;
		$yy = 0;
		while($row = mysqli_fetch_array($rs))
		{
			echo '<tr>';
				echo '<td>' . ($i + 1) . '</td>';
				echo '<td>' . $row["codigo"] . '</td>';
				echo '<td>' . $row["laboratorio"] . '</td>';
				echo '<td>' . $row["articulo"] . '</td>';
				echo '<td>' . $row["lote"] . '</td>';
				echo '<td>' . $row["fecha_vencimiento"] . '</td>';
				echo '<td>' . intval($row["cantidad"]) . '</td>';
				echo '<td>' . intval($row["cantidad_movimiento_consignacion"]) . '</td>';
				$pendiente = intval($row["cantidad"]) - intval($row["cantidad_movimiento_consignacion"]);
				echo '<td><strong>' . $pendiente . '</strong></td>';
				echo '<td width="5%">
						<input type="hidden" class="form-control input-sm" id="cant_' . $row["id"] . '" name="cant_' . $row["id"] . '" value="' . $pendiente . '">
						<input type="text" class="form-control input-sm" size="6" id="cantidad_' . $row["id"] . '" name="cantidad_' . $row["id"] . '" value="0" onchange="js:validad_cantidad(this.value, ' . $row["id"] . ');">
					</td>';
			echo '</tr>';

			$items++;
			$labt = $row["laboratorio"];
			$i++;
			$y += intval($row["cantidad"]);
			$yy += intval($row["cantidad"]);
		}
		?>
		</tbody>
	  </table> 

	  <input type="button" class="btn btn-primary" id="btnEnviar" value="Enviar">

  </div>  
</form>

<script type="text/javascript">
	function validad_cantidad(cant, id) {
		var cant = Number(cant);
		var cant2 = Number($("#cant_" + id).val());
		if(cant > cant2) {
			alert("!!! La cantidad colocada es mayor a la cantidad pendiente !!!");
			$("#cantidad_" + id).val(0);
			$("#cantidad_" + id).focus();
		}
	}

	$("#btnEnviar").click(function(){
		var sw = false;

		$("#frm").find(':input').each(function() {
			var elemento = this;
			if(elemento.id.substring(0, 9) == "cantidad_") {
				if(Number(elemento.value) > 0) {
					// console.log("elemento.id="+ elemento.id + ", elemento.value=" + elemento.value + ", indice _ " + elemento.id.substring(9)); 
					sw = true;
				}
			}
		});

		if(sw) 
			$("#frm").submit();
		else
			alert("Para crear la factura debe cargar cantidades en al menos un item");
	}); 	

</script>


<?= GetDebugMessage() ?>
