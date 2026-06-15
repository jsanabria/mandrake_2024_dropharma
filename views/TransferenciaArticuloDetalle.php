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
					<input
                        type="checkbox"
                        name="<?php echo "x" . $i . "_Lote"; ?>"
                        id="<?php echo "x" . $i . "_Lote"; ?>"
                        value="<?= ($value["articulo"] . "|" .$value["lote"] . "|" . $value["fecha"] . "|" . intval($value["cantidad"]) . "|" . $value["codalm"]) ?>"
                        onchange="limpiarFilaSiNoSeleccionada(<?= $i ?>);"
                    >
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
					<select class="form-select" name="<?php echo "x" . $i . "_Almacen"; ?>" id="<?php echo "x" . $i . "_Almacen"; ?>">
						<option value=""></option>
						<?php
						$sql = "SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S' AND codigo <> '" . $value["codalm"] . "';";
						$rows2 = ExecuteRows($sql);
						foreach ($rows2 as $key2 => $value2) {
							echo '<option value="' . $value2["codigo"] . '">' . $value2["descripcion"] . '</option>';
						}
						?>
					</select>
				</td>
				<td>
					<input
                        class="form-control"
                        type="number"
                        min="1"
                        max="<?php echo intval($value["cantidad"]); ?>"
                        name="<?php echo "x" . $i . "_Cantidad"; ?>"
                        id="<?php echo "x" . $i . "_Cantidad"; ?>"
                        value=""
                        onchange="validarCantidad(this.name);"
                    >
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

function obtenerIdFila(nombreControl) {
    const match = nombreControl.match(/^x(\d+)_/);
    return match ? match[1] : null;
}

function validarCantidad(nombreControl) {

    const id = obtenerIdFila(nombreControl);

    if (!id) {
        ew.alert("No se pudo identificar la fila.");
        return false;
    }

    const chkLote = $("#x" + id + "_Lote");
    const existenciaCtrl = $("#x" + id + "_Existencia");
    const almacenCtrl = $("#x" + id + "_Almacen");
    const cantidadCtrl = $("#x" + id + "_Cantidad");

    const existencia = parseInt(existenciaCtrl.val(), 10) || 0;
    const almacen = almacenCtrl.val();
    const cantidad = parseInt(cantidadCtrl.val(), 10) || 0;

    if (!chkLote.is(":checked")) {
        ew.alert("Debe seleccionar el lote.");
        cantidadCtrl.val("");
        chkLote.focus();
        return false;
    }

    if (almacen === "") {
        ew.alert("Debe seleccionar un almacén destino.");
        cantidadCtrl.val("");
        almacenCtrl.focus();
        return false;
    }

    if (cantidad <= 0) {
        ew.alert("La cantidad debe ser mayor a cero.");
        cantidadCtrl.val("");
        cantidadCtrl.focus();
        return false;
    }

    if (cantidad > existencia) {
        ew.alert("La cantidad no puede ser mayor que la existencia.");
        cantidadCtrl.val("");
        cantidadCtrl.focus();
        return false;
    }

    return true;
}

function limpiarFilaSiNoSeleccionada(id) {

    const chkLote = $("#x" + id + "_Lote");

    if (!chkLote.is(":checked")) {
        $("#x" + id + "_Almacen").val("");
        $("#x" + id + "_Cantidad").val("");
    }
}

function validarEnvio(totalFilas) {

    let selecciono = false;

    for (let i = 1; i <= totalFilas; i++) {

        const chkLote = $("#x" + i + "_Lote");

        if (chkLote.length && chkLote.is(":checked")) {

            selecciono = true;

            if (!validarCantidad("x" + i + "_Cantidad")) {
                return false;
            }
        }
    }

    if (!selecciono) {
        ew.alert("Debe seleccionar al menos un lote.");
        return false;
    }

    if (confirm("¿Está seguro de procesar esta transferencia?")) {
        $("#frm").submit();
        return true;
    }

    return false;
}

</script>

<?= GetDebugMessage() ?>
