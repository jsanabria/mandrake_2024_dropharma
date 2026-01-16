<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntrega = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
if($row = ExecuteRow($sql)) $tipo_documento_inventario = $row["tipo_documento"];

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$almacen = ExecuteScalar($sql);

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
$almacenconsig = ExecuteScalar($sql);

$sql = "SELECT 
	c.descripcion AS tipo, b.nombre AS cliente, a.nro_documento, 
	a.fecha, a.tipo_documento, a.nota 
FROM 
	salidas AS a 
	LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
	LEFT OUTER JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
WHERE 
	a.id = '$id';"; 
$row = ExecuteRow($sql);

$tipo = $row["tipo"];
$tipo_documento = $row["tipo_documento"];
$cliente = $row["cliente"];
$nro_documento = $row["nro_documento"];
$nota = $row["nota"];

?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>
<div class="container-fluid">
	<form id="frm" name="frm" method="post" action="CrearNotaEntregaGuardar">
		<div class="row">
			<!--<div class="col-md-12">-->
				<div class="col-md-12">
					<div class="table-responsive">
					  <table class="table table-bordered table-condensed table-striped table-striped ewViewTable">
					  		<tbody>
						  		<tr>
						  			<td><span>Tipo</span></td>
						  			<td><span><?php echo $tipo; ?></span></td>
						  		</tr>
						  		<tr>
						  			<td><span>Nro Documento Origen</span></td>
						  			<td><span><?php echo $nro_documento; ?></span></td>
						  		</tr>
						  		<!--<tr>
						  			<td><span>Nro Documento Destino</span></td>
						  			<td><span><?php echo $nro_documento; ?></span></td>
						  		</tr>-->
						  		<tr>
						  			<td><span>Cliente</span></td>
						  			<td><span><?php echo $cliente; ?></span></td>
						  		</tr>
						  		<tr>
						  			<td><span>Nota</span></td>
						  			<td><span><textarea id="nota" name="nota" class="form-control" cols="30" rows="3"><?php echo $nota; ?></textarea></span></td>
						  		</tr>
					  		</tbody>
					  </table>
					</div>			
				</div>
			<!--</div>-->
		</div>

		<div class="clearfix">
			<h5>Por favor, valide la exitencia para crear esta nota de entrega y cargue las cantidades de la misma con su respectiva unidad de medida.</h5>
		</div>

		<div class="row">
			<div class="panel panel-default ewGrid entradas_salidas">
				<div class="table-responsive ewGridMiddlePanel">
					  <table class="table table-bordered table-condensed table-striped">
					  		<thead>
						  		<tr>
						  			<th></th>
						  			<th>Fabricante</th>
						  			<th>Art&iacute;culo</th>
						  			<th>Cantidad</th>
						  			<th>Unidad Medida</th>
						  			<th>Lot, Venc y Exs UNIDAD</th>
						  			<th>Cantidad</th>
						  			<th>Unidad Medida</th>
						  		</tr>
					  		</thead>
					  		<tbody>
							<?php 
							$sql= "SELECT 
										a.id, 
										b.nombre AS fabricante, 
										CONCAT(IFNULL(c.principio_activo, ''), ', ', 
												IFNULL(c.presentacion, ''), ', ', 
												IFNULL(c.nombre_comercial, '')) AS articulo, 
											a.cantidad_articulo, d.descripcion AS unidad_medida, 
											d.cantidad, a.articulo AS codart, a.articulo_unidad_medida 
									FROM 
										entradas_salidas AS a 
										LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
										LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
										LEFT OUTER JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida 
									WHERE 
										a.tipo_documento = '$tipo_documento' AND a.id_documento = '$id' AND (c.cantidad_en_mano+ABS(c.cantidad_en_pedido)) > 0 
									 ORDER BY articulo;"; 
							$rows = ExecuteRows($sql);
							$cantidad = count($rows);

							for($i=0; $i<$cantidad; $i++) {
								$row = $rows[$i];

				  				$cnt = $row["cantidad_articulo"];
				  				$codart = $row["codart"];

								$sql = "SELECT 
	                              x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, SUM(x.cantidad_movimiento) AS cantidad 
	                            FROM 
	                              (
	                                SELECT 
	                                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
	                                   IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
	                                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
	                                FROM 
	                                   entradas_salidas AS a 
	                                   JOIN entradas AS b ON
	                                       b.tipo_documento = a.tipo_documento
	                                       AND b.id = a.id_documento 
	                                   JOIN almacen AS c ON
	                                       c.codigo = a.almacen AND c.movimiento = 'S'
	                                WHERE 
	                                   (
	                                       (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
	                                       (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
	                                   ) AND a.articulo = $codart AND a.almacen = '$almacen' AND a.newdata = 'S' 
	                                UNION ALL SELECT 
	                                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
	                                   IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
	                                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
	                                FROM 
	                                   entradas_salidas AS a 
	                                   JOIN salidas AS b ON
	                                       b.tipo_documento = a.tipo_documento
	                                       AND b.id = a.id_documento 
	                                   JOIN almacen AS c ON
	                                       c.codigo = a.almacen AND c.movimiento = 'S'
	                                WHERE 
	                                   (
	                                       (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO') 
	                                   ) AND a.articulo = $codart AND a.almacen = '$almacen' AND a.newdata = 'S' 
	                              ) AS x 
	                            WHERE 1 -- x.almacen = '$almacen' 
	                            GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
	                            HAVING SUM(x.cantidad_movimiento) <> 0 
	                            ORDER BY x.fecha_vencimiento, x.lote ASC;";
								$rows2 = ExecuteRows($sql);
								$cntun = count($rows2);

								if($cntun != 0) {
								?>
						  		<tr>
						  			<td><?php echo strval(($i+1)) . " " . '<input type="hidden" id="id_' . $i . '" name="id_' . $i . '" value="' . $row["id"] . '">'; ?></td>
						  			<td><?php echo $row["fabricante"]; ?></td>
						  			<td><?php echo $row["articulo"] . '<input type="hidden" id="articulo_' . $i . '" name="articulo_' . $i . '" value="' . $row["codart"] . '">'; ?></td>
						  			<td><?php echo $row["cantidad_articulo"]; ?></td>
						  			<td><?php echo $row["unidad_medida"] . ', ' . $row["cantidad"]; ?></td>
						  			<td><?php

										echo '<select id="lote_' . $i . '" name="lote_' . $i . '" class="form-select form-select-sm" onchange="js:validar_existencia(' . $i . ');">';
										//echo '<option value=""></option>';
										for($j=0; $j<$cntun; $j++) {
											$row2 = $rows2[$j];
											if(intval($row2["cantidad"]) > 0) {
												echo '<option value="' . $row2["lote"] . "|" . $row2["fecha_vencimiento"] . "|" . intval($row2["cantidad"]) . "|" . $row2["codalm"] . '">' . $row2["lote"] . ", " . ($row2["fecha"] == '01/01/1990' ? '' : $row2["fecha"]) . " | " . intval($row2["cantidad"]) . ' | ' . $row2["almacen"] . '</option>';
												if($j==0) {
													if($cnt>$row2["cantidad"]) $cnt = $row2["cantidad"];
												}
											}
	 									}
										echo '</select>'; 
						  				?>
						  			</td>
						  			<td><?php echo '<input type="number" id="cantidad_' . $i . '" name="cantidad_' . $i . '" class="form-control" style="width: 80px;" onchange="js:validar_existencia(' . $i . ');" value="' . intval($cnt) . '">'; ?></td>
						  			<td><?php
										echo '<select id="unidad_' . $i . '" name="unidad_' . $i . '" class="form-select form-select-sm" onchange="js:validar_existencia(' . $i . ');">';
										for($j=0; $j<$cntun; $j++) {
											echo '<option value="' . $row["articulo_unidad_medida"] . '">' . $row["unidad_medida"] . '</option>';
	 									}
										echo '</select>';
						  				?>
						  			</td>
						  		</tr>
								<?php
								}
							}
							?>
					  		</tbody>
					  </table>
				</div>
			</div>
		</div>
		<button id="enviar" name="enviar" class="btn btn-primary" type="button">Enviar</button>
		<input type="hidden" name="id" value="<?php echo $id; ?>">
		<input type="hidden" name="cantidad" value="<?php echo $cantidad; ?>">
		<input type="hidden" name="username" value="<?php echo CurrentUserName(); ?>">
	</form>
</div>

<script type="text/javascript">
	function validar_existencia(i) {
		id = $("#id_" + i).val();
		lote = $("#lote_" + i).val();
		cantidad = $("#cantidad_" + i).val();
		unidad = $("#unidad_" + i).val();

		if(lote == "" || cantidad == "" || unidad == "") {
			return false;
		}

		var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
			"id": id,
			"lote": lote,
			"cantidad": cantidad,
			"unidad": unidad
		};
		var url = "VerificarExistencia";

//alert(url + " -- " + id + " - " + lote + " - " + cantidad + " - " + unidad);
		$("#enviar").prop('disabled', true);
		$.ajax({
			data: parametros,
			url: url,
			type: 'post',
			beforeSend: function () {//elemento que queramos poner mientras ajax carga
				//$("#message").html('<img src="images/ajax.gif" width="60" />');
			},
			success: function (response) {//resultado de la función
				var paso = response.trim();
				if(paso == "0") {
					alert("La cantidad solicitada es mayor a la existencia del lote o a lo pedido en el item.");
					$("#cantidad_" + i).val("");
					$("#cantidad_" + i).focus();
					$("#enviar").prop('disabled', false);
					return false;
				}
				else {
					$("#enviar").prop('disabled', false);
					return true;
				}
			}
		});
	}

	$("#enviar").click(function() {
		cantidad = <?php echo $cantidad; ?>;

		for(i = 0; i < cantidad; i++) {
			id = $("#id_" + i).val();
			lote = $("#lote_" + i).val();
			cantidad = $("#cantidad_" + i).val();
			unidad = $("#unidad_" + i).val();

			if(lote == "") {
				alert("Debe indicar el lote");
				$("#lote_" + i).focus();
				return false;
			}

			if(cantidad == "") {
				alert("Debe indicar la cantidad");
				$("#cantidad_" + i).focus();
				return false;
			}

			if(unidad == "") {
				alert("Debe indicar la unidad de medida");
				$("#unidad_" + i).focus();
				return false;
			}
		}

		if(confirm("Está seguro de crear la nota de entrega?")) {
			$("#enviar").prop('disabled', true);
			$("#frm").submit();
		}
	});

</script>

<style type="text/css">
	.form-control:focus {
		border-color: #FF0000;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);
	}	
</style>

<?= GetDebugMessage() ?>
