<?php
session_start();

include "connect.php"; 

$cliente = $_REQUEST["cliente"];

?>
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-1" align="center">
		<!--<h3>Facturas por cobrar al cliente</h3>-->
		<table class="table table-condensed">
		<thead>
		  <tr>
		    <th class="col-sm-1">&nbsp;</th>
		    <th class="col-sm-2">Documento</th>
		    <th class="col-sm-2">Tipo</th>
		    <th class="col-sm-2">Nro.</th>
		    <th class="col-sm-1">A Pagar</th>
		    <th class="col-sm-1">Saldo</th>
		  </tr>
		</thead>
		<tbody>
		  <?php 
		  	$sql = "SELECT 
						a.id AS id_documento, c.descripcion AS tipo_documento, b.descripcion, a.nro_documento, 
						a.monto_pagar, a.monto_pagado, a.retiva, a.retivamonto, a.retislr, a.retislrmonto, a.tipodoc 
					FROM 
						view_x_cobrar AS a
						JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
						JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
					WHERE 
						a.cliente = $cliente 
						AND IFNULL(a.monto_pagar, 0) > 0 
						AND IFNULL(a.monto_pagar, 0) > (IFNULL(a.monto_pagado, 0)+IFNULL(a.retivamonto, 0)+IFNULL(a.retislrmonto, 0));"; 

			$rs = mysqli_query($link, $sql);
			$i = 0;
			while($row = mysqli_fetch_array($rs)) { 
				$id_documento = $row["id_documento"];
				$tipo_documento = $row["tipo_documento"];
				$monto_pagar = floatval($row["monto_pagar"]);
				$monto_pagado = floatval($row["monto_pagado"]);

				$retivamonto = floatval($row["retivamonto"]);
				$retiva = $row["retiva"];
				$retislrmonto = floatval($row["retislrmonto"]);
				$retislr = $row["retislr"];
				//$saldo = floatval($row["monto_pagar"]) - floatval($row["monto_pagado"]);

				$saldo = $monto_pagar - ($monto_pagado + $retivamonto + $retislrmonto);

				$x_id = "x_id_$i";
				$x_pagar = "x_pagar_$i";
				$x_pagado = "x_pagado_$i";
				$x_saldo = "x_saldo_$i";

				?>
				<tr>
					<td class="col-sm-1">
						<input type="radio" id="<?php echo $x_id; ?>" name="<?php echo $x_id; ?>" onclick="js:validar_check(<?php echo $i; ?>);" value="<?php echo "$id_documento-$tipo_documento"; ?>">
					</td>
					<td class="col-sm-2"><?php echo $row["descripcion"]; ?></td>
					<td class="col-sm-2"><?php echo $row["tipo_documento"]; ?></td>
					<td class="col-sm-2"><?php echo $row["nro_documento"]; ?></td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_pagar; ?>" name="<?php echo $x_pagar; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagar, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_saldo; ?>" name="<?php echo $x_saldo; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagar, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
				</tr>
				<?php
				$i++;
			}
		  ?>
		  <input type="hidden" id="xCantidad" name="xCantidad" value="<?php echo $i; ?>"></input>
		  <input type="hidden" id="pagos" name="pagos" value="">
		  <input type="hidden" id="monto" name="monto" value="">
		  <input type="hidden" id="abono" name="abono" value="">
		  <input type="hidden" id="saldo" name="saldo" value="">
		  <input type="hidden" id="xctrl" name="xctrl" value="">
		</tbody>
		</table>
	</div>
<script type="text/javascript">
	function validar_check(xi) {
		// var pagar = parseFloat($("#x_pagar_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var monto = $("#x_pagar_" + xi).val();
		monto = monto.replace(".", "");
		monto = monto.replace(",", ".");
		$("#x_pagar_" + xi).val(monto);
		var pagar = parseFloat($("#x_pagar_" + xi).val()); 

		if($("#x_id_" + xi).is(':checked')) $("#monto").val(pagar);
		$("#xctrl").val("x_saldo_" + xi);
	}
</script>
</div>
<?php include "connect.php"; ?>
