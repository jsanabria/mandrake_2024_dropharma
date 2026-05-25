<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];


$sql = "SELECT 
			a.proveedor AS id, b.nombre AS nombre_proveedor 
		FROM 
			compra AS a JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE a.id = $id;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$codpro = $row["id"];
$nompro = $row["nombre_proveedor"];
$tasa_cambio_fact = 1; // $row["tasa"];

$sql = "SELECT tasa FROM tasa_usd
		WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tasa_cambio = $row["tasa"]; // Tasa del día cargada según BCV
// $tasa_cambio = $tasa_cambio_fact;

$out = '<label id="elh_cobros_cliente_pago" for="x_cliente" class="col-sm-2 col-form-label ew-label">
			Proveedor
		</label>
		<div class="col-sm-10">
			<div>
				<span id="elh_cobros_cliente_pago">
					<input type="hidden" data-table="pagos_proveedor" data-field="x_proveedor" name="x_proveedor" id="x_proveedor" size="30" placeholder="Proveedor" value="' . $codpro . '" class="form-control" aria-describedby="x_pago_help" readonly="">
					<input type="text" data-table="pagos_proveedor" data-field="x_proveedor" name="x_Nomproveedor" id="x_Nomproveedor" size="30" placeholder="Proveedor" value="' . $nompro . '" class="form-control" aria-describedby="x_pago_help" readonly="">
					<strong>Tasa USD: ' . number_format($tasa_cambio, 2, ".", ",") . ' Bs.</strong>
					<input type="hidden" name="tasa_usd" id="tasa_usd" value="' . $tasa_cambio . '" class="form-control">
				</span>
			</div>
		</div>';
$out .= '|';

$out .= '<div class="container-fluid">
	<div class="col-md-9 col-md-offset-1" align="center">
		<!--<h3>Facturas por cobrar al cliente</h3>-->
		<table class="table table-condensed">
		<thead>
		  <tr>
		    <th class="col-sm-1">&nbsp;</th>
		    <th class="col-sm-1">Documento</th>
		    <th class="col-sm-1">Tipo</th>
		    <th class="col-sm-1">Nro.</th>
		    <th class="col-sm-1 text-right">A Pagar Bs.</th>
		    <th class="col-sm-1 text-right">Saldo Bs.</th>
		    <!--<th class="col-sm-1 text-right">A Pagar Bs.</th>-->
		  </tr>
		</thead>
		<tbody>';


		  	/*$sql = "SELECT 
						a.id AS id_documento, c.descripcion AS tipo_documento, b.descripcion, a.nro_documento, 
						a.monto_pagar, a.monto_pagado, a.retiva, a.retivamonto, a.retislr, a.retislrmonto, a.tipodoc, a.tipo_documento AS tdc
					FROM 
						view_x_cobrar AS a
						LEFT OUTER JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
						LEFT OUTER JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
					WHERE 
						a.id = $id;"; */
			$sql = "SELECT 
						a.id AS id_documento, 'Compras' AS tipo_documento, '' AS descripcion, a.documento AS nro_documento, 
						IFNULL(a.monto_total, 0) AS monto_pagar, 
						 (SELECT SUM(monto_bs) AS monto 
						 FROM pagos_compras_detalle 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS monto_pagado,    
						 0 AS retivamonto,   
						 0 AS retiva,     
						 0 AS retislrmonto, 
						 0 AS retislr, 
						 a.tipo_documento AS tdc
					FROM 
						compra AS a
					WHERE 
						a.id = $id;";
			$rs = mysqli_query($link, $sql);
			$i = 0;

			while($row = mysqli_fetch_array($rs)) { 
				$id_documento = $row["id_documento"];
				$tipo_documento = $row["tipo_documento"];
				$tdc = $row["tdc"];
				$monto_pagar = round(floatval($row["monto_pagar"]), 2);
				$monto_pagado = round(floatval($row["monto_pagado"]), 2);

				if($tdc == "TDCNET") 
					$monto_pagar = round($monto_pagar*$tasa_cambio, 2);;

				$monto_pagar01 = $monto_pagar;
				//$saldo = floatval($row["monto_pagar"]) - floatval($row["monto_pagado"]);

				$monto_pagar = round($monto_pagar/$tasa_cambio_fact, 2);
				$monto_pagado = round($monto_pagado/$tasa_cambio, 2);

				$retivamonto = floatval($row["retivamonto"]);
				$retiva = $row["retiva"];
				$retislrmonto = floatval($row["retislrmonto"]);
				$retislr = $row["retislr"];

				$saldo = $monto_pagar - ($monto_pagado + $retivamonto + $retislrmonto);

				$x_id = "x_id_$i";
				$x_pagar = "x_pagar_$i";
				$x_pagado = "x_pagado_$i";
				$x_saldo = "x_saldo_$i";

				$out .= '<tr>
					<td class="col-sm-1">
						<input type="radio" id="' . $x_id . '" name="' . $x_id . '" value="' . "$id_documento-$tipo_documento" . '" checked="checked">
					</td>
					<td class="col-sm-1">' . $row["descripcion"] . '</td>
					<td class="col-sm-1">' . $row["tipo_documento"] . '</td>
					<td class="col-sm-1">' . $row["nro_documento"] . '</td>
					<td class="col-sm-1">
						<input type="text" id="' . $x_pagar . '" name="' . $x_pagar . '" class="form-control text-right input-sm" value="'. number_format($monto_pagar, 2, ".", ",") . '" size="12" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="' . $x_saldo . '" name="' . $x_saldo . '" class="form-control text-right input-sm" value="' . number_format($monto_pagar, 2, ".", ",") . '" size="12" readonly="yes">
					</td>
					<!--<td class="col-sm-1">
						<input type="text" class="form-control text-right input-sm" value="' . number_format($monto_pagar01, 2, ".", ",") . '" size="12" readonly="yes">
					</td>-->
				</tr>';
				$i++;
			}

		  $out .= '<input type="hidden" id="xCantidad" name="xCantidad" value="' . $i . '"></input>
		  <input type="hidden" id="pagos" name="pagos" value="">
		  <input type="hidden" id="monto" name="monto" value="' . $monto_pagar . '">
		  <input type="hidden" id="abono" name="abono" value="">
		  <input type="hidden" id="saldo" name="saldo" value="">
		  <input type="hidden" id="xctrl" name="xctrl" value="x_saldo_0">
		</tbody>
		</table>
	</div>';

	$out .= '|' . $x_pagar . '';

include "connect.php";	

echo $out;		
?>
