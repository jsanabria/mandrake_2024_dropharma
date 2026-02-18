<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];


$sql = "SELECT 
			a.cliente AS id, b.nombre AS nombre_cliente, tasa_dia AS tasa  
		FROM 
			salidas AS a JOIN cliente AS b ON b.id = a.cliente 
		WHERE a.id = $id;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$codcli = $row["id"];
$nomcli = $row["nombre_cliente"];
$tasa_cambio_fact = $row["tasa"];

$sql = "SELECT tasa FROM tasa_usd
		WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
// $tasa_cambio = $row["tasa"]; // Tasa del día cargada según BCV
$tasa_cambio = $tasa_cambio_fact;

$out = '<label id="elh_cobros_cliente_pago" for="x_cliente" class="col-sm-2 col-form-label ew-label">
			Cliente
		</label>
		<div class="col-sm-10">
			<div>
				<span id="el_cobros_cliente_cliente">
					<input type="hidden" data-table="cobros_cliente" data-field="x_cliente" name="x_cliente" id="x_cliente" size="30" placeholder="Cliente" value="' . $codcli . '" class="form-control" aria-describedby="x_pago_help" readonly="">
					<input type="text" data-table="cobros_cliente" data-field="x_cliente" name="x_Nomcliente" id="x_Nomcliente" size="30" placeholder="Cliente" value="' . $nomcli . '" class="form-control" aria-describedby="x_pago_help" readonly="">
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
		    <th class="col-sm-1 text-right">A Pagar $</th>
		    <th class="col-sm-1 text-right">Saldo $</th>
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
						a.id AS id_documento, c.descripcion AS tipo_documento, b.descripcion, a.nro_documento, 
						(IFNULL(a.total, 0)+IFNULL(a.monto_igtf, 0)) AS monto_pagar, 
						 (SELECT SUM(monto) AS monto 
						 FROM cobros_cliente_factura 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS monto_pagado,    
						 (SELECT SUM(retivamonto) AS retivamonto 
						 FROM cobros_cliente_factura 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS retivamonto,   
						 (SELECT GROUP_CONCAT(retiva, '|') AS retiva 
						 FROM cobros_cliente_factura 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS retiva,     
						 (SELECT SUM(retislrmonto) AS retislrmonto 
						 FROM cobros_cliente_factura 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS retislrmonto, 
						 (SELECT GROUP_CONCAT(retislr, '|') AS retislr 
						 FROM cobros_cliente_factura 
						 WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS retislr, 
						 a.tipo_documento AS tdc
					FROM 
						salidas AS a
						LEFT OUTER JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
						LEFT OUTER JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
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
