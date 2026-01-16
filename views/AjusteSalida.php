<?php

namespace PHPMaker2024\mandrake;

// Page object
$AjusteSalida = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$codcli = isset($_REQUEST["codcli"]) ? $_REQUEST["codcli"] : 0;
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? $_REQUEST["tipo_documento"] : "TDCASA";

$titulo = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo_documento';");

$username = CurrentUserName();

if($id == 0) {
    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
    $row = ExecuteRow($sql); 
    $tasa = floatval($row["tasa"]);

    $sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    $row = ExecuteRow($sql);
    $moneda = $row["moneda"];

    $sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = '$tipo_documento';";
    $consecutivo = intval(ExecuteScalar($sql)) + 1;
    $nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO salidas
                (id, tipo_documento, username, fecha,
                cliente, nro_documento,
                nota, estatus, moneda, factura, tasa_dia)
            VALUES 
                (NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "',
                $codcli, '$nro_documento',
                '', 'NUEVO', '$moneda', 'N', $tasa);"; 
    Execute($sql);

    // Obtengo el id de la nueva factura
    $row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
    $id = $row["id"];
}

$sql = "SELECT 
	DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_factura, 
	c.descripcion AS tipo, b.nombre AS cliente, a.nro_documento, 
	a.fecha, a.tipo_documento, a.nota, a.factura, 
	a.ci_rif, a.nombre, a.direccion, a.telefono, a.email, a.tasa_dia, IFNULL(a.descuento, 0) AS descuento  
FROM 
	salidas AS a 
	LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
	LEFT OUTER JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
WHERE 
	a.id = '$id';"; 
$row = ExecuteRow($sql);

$fecha = $row["fecha_factura"];
$tipo = $row["tipo"];
$tipo_documento = $row["tipo_documento"];
$cliente = $row["cliente"];
$nro_documento = $row["nro_documento"];
$nota = $row["nota"];

$factura = $row["factura"]; 
$ci_rif = $row["ci_rif"]; 
$nombre = $row["nombre"]; 
$direccion = $row["direccion"]; 
$telefono = $row["telefono"]; 
$email = $row["email"];

