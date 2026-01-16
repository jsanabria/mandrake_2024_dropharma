<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteConsignacionArticulos = &$Page;
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.min.js"></script>
<?php

namespace PHPMaker2021\mandrake;

// Page object
$ClienteConsignacionArticulos = &$Page;
?>
<?php
$id = isset($_POST["xCliente"]) ? $_POST["xCliente"] : "";

if($id == "") {
?>
<div class="container">
	<div class="alert alert-danger" role="alert">
		Debe seleccionar un cliente para procesar la consignaci&oacute;n
  	</div>
	<a href="ClienteConsignacionLista" class="btn btn-default">Regresar</a>
</div>
<?php
}
else {
	$sql = "SELECT nombre AS cliente FROM cliente WHERE id = $id;";
	$cliente = ExecuteScalar($sql);
}
?>
	<form id="frm" name="frm" method="post" action="ClienteConsignacionVer">
  	<input type="hidden" name="xCliente" value="<?php echo $id; ?>">

  <div id="grilla" class="container form-group row">
	  <h3>Art&iacute;culos en Notas de Entrega <?php echo "<b>$cliente</b>" ?></h3>
	  <table class="table table-condensed table-bordered table-striped table-hover">
		<thead>
		  <tr>
		  	<th>&nbsp;</th>
			<th>CODIGO</th>
			<th>LABORATORIO</th>
			<th>ARTICULO</th>
			<th>ENTREGADO</th>
			<th>FACTURADO</th>
			<th>PEDIENTE</th>
			<th>REPORTADO</th>
		  </tr>
		</thead>
		<tbody>
		<?php 
		$sql = "SELECT 
			c.id,
			c.codigo,
			c.nombre_comercial, c.principio_activo, c.presentacion,
			d.nombre AS fabricante, 
			SUM(b.cantidad_articulo) AS cantidad,
			SUM(b.cantidad_movimiento) AS cantidad_movimiento,
			SUM(b.cantidad_movimiento_consignacion) AS cantidad_movimiento_consignacion  
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento= a.tipo_documento 
			JOIN articulo AS c ON c.id = b.articulo 
			JOIN fabricante AS d ON d.id = b.fabricante 
		WHERE 
			a.cliente = $id AND a.tipo_documento = 'TDCNET' AND 
			a.estatus = 'NUEVO' AND a.consignacion = 'S'
		GROUP BY c.id, c.codigo, c.nombre_comercial, c.principio_activo, c.presentacion, d.nombre
		ORDER BY c.principio_activo, c.presentacion;";
		$rows = ExecuteRows($sql);
		$i = 1;
		foreach ($rows as $key => $value) {
			echo '<tr>';
				echo '<td>' . $i . '</td>';
				echo '<td>' . $value["codigo"] . '</td>';
				echo '<td>' . $value["fabricante"] . '</td>';
				echo '<td>' . $value["nombre_comercial"] . ' ' . $value["principio_activo"] . ' ' . $value["presentacion"] . '</td>';
				echo '<td>' . intval($value["cantidad"]) . '</td>';
				echo '<td>' . intval($value["cantidad_movimiento_consignacion"]) . '</td>';
				$pendiente = intval($value["cantidad"]) - intval($value["cantidad_movimiento_consignacion"]);
				echo '<td><strong>' . $pendiente . '</strong></td>';
				echo '<td width="5%">
						<input type="hidden" class="form-control input-sm" id="cant_' . $value["id"] . '" name="cant_' . $value["id"] . '" value="' . $pendiente . '">
						<input type="text" class="form-control input-sm" size="6" id="cantidad_' . $value["id"] . '" name="cantidad_' . $value["id"] . '" value="0" onchange="js:validad_cantidad(this.value, ' . $value["id"] . ');">
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


<?= GetDebugMessage() ?>
