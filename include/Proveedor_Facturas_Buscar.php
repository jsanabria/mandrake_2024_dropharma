<?php
session_start();

include "connect.php";

$proveedor = $_REQUEST["proveedor"];


//echo "0";
//die();
?>
<div class="container">
	<div class="col-md-7 col-md-offset-1" align="center">
		<h3>Facturas por pagar al proveedor</h3>
		<table class="table table-condensed">
		<thead>
		  <tr>
		    <th class="col-sm-1">&nbsp;</th>
		    <th class="col-sm-3">Documento</th>
		    <th class="col-sm-2">Nro.</th>
		    <th class="col-sm-2">A Pagar</th>
		    <th class="col-sm-2">Abonos/Pagos</th>
		    <th class="col-sm-2">Monto a Abonar</th>
		  </tr>
		</thead>
		<tbody>
		  <?php 
		  	$sql = "SELECT 
						a.id AS id_documento, a.tipo_documento, b.descripcion, a.nro_documento, 
						a.monto_pagar, a.monto_pagado, a.tipodoc   
					FROM 
						view_x_pagar AS a
						JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
					WHERE 
						a.proveedor = $proveedor 
						AND IFNULL(a.monto_pagar, 0) > 0 
						AND IFNULL(a.monto_pagar, 0) > IFNULL(a.monto_pagado, 0) 
						AND a.fecha > '2021-07-31';"; 

			$rs = mysqli_query($link, $sql);
			$i = 0;
			while($row = mysqli_fetch_array($rs)) { 
				$id_documento = $row["id_documento"];
				$tipo_documento = $row["tipo_documento"];
				$monto_pagar = floatval($row["monto_pagar"]);
				$monto_pagado = floatval($row["monto_pagado"]);
				$saldo = floatval($row["monto_pagar"]) - floatval($row["monto_pagado"]);

				$x_id = "x_id_$i";
				$x_pagar = "x_pagar_$i";
				$x_pagado = "x_pagado_$i";
				$x_saldo = "x_saldo_$i";

				?>
				<tr>
					<td class="col-sm-1">
						<!--<input type="hidden" id="<?php echo $x_id; ?>" name="<?php echo $x_id; ?>" value="<?php echo "$id_documento-$tipo_documento"; ?>">-->
						<input type="checkbox" id="<?php echo $x_id; ?>" name="<?php echo $x_id; ?>" onclick="js:validar_check(<?php echo $i; ?>);" value="<?php echo "$id_documento-$tipo_documento"; ?>">
					</td>
					<td class="col-sm-3"><?php echo $row["descripcion"]; ?></td>
					<!-- <td class="col-sm-2"><?php echo $row["tipodoc"] . "-" . $row["nro_documento"]; ?></td> -->
					<td class="col-sm-2"><?php echo $row["nro_documento"]; ?></td>
					<td class="col-sm-2">
						<input type="text" id="<?php echo $x_pagar; ?>" name="<?php echo $x_pagar; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagar, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-2">
						<input type="text" id="<?php echo $x_pagado; ?>" name="<?php echo $x_pagado; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagado, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-2">
						<input type="text" id="<?php echo $x_saldo; ?>" name="<?php echo $x_saldo; ?>" onchange="js:validar_saldo(<?php echo $i; ?>);" class="form-control text-right input-sm" value="<?php echo number_format($saldo * ($row["tipodoc"] == "NC" ? -1 : 1), 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
				</tr>
				<?php
				$i++;
			}
		  ?>
		  <input type="hidden" id="xCantidad" name="xCantidad" value="<?php echo $i; ?>"></input>
		</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	function validar_saldo(xi) {
		if(isNaN($("#x_saldo_" + xi).val().replace(/\./g, "").replace(/\,/g, "."))) {
			$("#x_saldo_" + xi).val("0,00");
			$("#x_saldo_" + xi).focus();
			return false;
		}

		//$("#x_saldo_" + xi).val(number_format(parseFloat($("#x_saldo_" + xi).val()), 2, ",", "."));
		var pagar = parseFloat($("#x_pagar_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var pagado = parseFloat($("#x_pagado_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var saldo = parseFloat($("#x_saldo_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var Cantidad = $("#xCantidad").val();
		var i = 0;
		var monto = 0;

		if(pagar < saldo) {
			alert("El monto del saldo no puede ser mayor al monto a pagar.");
			$("#x_saldo_" + xi).val(number_format(pagar-pagado, 2, ",", "."));
			$("#x_saldo_" + xi).focus();
			return false;
		}

		for(i=0; i<Cantidad; i++) { 
			if($("#x_id_" + i).is(':checked'))
				monto += parseFloat($("#x_saldo_" + i).val().replace(/\./g, "").replace(/\,/g, "."));
		}

		if(monto==0) $("#x_monto").val("");
		else $("#x_monto").val(monto);
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        return x1 + x2;
    }	

    function validar_check(xi) {
    	if($("#x_id_" + xi).is(':checked')) $("#x_saldo_" + xi).prop('readonly', false);
    	else {
			var pagar = parseFloat($("#x_pagar_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
			var pagado = parseFloat($("#x_pagado_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
			$("#x_saldo_" + xi).val(number_format(pagar-pagado, 2, ",", "."));
    		$("#x_saldo_" + xi).prop('readonly', true);
    	}

    	validar_saldo(xi);
    }
</script>
<?php include "connect.php"; ?>
