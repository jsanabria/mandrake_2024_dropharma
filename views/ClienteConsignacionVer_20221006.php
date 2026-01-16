<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteConsignacionVer = &$Page;
?>
<?php
$id = isset($_POST["xCliente"]) ? $_POST["xCliente"] : "";

$sql = "SELECT nombre AS cliente FROM cliente WHERE id = $id;";
$cliente = ExecuteScalar($sql);
?>
	<form id="frm" name="frm" method="post" action="ClienteConsignacionProcesar">
  	<input type="hidden" name="xCliente" value="<?php echo $id; ?>">

  <div id="grilla" class="container form-group row">
	  <h3>Detalle Notas de Entrega <?php echo "<b>$cliente</b>" ?></h3>
	  <table class="table table-condensed table-bordered table-striped table-hover">
		<thead>
		  <tr>
		  	<th>&nbsp;</th>
			<th>CODIGO</th>
			<th>LABORATORIO</th>
			<th>ARTICULO</th>
			<th>N.E.</th>
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
		$sql = "SELECT 
			b.id,
			c.codigo,
			c.nombre_comercial, c.principio_activo, c.presentacion,
			d.nombre AS fabricante, 
			a.nro_documento,
			b.lote,
			date_format(b.fecha_vencimiento, '%d/%m/%Y') fecha_vencimiento,
			b.cantidad_articulo AS cantidad,
			b.cantidad_movimiento,
			b.cantidad_movimiento_consignacion, b.articulo     
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento= a.tipo_documento 
			JOIN articulo AS c ON c.id = b.articulo 
			JOIN fabricante AS d ON d.id = b.fabricante 
		WHERE 
			a.cliente = $id AND a.tipo_documento = 'TDCNET' AND 
			a.estatus = 'NUEVO' AND a.consignacion = 'S' 
		ORDER BY c.principio_activo, c.presentacion, b.fecha_vencimiento ASC;";
		$rows = ExecuteRows($sql);
		$i = 1;
		$xPendiente = 0;
		$ArtAnt = 0;
		foreach ($rows as $key => $value) {
			echo '<tr>';
				echo '<td>' . $i . '</td>';
				echo '<td>' . $value["codigo"] . '</td>';
				echo '<td>' . $value["fabricante"] . '</td>';
				echo '<td>' . $value["nombre_comercial"] . ' ' . $value["principio_activo"] . ' ' . $value["presentacion"] . '</td>';
				echo '<td>' . $value["nro_documento"] . '</td>';
				echo '<td>' . $value["lote"] . '</td>';
				echo '<td>' . $value["fecha_vencimiento"] . '</td>';
				echo '<td>' . intval($value["cantidad"]) . '</td>';
				echo '<td>' . intval($value["cantidad_movimiento_consignacion"]) . '</td>';
				$pendiente = intval($value["cantidad"]) - intval($value["cantidad_movimiento_consignacion"]);
				echo '<td><strong>' . $pendiente . '</strong></td>';

				/////////////////
				$xCantidad = 0;
				$idd = 0;
				foreach ($_POST as $key2 => $value2) {
					if(substr($key2, 0, 9) == "cantidad_") {
					 	if(intval($value2) > 0) { 
					 		$MyArt = explode("_", $key2);
					 		// if($value["articulo"] == substr($key2, 9, strlen($key2))) {
					 		if($value["articulo"] == intval($MyArt[1])) { 
					 			$idd = intval($MyArt[1]);
					 			// $xPendiente = intval($MyArt[2]);
					 			if($xPendiente == 0) { 
					 				if($ArtAnt == $value["articulo"]) {
						 				$xCantidad = 0;
						 				$xPendiente = 0;
					 				}
					 				else {
							 			if($value2 <= $pendiente) {
							 				$xCantidad = $value2;
							 				$xPendiente = 0;
							 			}
							 			else {
							 				$xCantidad = $pendiente;
							 				$xPendiente = $value2 - $pendiente;
							 			}
					 				}
					 			} 
					 			else {
						 			if($xPendiente <= $pendiente) {
						 				$xCantidad = $xPendiente;
						 				$xPendiente = 0;
							 			$sw = false;
						 			}
						 			else {
						 				$xCantidad = $pendiente;
						 				$xPendiente -= $pendiente;
							 			$sw = true;
						 			}
					 			}
					 			// die("<td><strong>ES: $pendiente | $value2 | $xCantidad | $xPendiente</strong></td>");
					 			// break;
					 		}
							$ArtAnt = $value["articulo"];
					 	}
					 } 
				}
				/////////////////
				// ' . $xCantidad . ' | ' . $idd . ' | ' . $xPendiente . '
				echo '<td width="5%">
						<input type="hidden" class="form-control input-sm" id="cant_' . $value["id"] . '" name="cant_' . $value["id"] . '" value="' . $pendiente . '">
						<input type="text" class="form-control input-sm" size="6" id="cantidad_' . $value["id"] . '" name="cantidad_' . $value["id"] . '" value="' . $xCantidad . '" onchange="js:validad_cantidad(this.value, ' . $value["id"] . ');">
					</td>';
			echo '</tr>';

			$i++;
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
