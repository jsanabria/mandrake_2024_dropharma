<?php
session_start();

include "connect.php";

$cliente = $_REQUEST["cliente"];

$sql = "SELECT valor1 FROM parametro WHERE codigo = '019';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$factServi = $row["valor1"];


//echo "0";
//die();
?>
<div class="container-fluid">
	<div class="col-md-7 col-md-offset-1" align="center">
		<h3>Facturas por cobrar al cliente</h3>
		<table class="table table-condensed">
		<thead>
		  <tr>
		    <th class="col-sm-1">&nbsp;</th>
		    <th class="col-sm-2">Documento</th>
		    <th class="col-sm-2">Nro.</th>
		    <th class="col-sm-1">A Pagar</th>
		    <th class="col-sm-1">Abonos/Pagos</th>
		    <th class="col-sm-1">Monto a Abonar</th>
		    <th class="col-sm-1">Ret. IVA</th>
		    <th class="col-sm-1">#Ref. IVA</th>
		    <?php if($factServi == "S") { ?>
		    <th class="col-sm-1">Ret. ISLR</th>
		    <th class="col-sm-1">#Ref. ISLR</th>
		    <?php } ?>
		  </tr>
		</thead>
		<tbody>
		  <?php 
		  	$sql = "SELECT 
						a.id AS id_documento, a.tipo_documento, b.descripcion, a.nro_documento, 
						a.monto_pagar, a.monto_pagado, a.retiva, a.retivamonto, a.retislr, a.retislrmonto, a.tipodoc 
					FROM 
						view_x_cobrar AS a
						JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
					WHERE 
						a.cliente = $cliente 
						AND IFNULL(a.monto_pagar, 0) > 0 
						AND IFNULL(a.monto_pagar, 0) > (IFNULL(a.monto_pagado, 0)+IFNULL(a.retivamonto, 0)+IFNULL(a.retislrmonto, 0)) 
						AND a.fecha > '2021-07-31';"; 

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
				$x_retIVA = "x_retIVA_$i";
				$x_refIVA = "x_refIVA_$i";
				$x_retISLR = "x_retISLR_$i";
				$x_refISLR = "x_refISLR_$i";

				?>
				<tr>
					<td class="col-sm-1">
						<!--<input type="hidden" id="<?php echo $x_id; ?>" name="<?php echo $x_id; ?>" value="<?php echo "$id_documento-$tipo_documento"; ?>">-->
						<input type="checkbox" id="<?php echo $x_id; ?>" name="<?php echo $x_id; ?>" onclick="js:validar_check(<?php echo $i; ?>);" value="<?php echo "$id_documento-$tipo_documento"; ?>">
					</td>
					<td class="col-sm-1"><?php echo $row["descripcion"]; ?></td>
					<td class="col-sm-2"><?php echo $row["nro_documento"]; ?></td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_pagar; ?>" name="<?php echo $x_pagar; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagar, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_pagado; ?>" name="<?php echo $x_pagado; ?>" class="form-control text-right input-sm" value="<?php echo number_format($monto_pagado, 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_saldo; ?>" name="<?php echo $x_saldo; ?>" onchange="js:(validar_saldo(<?php echo $i; ?>) == false ? this.value = 0.00 : this.value = this.value);" class="form-control text-right input-sm" value="<?php echo number_format($saldo * ($row["tipodoc"] == "NC" ? -1 : 1), 2, ",", "."); ?>" size="12" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_retIVA; ?>" name="<?php echo $x_retIVA; ?>" onchange="js:(validar_saldo(<?php echo $i; ?>) == false ? this.value = 0.00 : this.value = this.value); focus_rIVA(<?php echo $i; ?>);" class="form-control text-right input-sm" value="<?php echo number_format($retivamonto, 2, ",", "."); ?>" size="10" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_refIVA; ?>" name="<?php echo $x_refIVA; ?>" onchange="js:validar_saldo(<?php echo $i; ?>);" onfocusout="js:focus_rIVA2(<?php echo $i; ?>);" class="form-control text-left input-sm" value="<?php echo $retiva; ?>" size="6" readonly="yes">
					</td>
		    <?php if($factServi == "S") { ?>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_retISLR; ?>" name="<?php echo $x_retISLR; ?>" onchange="js:(validar_saldo(<?php echo $i; ?>) == false ? this.value = 0.00 : this.value = this.value); focus_rISLR(<?php echo $i; ?>);" class="form-control text-right input-sm" value="<?php echo number_format($retislrmonto, 2, ",", "."); ?>" size="10" readonly="yes">
					</td>
					<td class="col-sm-1">
						<input type="text" id="<?php echo $x_refISLR; ?>" name="<?php echo $x_refISLR; ?>" onchange="js:validar_saldo(<?php echo $i; ?>);" onfocusout="js:focus_rISLR2(<?php echo $i; ?>);" class="form-control text-left input-sm" value="<?php echo $retislr; ?>" size="6" readonly="yes">
					</td>
			<?php } ?>
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

		if(isNaN($("#x_retIVA_" + xi).val().replace(/\./g, "").replace(/\,/g, "."))) {
			$("#x_retIVA_" + xi).val("0,00");
			$("#x_retIVA_" + xi).focus();
			return false;
		}

		<?php if($factServi == "S") { ?>
		if(isNaN($("#x_retISLR_" + xi).val().replace(/\./g, "").replace(/\,/g, "."))) {
			$("#x_retISLR_" + xi).val("0,00");
			$("#x_retISLR_" + xi).focus();
			return false;
		}
		<?php } ?>

		//$("#x_saldo_" + xi).val(number_format(parseFloat($("#x_saldo_" + xi).val()), 2, ",", "."));
		var pagar = parseFloat($("#x_pagar_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var pagado = parseFloat($("#x_pagado_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		var saldo = parseFloat($("#x_saldo_" + xi).val().replace(/\./g, "").replace(/\,/g, "."));
		var retIVA = parseFloat($("#x_retIVA_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		<?php if($factServi == "S") { ?>
		var retISLR = parseFloat($("#x_retISLR_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
		<?php } else { echo "var retISLR = 0;"; } ?>
		var Cantidad = $("#xCantidad").val();
		var i = 0;
		var monto = 0;

		monto = pagado + saldo + retIVA + retISLR;

		//alert(pagar + " <  " + monto);

		if(pagar < monto) {
			alert("El monto del saldo no puede ser mayor al monto a pagar.");
			// $("#x_saldo_" + xi).val(number_format(pagar - (pagado + retIVA + retISLR), 2, ",", "."));
			$("#x_saldo_" + xi).val(number_format(0, 2, ",", "."));
			$("#x_saldo_" + xi).focus();
			return false;
		}


		monto = 0;
		for(i=0; i<Cantidad; i++) { 
			if($("#x_id_" + i).is(':checked'))
				monto += parseFloat($("#x_saldo_" + i).val().replace(/\./g, "").replace(/\,/g, "."));
		}

		if(monto==0) $("#x_monto").val("");
		else $("#x_monto").val(monto);

		$("#x_saldo_" + xi).val(number_format(saldo, 2, ",", "."));
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
    	if($("#x_id_" + xi).is(':checked')) {
    		$("#x_saldo_" + xi).prop('readonly', false);
			$("#x_retIVA_" + xi).val(number_format(0, 2, ",", "."));
    		$("#x_retIVA_" + xi).prop('readonly', false);
    		$("#x_refIVA_" + xi).prop('readonly', false);
    		<?php if($factServi == "S") { ?>
			$("#x_retISLR_" + xi).val(number_format(0, 2, ",", "."));
    		$("#x_retISLR_" + xi).prop('readonly', false);
    		$("#x_refISLR_" + xi).prop('readonly', false);
    		<?php } ?>
    	}
    	else {
			var pagar = parseFloat($("#x_pagar_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
			var pagado = parseFloat($("#x_pagado_" + xi).val().replace(/\./g, "").replace(/\,/g, ".")); 
			$("#x_saldo_" + xi).val(number_format(pagar-pagado, 2, ",", "."));
    		$("#x_saldo_" + xi).prop('readonly', true);

			$("#x_retIVA_" + xi).val(number_format(pagar-pagado, 2, ",", "."));
			$("#x_retIVA_" + xi).prop('readonly', true);
    		$("#x_refIVA_" + xi).prop('readonly', true);
    		<?php if($factServi == "S") { ?>
			$("#x_retISLR_" + xi).val(number_format(pagar-pagado, 2, ",", "."));
    		$("#x_retISLR_" + xi).prop('readonly', true);
    		$("#x_refISLR_" + xi).prop('readonly', true);
    		<?php } ?>
    	}

    	validar_saldo(xi);
    }

    function focus_rIVA(xi) {
		// document.getElementById("x_refIVA_" + xi).focus(); 
		var monto = parseFloat($("#x_retIVA_" + xi).val().replace(/\./g, "").replace(/\,/g, "."));
		$("#x_retIVA_" + xi).val(number_format(monto, 2, ",", "."));
		$("#x_refIVA_" + xi).focus();
    }

    function focus_rISLR(xi) {
		// document.getElementById("x_refISLR_" + xi).focus(); 
		var monto = parseFloat($("#x_retISLR_" + xi).val().replace(/\./g, "").replace(/\,/g, "."));
		$("#x_retISLR_" + xi).val(number_format(monto, 2, ",", "."));
		$("#x_refISLR_" + xi).focus();
    }

    function focus_rIVA2(xi) {
		// document.getElementById("x_refIVA_" + xi).focus(); 
		$("#btnAction").prop('disabled', true);

		var monto = parseFloat($("#x_retIVA_" + xi).val());  
		var ref = $("#x_refIVA_" + xi).val().trim();

		if(monto != 0.00 && ref == "") {
			$("#x_refIVA_" + xi).focus();
			$("#btnAction").prop('disabled', true);
		} 
		else 
			$("#btnAction").prop('disabled', false);

		if(monto == 0.00) $("#x_refIVA_" + xi).val("");
    }

    function focus_rISLR2(xi) {
    	$("#btnAction").prop('disabled', true);

		// document.getElementById("x_refISLR_" + xi).focus();    	
		var monto = parseFloat($("#x_retISLR_" + xi).val());  
		var ref = $("#x_refISLR_" + xi).val().trim();

		if(monto != 0.00 && ref == "") {
			$("#x_refISLR_" + xi).focus();
			$("#btnAction").prop('disabled', true);
		} 
		else 
			$("#btnAction").prop('disabled', false);
		
		if(monto == 0.00) $("#x_refISLR_" + xi).val("");
    }
</script>
<?php include "connect.php"; ?>
