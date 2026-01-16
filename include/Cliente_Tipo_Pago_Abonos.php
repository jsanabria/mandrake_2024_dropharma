<?php
session_start();

include "connect.php"; 

$cliente = $_REQUEST["cliente"];
$tipo_pago = $_REQUEST["tipo_pago"];
$pagos = $_REQUEST["pagos"];

$arr = explode(",-,", $pagos);

$total = 0.00;
$abono = 0.00;

$sql = "SELECT tasa FROM tasa_usd
		WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tasa_cambio = 1; // $row["tasa"];

?>
<div class="container-fluid">
	<div class="col-md-10" align="center">
		<table class="table table-condensed">
		<thead>
		  <?php 
		  	if($tipo_pago != "") {
		  		?>
				  <tr>
				    <th class="col-sm-1">Tipo de Pago</th>
				    <?php 
				    if($tipo_pago=="RC") { 
				    	echo '<th class="col-sm-2 text-right"></th>'; // BCO
				    	echo '<th class="col-sm-2 text-right">Disponible</th>';
				    } 
				    elseif ($tipo_pago=="EF") { 
				    	echo '<th class="col-sm-2 text-right"></th>'; // BCO
				    	echo '<th class="col-sm-2 text-right"></th>';
				    } 
				    else {
				    	echo '<th class="col-sm-2 text-right">Banco</th>'; // BCO
				    	echo '<th class="col-sm-2 text-left">Referencia</th>';
				    }
				    ?>
				    <th class="col-sm-2 text-right">Monto</th>
				    <th class="col-sm-1">Moneda</th>
				    <th class="col-sm-2">&nbsp</th>
				    <th class="col-sm-1">&nbsp</th>
				  </tr>
		  		<?php
		  	}
		  ?>
		</thead>
		<tbody>
		  <?php 
		  	if($tipo_pago != "") {
			  	$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '$tipo_pago';"; 
				$rs = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($rs);
				$tipo = $row["valor2"];

				?>
				<tr>
					<td class="col-sm-2">
						<input type="hidden" id="tipo_pago" name="tipo_pago" value="<?php echo $tipo_pago; ?>" class="form-control text-left input-sm">
						<input type="text" id="tipo" name="tipo" value="<?php echo $tipo; ?>" class="form-control text-left input-sm" readonly="yes" size="15">
					</td>


					<td class="col-sm-2">
						<?php 
						switch($tipo_pago) {
						case "RC":
							$combo = '<input type="hidden" id="banco" name="banco" value="" class="form-control text-left input-sm" size="10" readonly="yes">'; // BCO
							break;
						case "EF":
							$combo = '<input type="hidden" id="banco" name="banco" value="" class="form-control text-left input-sm" size="10" readonly="yes">'; // BCO
							break;
						default:
						  	$sql = "SELECT id, banco FROM view_banco WHERE banco LIKE '%BOLIVARES%' ORDER BY banco;"; 
							$rs = mysqli_query($link, $sql);
							$combo = '<select id="banco" name="banco" class="form-control">';
							$combo .= '<option value=""></option>';
							while($row = mysqli_fetch_array($rs)) {
								$combo .= '<option value="' . $row["id"] . '">' . $row["banco"] . '</option>';
							}
							$combo .= '</select>';
							break;
						}
						echo $combo;
						?>
					</td>


					<td class="col-sm-2">
						<?php 
						switch($tipo_pago) {
						case "RC":
							$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
							$rs = mysqli_query($link, $sql);
							if($row = mysqli_fetch_array($rs)) {
								$combo = '<input type="hidden" id="referencia" name="referencia" value="' . $row["id"] . '"><input type="text" id="disponible" name="disponible" value="' . number_format($row["saldo"], 2, ".", ",") . '" class="form-control text-right input-sm" size="10" readonly="yes">';
							} 
							else {
								$combo = '<input type="hidden" id="referencia" name="referencia" value=""><input type="text" id="disponible" name="disponible" value="0.00" class="form-control text-right input-sm" size="10" readonly="yes">';
								}
							break;
						case "EF":
							$combo = '<input type="hidden" id="referencia" name="referencia" value="" class="form-control text-left input-sm" size="10" readonly="yes">';
							break;
						default:
							$combo = '<input type="text" id="referencia" name="referencia" value="" class="form-control text-left input-sm" size="10">';
							break;
						}
						echo $combo;
						?>
					</td>
					<td class="col-sm-2 text-right">
						<input type="number" id="pagar" name="pagar" value="" class="form-control text-right input-sm" size="10">
					</td>
					<td class="col-sm-2">
					  <?php 
					  	if($tipo_pago == "RC") {
						  	//$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2='default';"; 
						  	$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND SUBSTRING(valor1, 1, 3) = 'Bs.';"; 
							$rs = mysqli_query($link, $sql);
							$combo = '<select id="moneda" name="moneda" class="form-control">';
					  	} 
					  	else {
						  	//$sql = "SELECT valor1 FROM parametro WHERE codigo = '006';"; 
						  	$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND SUBSTRING(valor1, 1, 3) = 'Bs.';"; 
							$rs = mysqli_query($link, $sql);
							$combo = '<select id="moneda" name="moneda" class="form-control">';
							$combo .= '<option value=""></option>';
					  	}
						while($row = mysqli_fetch_array($rs)) {
							$combo .= '<option value="' . $row["valor1"] . '">' . $row["valor1"] . '</option>';
						}
						$combo .= '</select>';
						echo $combo;
					  ?>
					</td>
					<td class="col-sm-1">&nbsp</td>
					<td>
						<a class="btn btn-primary" id="agregar" name="agregar">Agregar</a>
					</td>
				</tr>
				<?php
		  	}

		  	if(count($arr) > 0) {
		  		?>
				  <tr>
				    <th class="col-sm-2">Tipo de Pago</th>
				    <th class="col-sm-2">Banco</th>
				    <th class="col-sm-2">Referencia</th>
				    <th class="col-sm-2 text-right">Monto</th>
				    <th class="col-sm-2 text-center">Moneda</th>
				    <th class="col-sm-2 text-right">Monto Bs.</th>
				    <th class="col-sm-1">&nbsp</th>
				  </tr>
		  		<?php

			  	foreach ($arr as $key => $value) {
			  		if(trim($value) != "") {
			  			$arr2 = explode("|", $value);	
			  			if(count($arr2) > 2) {
							$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $arr2[1] . "';";
							$rs = mysqli_query($link, $sql);
							$row = mysqli_fetch_array($rs);
					  		?>
							  <tr>
							    <td class="col-sm-2"><?php echo $row["valor2"]; ?></td>
							    <td class="col-sm-2"><?php echo $arr2[6]; ?></td>
							    <td class="col-sm-2"><?php echo $arr2[2]; ?></td>
							    <td class="col-sm-2 text-right"><?php echo number_format(floatval($arr2[4]), 2, ".", ","); ?></td>
							    <td class="col-sm-2 text-center"><?php echo $arr2[5]; ?></td>
							<?php 
					  		/////////////////
					  		$monto_moneda = floatval($arr2[4]);
							$sql = "SELECT tasa FROM tasa_usd
									WHERE moneda = '" . $arr2[5] . "' ORDER BY id DESC LIMIT 0, 1;"; 
							$rs = mysqli_query($link, $sql);
							$row = mysqli_fetch_array($rs);
							$tasa = floatval($row["tasa"]);

							$monto_bs = $tasa * $monto_moneda;

					    	$sql = "SELECT tasa FROM tasa_usd
					    			WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
							$rs = mysqli_query($link, $sql);
							$row = mysqli_fetch_array($rs);
					    	$tasa_usd = 1; // floatval($row["tasa"]);

					    	$monto_usd = $monto_bs / $tasa_usd;
					  		/////////////////

					  		$total += round($monto_usd, 2);
					  		if($arr2[1] == "RC") $abono += floatval($arr2[4]);
							?>
							    <th class="col-sm-2 text-right"><?php echo number_format($arr2[1] == "ND" ? (-1)*$monto_usd : $monto_usd, 2, ".", ","); ?></th>
							    <td class="col-sm-1"><a class="btn btn-primary" onclick="js:EliminarItem('<?php echo $value ?>')">Eliminar</a></td>
							  </tr>
					  		<?php

			  			}
			  		}
			  	 } 
		  	}
		  ?>
		</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	    $("#x_pago").val((<?php echo $total ?>).toFixed(2));
	    $("#abono").val(<?php echo $abono ?>);

		if(parseFloat($("#x_pago").val()) == 0.00 || parseFloat($("#monto").val()) == 0.00 || parseFloat($("#x_pago").val()) < parseFloat($("#monto").val())) { 
			$("#btn-action").prop('disabled', true);
			$("#" + $("#xctrl").val()).css({ 'background-color': 'red', 'color': 'white' });
		}
		else {
			$("#btn-action").prop('disabled', false);
			$("#" + $("#xctrl").val()).css({ 'background-color': 'green', 'color': 'white' });
		}

		if(parseFloat($("#x_saldo_0").val()) == 0.00) {
			$("#btn-action").prop('disabled', false);
			$("#" + $("#xctrl").val()).css({ 'background-color': 'green', 'color': 'white' });
		}

		var xMonto = parseFloat($("#monto").val());
		var xTot = parseFloat($("#x_pago").val());
		var xsal = parseFloat($("#saldo").val());
		$("#pagar").val((xMonto-xTot).toFixed(2));
		$("#saldo").val((xMonto-xTot).toFixed(2));

		$("#" + $("#xctrl").val()).val((xMonto-xTot).toFixed(2));

		if($("#tipo_pago").val() == "RC") {
			$("#disponible").val(($("#disponible").val()-$("#abono").val()).toFixed(2));
		}

	});

	$("#agregar").click(function() {
	  var tipo_pago = $("#tipo_pago").val();
	  var banco = $("#banco").val().trim();
	  var referencia = $("#referencia").val().trim();
	  var disponible = tipo_pago=="RC" ? $("#disponible").val() : 0;
	  var pagar = parseFloat($("#pagar").val());
	  var moneda = $("#moneda").val();
	  var monto = $("#monto").val();
	  var abono = $("#abono").val();


	  if(isNaN(pagar)) {
  		alert("El monto no es correcto");
  		return false;
	  }

	  if(pagar <= 0) {
  		// alert("El monto debe ser mayor a 0");
  		// return false;
	  }

  	  if(moneda == "") {
  		alert("Debe seleccionar tipo de moneda");
  		return false;
  	  }

  	  if(tipo_pago != "EF" && referencia == "") {
  		alert("Debe colocar un Nro. de Referencia");
  		return false;
  	  }

	  $("#pagos").val($("#pagos").val() + ",-," + "|" + tipo_pago + "|" + referencia + "|" + disponible + "|" + pagar + "|" + moneda + "|" + banco + "|");
	  //alert("!!! AGREGADO !!!"); 

	  var arr = $("#pagos").val();
	  $('#x_metodo_pago').val('').trigger('change');

	});	

	function EliminarItem(Item) {
		$("#pagos").val($("#pagos").val().replace(Item, ''));
		$('#x_metodo_pago').val('').trigger('change');
	}
</script>
<?php include "connect.php"; ?>
