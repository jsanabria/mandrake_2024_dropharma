<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticuloDetalle = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
if(!isset($_POST["xArticulo"])) {
	header("Location: TransferenciaArticulo");
	die();
}

$articulo = $_POST["xArticulo"];
?>
	<form id="frm" name="frm" method="post" action="TransferenciaGuardar">
<div class="container">
	<?php
	$sql = "SELECT 
		a.codigo, a.codigo_ims, 
		a.nombre_comercial, a.principio_activo, a.presentacion, 
		b.nombre AS fabricante, a.cantidad_en_mano  
	FROM 
		articulo AS a 
		JOIN fabricante AS b ON b.Id = a.fabricante 
	WHERE 
		a.id = '$articulo';";
	if($row = ExecuteRow($sql)) {
		echo '<h3>' . $row["codigo"] . '</h3>';
		echo '<h3>' . $row["nombre_comercial"]  . " " . $row["principio_activo"] . " ". $row["presentacion"] . '</h3>';
		echo '<h3>' . $row["fabricante"] . '</h3>';
	}
	?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					&nbsp;
				</th>
				<th>
					Lote
				</th>
				<th>
					Fecha Vencimiento
				</th>
				<th>
					Existencia
				</th>
				<th>
					Alamacen Origen
				</th>
				<th>
					Alamacen Destino
				</th>
				<th>
					Cantidad
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
        $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
        $tipo_documento = 'TDCNET';
        if($row = ExecuteRow($sql)) $tipo_documento = $row["tipo_documento"];

        $sql = "SELECT 
                    x.articulo, x.cod_almacen AS codalm, x.nom_almacen, x.lote, x.fecha AS fecha_vencimiento, x.fecha_vencimiento AS fecha, SUM(x.cantidad_movimiento) AS cantidad 
                FROM 
                    (
                        SELECT 
                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento, c.codigo AS cod_almacen, c.descripcion AS nom_almacen  
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
                            ) AND a.articulo = $articulo AND a.newdata = 'S' 
                        UNION ALL SELECT 
                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento, c.codigo AS almacen, c.descripcion AS nombre_almacen    
                        FROM 
                            entradas_salidas AS a 
                            JOIN salidas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                                (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                            ) AND a.articulo = $articulo AND a.newdata = 'S' 
                    ) AS x 
                WHERE 1 
                GROUP BY x.articulo, x.cod_almacen, x.nom_almacen, x.lote, x.fecha, x.fecha_vencimiento  
                HAVING SUM(x.cantidad_movimiento) <> 0 
                ORDER BY x.fecha ASC;";
		$rows = ExecuteRows($sql);
		$i = 1;
		$onhand = 0;
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<td>
					<?php if(intval($value["cantidad"]) > 0) { ?>
					<input type="checkbox" name="<?php echo "x" . $i . "_Lote"; ?>" id="<?php echo "x" . $i . "_Lote"; ?>" value="<?= ($value["articulo"] . "|" .$value["lote"] . "|" . $value["fecha"] . "|" . intval($value["cantidad"]) . "|" . $value["codalm"]) ?>">
					<?php } ?>
				</td>
				<td>
					<?php echo $value["lote"]; ?>
				</td>
				<td>
					<?php echo $value["fecha_vencimiento"]; ?>
				</td>
				<td>
					<input class="form-control" type="number" name="<?php echo "x" . $i . "_Existencia"; ?>" id="<?php echo "x" . $i . "_Existencia"; ?>" value="<?php echo intval($value["cantidad"]); ?>" readonly="yes">
					<?php
					$onhand += intval($value["cantidad"]);
					?>
				</td>
				<td>
					<?php echo $value["nom_almacen"]; ?>
				</td>
				<td>
					<select class="form-control" name="<?php echo "x" . $i . "_Almacen"; ?>" id="<?php echo "x" . $i . "_Almacen"; ?>">
						<option value=""></option>
						<?php
						$sql = "SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S' AND codigo <> '" . $value["cod_almacen"] . "';";
						$rows2 = ExecuteRows($sql);
						foreach ($rows2 as $key2 => $value2) {
							echo '<option value="' . $value2["codigo"] . '">' . $value2["descripcion"] . '</option>';
						}
						?>
					</select>
				</td>
				<td>
					<input class="form-control" type="number" name="<?php echo "x" . $i . "_Cantidad"; ?>" id="<?php echo "x" . $i . "_Cantidad"; ?>" value="" onchange="js: validarCantidad(this.name); ">
				</td>
			</tr>
			<?php
			$i++;
		}
		?>
			<tr>
				<td colspan="6">
					<?php
					echo '<h3>Existencia: ' . $onhand . '</h3>';
					?>
				</td>
			</tr>
			<tr>
				<td class="text-center" colspan="7">
					<input type="button" id="enviar" class="btn btn-default" type="button" value="Procesar Transferencia" onclick="js:validarEnvio(<?php echo $i-1; ?>);">
					&nbsp;
					<a href="TransferenciaArticulo" class="btn btn-default">Regresar</a>
				</td>
			</tr>
		</tbody>
		<input type="hidden" name="CantItem" value="<?php echo $i-1; ?>">
	</table>
</div>
	</form>

<script>
	function validarCantidad(xCtrl) {
		var id = xCtrl.substring(1, 2);
		var Lote = $("#x" + id + "_Lote").val();
		var Existencia = parseInt($("#x" + id + "_Existencia").val());
		var Almacen = $("#x" + id + "_Almacen").val();
		if($("#x" + id + "_Cantidad").val() == "") {
			alert("Debe colocar una cantidad.");
			return false;
		}
		var Cantidad = parseInt($("#x" + id + "_Cantidad").val());

		if(Cantidad <= 0) {
			alert("La cantidad debe ser mayor a 0.");
			$("#x" + id + "_Cantidad").val("");
			return false;
		}
		
		if(Cantidad > Existencia) {
			alert("La cantidad no debe ser mayor a la existencia.");
			$("#x" + id + "_Cantidad").val("");
			return false;
		}

		if(Almacen == "") {
			alert("Debe seleccionar un almacen de destino.");
			return false;
		}

		return true;
	}

	function validarEnvio(xVal) {
		var sw = false;
		for(i=1; i<=xVal; i++) {
			if($('#x' + i + '_Lote').is(':checked')) {
				if(validarCantidad("x" + i + "_Ctrl") == false) {
					return false;
				}

				sw = true;
			}
		}
		if(sw) {
			if(confirm("Está seguro de procesar esta transferencia?")) {
				$('#frm').submit();
				return true;
			}
			else return false;
		}
		else {
			alert("Debe seleccionar al menos un lote para realizar la transferencia...");
			return false;
		}
	}	
</script>

<?= GetDebugMessage() ?>