$tasa_dia = floatval($row["tasa_dia"]); 
$descuento = floatval($row["descuento"]);
?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
<h1><?= $titulo ?></h1>
<div class="container-fluid">
		<div class="row">
				<div class="col-md-12">
		<a class="btn btn-primary" onclick="js:ActualizarCabecera();">Guardar Cabecera</a>
		&nbsp;
		<a class="btn btn-primary" onclick="js:VaciarCesta();">Vaciar Cesta</a>
		&nbsp;
		<?php 
		if($factura == "S") { 
				echo '<a class="btn btn-primary" target="_blank" href="reportes/factura_ajuste_de_salida.php?id=' . $id . '&tipo=' . $tipo_documento . '">Imprimir Factura</a>';
		}
		?>
					<div class="table-responsive">
					  <table class="table table-bordered table-condensed table-striped table-striped ewViewTable">
					  		<tbody>
						  		<tr>
						  			<td><span><b>Fecha:</b></span></td>
						  			<td><span><?php echo $fecha; ?></span></td>
						  			<td><span><b>Nro Documento: (<?php echo $nro_documento; ?>)</b></span></td>
						  			<td><span>
						  				<select id="factura" name="factura" class="form-select form-select-sm">
						  					<option value="S" <?php echo $factura=="S"?"selected":""; ?>>Recibo: SI</option>
						  					<option value="N" <?php echo $factura=="N"?"selected":""; ?>>Recibo: NO</option>
						  				</select>
						  			</span></td>
						  		</tr>
						  		<tr>
						  			<td><span><b>Cliente:</b></span></td>
						  			<td><span><?php echo $cliente; ?></span></td>
						  			<td><span><b>Nota:</b></span></td>
						  			<td><span><textarea id="nota" name="nota" class="form-control" cols="30" rows="2"><?php echo $nota; ?></textarea></span></td>
						  		</tr>
					  		</tbody>
					  </table>
					</div>
				</div>
				<div class="col-md-12" class="table-responsive">
		  			<div id="DatosFactura">
					  <table class="table table-bordered table-condensed table-striped table-striped ewViewTable">
					  		<tbody>
							  		<tr>
							  			<td><span><b>CI / RFI:</b> <font color="red">*</font></span></td>
							  			<td><span><input type="text" id="ci_rif" name="ci_rif" class="form-control" value="<?php echo $ci_rif; ?>"></span></td>
							  			<td><span><b>Nombre:</b> <font color="red">*</font></span></td>
							  			<td><span><input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $nombre; ?>"></span></td>
							  		</tr>
							  		<tr>
							  			<td><span><b>Direcci&oacute;n:</b> <font color="red">*</font></span></td>
							  			<td><span><input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $direccion; ?>"></span></td>
							  			<td><span><b>Tel&eacute;fono:</b> <font color="red">*</font></span></td>
							  			<td><span><input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo $telefono; ?>"></span></td>
							  		</tr>
							  		<tr>
							  			<td><span><b>Tasa del D&iacute;a:</b> </span></td>
							  			<td><span><input type="text" id="tasa" name="tasa" class="form-control" value="<?php echo $tasa_dia; ?>"></span></td>
							  			<td><span><b>Descuento:</b> </span></td>
							  			<td><span><input type="text" id="descuento" name="descuento" class="form-control" value="<?php echo $descuento; ?>"></span></td>
							  		</tr>
					  		</tbody>
					  </table>
					</div>
				</div>
		</div>

		<div class="clearfix">
			<table class="table table-bordered table-condensed table-striped table-striped ewViewTable">
		  		<tbody>
			  		<tr>
			  			<td><span><b>Buscar Art&iacute;culo: </b><span class="glyphicon glyphicon-barcode"></span> <input type="text" id="findme" name="findme" class="form-control"></span></td>
			  		</tr>
			  		<tr>
			  			<td><span><div id="ResultadoBusqueda"></div></span></td>
			  		</tr>
			  	</tbody>
			</table>
		</div>

		<div id="ResultadoLote">
		</div>

		<div id="ResultadoDetalle">
			<div class="row">
				<div class="panel panel-default ewGrid entradas_salidas">
					<div class="table-responsive ewGridMiddlePanel">
						  <table class="table table-bordered table-condensed table-striped">
						  		<thead>
							  		<tr>
							  			<th></th>
							  			<th>Fabricante</th>
							  			<th>Art&iacute;culo</th>
							  			<th>Lot, Venc</th>
							  			<th>Cantidad</th>
							  			<th>Unidad Medida</th>
							  			<th>Precio</th>
							  			<th>Total</th>
							  		</tr>
						  		</thead>
						  		<tbody>
									<div id="DatosArticulo"></div>
									<div id="DatosDetalle">
										<?php 
										$sql = "SELECT COUNT(*) AS cantidad FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = '$id';";
										$cantidad = ExecuteScalar($sql);  

										for($i=0; $i<$cantidad; $i++) {
											$sql= "SELECT 
														a.id, 
														b.nombre AS fabricante, 
														CONCAT(IFNULL(c.principio_activo, ''), ', ', 
																IFNULL(c.presentacion, ''), ', ', 
																IFNULL(c.nombre_comercial, '')) AS articulo, 
															a.cantidad_articulo, d.descripcion AS unidad_medida, 
															d.cantidad, a.articulo AS codart, a.articulo_unidad_medida,  
															a.lote, a.fecha_vencimiento, a.precio_unidad, a.precio 
													FROM 
														entradas_salidas AS a 
														LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
														LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
														LEFT OUTER JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida 
													WHERE 
														a.tipo_documento = '$tipo_documento' AND a.id_documento = '$id' 
													 ORDER BY articulo LIMIT $i, 1;";
											$row = ExecuteRow($sql);

											?>
									  		<tr>
									  			<td>
									  				<a class="btn btn-primary" id="eliminar" name="eliminar" onclick="EliminarItem(<?php echo $row["id"]; ?>)"><span class="fa fa-trash"></span></a>
									  			</td>
									  			<td><?php echo $row["fabricante"]; ?></td>
									  			<td><?php echo $row["articulo"]; ?></td>
									  			<td><?php 
									  				echo $row["lote"] . ", " . ($row["fecha_vencimiento"] == "1990-01-01" ? "" : $row["fecha_vencimiento"]); 
									  				?>
									  			</td>
									  			<td><?php echo $row["cantidad_articulo"]; ?></td>
									  			<td><?php echo $row["unidad_medida"] . ', ' . $row["cantidad"]; ?></td>
									  			<td><?php echo number_format(floatval($row["precio_unidad"]), 2, ",", "."); ?></td>
									  			<td><?php echo number_format(floatval($row["precio"]), 2, ",", "."); ?></td>
									  		</tr>
										<?php
										}
										?>
									</div>
						  		</tbody>
						  </table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="panel panel-default ewGrid entradas_salidas">
					<div class="table-responsive ewGridMiddlePanel">
						<?php 
							$sql = "SELECT
										SUM(precio) AS precio, 
										SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento, 
										SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado, 
										SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
										SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
									FROM entradas_salidas
									WHERE tipo_documento = '$tipo_documento' AND 
										id_documento = '$id'"; 
							$row = ExecuteRow($sql);
							$exento = floatval($row["exento"]);
							$gravado = floatval($row["gravado"]);


							$sql = "SELECT 
										a.monto_total, a.alicuota_iva, a.iva, a.total, 
										IFNULL(a.tasa_dia, 0) AS tasa_dia, 
                                        IFNULL(a.monto_usd, 0) AS monto_usd, 
                                        IFNULL(a.descuento, 0) AS descuento, a.monto_sin_descuento 
									FROM 
										salidas AS a 
									WHERE 
										a.id = $id;"; 
									$row = ExecuteRow($sql);
						?>
						  <table class="table table-bordered table-condensed table-striped">
						  		<thead>
							  		<tr>
							  			<th colspan="2">Resumen Venta</th>
							  		</tr>
						  		</thead>
						  		<tbody>
						  			<tr>
							  			<td>Monto a aplicar <?php echo number_format((isset($row["descuento"]) ? $row["descuento"] : 0), 2, ",", ".");  ?>% de Descuento</td>
							  			<td><?php echo number_format((isset($row["monto_sin_descuento"]) ? $row["monto_sin_descuento"] : 0), 2, ",", "."); ?></td>
							  		</tr>
						  			<tr>
							  			<td>Monto Exento</td>
							  			<td><?php echo number_format($exento, 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>Monto Gravado</td>
							  			<td><?php echo number_format($gravado, 2, ",", ".") ?></td>
							  		</tr>

						  			<tr>
							  			<td>Monto</td>
							  			<td><?php echo number_format((isset($row["monto_total"]) ? $row["monto_total"] : 0), 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>Alicuota IVA</td>
							  			<td><?php echo number_format((isset($row["alicuota_iva"]) ? $row["alicuota_iva"] : 0), 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>IVA</td>
							  			<td><?php echo number_format((isset($row["iva"]) ? $row["iva"] : 0), 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>Total</td>
							  			<td><?php echo number_format((isset($row["total"]) ? $row["total"] : 0), 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>Tasa del D&iacute;a</td>
							  			<td><?php echo number_format($row["tasa_dia"], 2, ",", ".") ?></td>
							  		</tr>
						  			<tr>
							  			<td>Monto USD</td>
							  			<td><?php echo number_format((isset($row["monto_usd"]) ? $row["monto_usd"] : 0), 2, ",", ".") ?></td>
							  		</tr>
							  	</tbody>
						   </table>
					</div>
				</div>
			<div>
		</div>
</div>
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<input type="hidden" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>">
<input type="hidden" id="username" name="username" value="<?php echo CurrentUserName(); ?>">



<script type="text/javascript">
	$(document).ready(function(){
		var factura = $("#factura").val().trim();

		if(factura == "S")
	    	$("#DatosFactura").show();
	   	else
	   		$("#DatosFactura").hide();
	});

	$("#factura").change(function() {
		var factura = $(this).val().trim();

		if(factura == "S")
	    	$("#DatosFactura").show();
	   	else
	   		$("#DatosFactura").hide();
	});

	$("#findme").keyup(function() {
		var findme = $(this).val().trim();

		var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
			"findme": findme
		};
		var url = "include/findme_item.php";

		//$("#enviar").prop('disabled', true);
	
		$.ajax({
			data: parametros,
			url: url,
			type: 'post',
			beforeSend: function () {//elemento que queramos poner mientras ajax carga
				//$("#message").html('<img src="images/ajax.gif" width="60" />');
				$("#ResultadoBusqueda").html('');
				$("#ResultadoLote").html('');
				// $("#findme").val('');
			},
			success: function (response) {//resultado de la función
				if(response > 0) {
					$("#ResultadoBusqueda").html('<span class="glyphicon glyphicon-ok-sign"></span> <strong>Art&iacute;culo Encontrado</strong>');
					BuscarLote(response);
				}
				else 
					$("#ResultadoBusqueda").html(response);

				return true;
			}
		});
	});

	/*$("#xItem").change(function() {
		var id = $(this).val();
		BuscarLote(id);
	});*/

	function BuscarLote(id) {
		var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
			"id": id
		};
		var url = "include/findme_lote.php";

		//$("#enviar").prop('disabled', true);
	
		$.ajax({
			data: parametros,
			url: url,
			type: 'post',
			beforeSend: function () {//elemento que queramos poner mientras ajax carga
				//$("#message").html('<img src="images/ajax.gif" width="60" />');
				$("#ResultadoLote").html('');
			},
			success: function (response) {//resultado de la función
				$("#ResultadoLote").html(response);
				$("#agregar").attr('disabled', true);
				return true;
			}
		});
	}

	function EliminarItem(id) { 
		username = $("#username").val().trim();

		if(confirm("Seguro de eliminar el item")) {
			var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
				"id": id,
				"username":username
			};
			var url = "include/findme_eliminar.php";

			//$("#enviar").prop('disabled', true);
		
			$.ajax({
				data: parametros,
				url: url,
				type: 'post',
				beforeSend: function () {//elemento que queramos poner mientras ajax carga
					//$("#message").html('<img src="images/ajax.gif" width="60" />');
					$("#ResultadoDetalle").html('');
				},
				success: function (response) {//resultado de la función
					$("#ResultadoDetalle").html(response);
					$("#ResultadoBusqueda").html('');
					$("#ResultadoLote").html('');
					return true;
				}
			});
		}
	}

	function AgregarItem() {
		if(validar_existencia() == false) return false;

		id = $("#id").val();

		nota = $("#nota").val();
		factura = $("#factura").val();
		ci_rif = $("#ci_rif").val().trim();
		nombre = $("#nombre").val().trim();
		direccion = $("#direccion").val().trim();
		telefono = $("#telefono").val().trim();
		username = $("#username").val().trim();

		if(factura == "S") {
			if(ci_rif == "" || nombre == "" || direccion == "" || telefono == "") {
				alert("Faltan datos fiscales; Verifique");

				if(ci_rif == "") { $("#ci_rif").focus(); return false; }
				if(nombre == "") { $("#nombre").focus(); return false; }
				if(direccion == "") { $("#direccion").focus(); return false; }
				if(telefono == "") { $("#telefono").focus(); return false; }
			}

		}
		else {
			ci_rif = "";
			nombre = "";
			direccion = "";
			telefono = "";
		}

		var xlote = $("#lote").val().split("|");
		var cantidad = $("#cantidad").val();

		lote = xlote[0];
		fecha = xlote[1];
		existencia = xlote[2];
		almacen = xlote[3];

		articulo = $("#id_articulo").val();
		cantidad = $("#cantidad").val();
		unidad = $("#unidad").val();

		var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
			"id": id,
			"factura": factura,
			"ci_rif": ci_rif,
			"nombre": nombre,
			"direccion": direccion,
			"telefono": telefono,
			"articulo": articulo,
			"lote": lote,
			"fecha": fecha,
			"existencia": existencia,
			"almacen": almacen,
			"cantidad": cantidad,
			"unidad": unidad,
			"nota": nota,
			"username":username
		};
		var url = "include/findme_agregar.php";

		//$("#enviar").prop('disabled', true);
	
		$.ajax({
			data: parametros,
			url: url,
			type: 'post',
			beforeSend: function () {//elemento que queramos poner mientras ajax carga
				//$("#message").html('<img src="images/ajax.gif" width="60" />');
				$("#ResultadoDetalle").html('');
			},
			success: function (response) {//resultado de la función
				$("#ResultadoDetalle").html(response);
				$("#ResultadoBusqueda").html('');
				$("#ResultadoLote").html('');
				return true;
			}
		});
	}


	function validar_existencia() { 
		if($("#lote").val() === null) {
			alert("Lote sin existencia.");
			$("#cantidad").val("");
			return false;
		}

		var xlote = $("#lote").val().split("|");
		var cantidad = parseInt($("#cantidad").val());

		lote = xlote[0];
		existencia = parseInt(xlote[2]);

		if(cantidad > existencia) {
			alert("La cantidad solicitada es mayor a la existencia.");
			return false;
		} else return true;
	}

	function ActualizarCabecera() {
		id = $("#id").val();

		nota = $("#nota").val();
		factura = $("#factura").val();
		ci_rif = $("#ci_rif").val().trim();
		nombre = $("#nombre").val().trim();
		direccion = $("#direccion").val().trim();
		telefono = $("#telefono").val().trim();
		username = $("#username").val().trim();
		tasa = $("#tasa").val();
		descuento = $("#descuento").val();

		if(factura == "S") {
			if(ci_rif == "" || nombre == "" || direccion == "" || telefono == "") {
				alert("Faltan datos fiscales; Verifique");

				if(ci_rif == "") { $("#ci_rif").focus(); return false; }
				if(nombre == "") { $("#nombre").focus(); return false; }
				if(direccion == "") { $("#direccion").focus(); return false; }
				if(telefono == "") { $("#telefono").focus(); return false; }
			}

		}
		else {
			ci_rif = "";
			nombre = "";
			direccion = "";
			telefono = "";
		}

		var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
			"id": id,
			"factura": factura,
			"ci_rif": ci_rif,
			"nombre": nombre,
			"direccion": direccion,
			"telefono": telefono,
			"username":username,
			"nota":nota,
			"tasa":tasa,
			"descuento":descuento
		};
		var url = "include/findme_cabecera_update.php";

		//$("#enviar").prop('disabled', true);
	
		$.ajax({
			data: parametros,
			url: url,
			type: 'post',
			beforeSend: function () {//elemento que queramos poner mientras ajax carga
				//$("#message").html('<img src="images/ajax.gif" width="60" />');
				$("#ResultadoDetalle").html('');
			},
			success: function (response) {//resultado de la función
				$("#ResultadoDetalle").html(response);
				$("#ResultadoBusqueda").html('');
				$("#ResultadoLote").html('');
				alert("Actualizado los Datos de la Cabecera");
				return true;
			}
		});
	}

	function VaciarCesta() {
		id = $("#id").val();
		username = $("#username").val().trim();


		if(confirm("Seguro de vaciar la cesta?")) { 
			window.location.href = "include/findme_eliminar_all.php?id=" + id + "&username=" + username;
			return true;
		} 
		else return false;
	}

</script>

<?= GetDebugMessage() ?>
