<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticuloListar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
if(!isset($_POST["articulo"])) {
	header("Location: TransferenciaArticulo");
	die();
}

$articulo = $_POST["articulo"];
?>
	<form id="frm" name="frm" method="post" action="TransferenciaArticuloDetalle">
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					&nbsp;
				</th>
				<th>
					C&oacute;digo
				</th>
				<th>
					Nombre Comercial
				</th>
				<th>
					Nombre Principal
				</th>
				<th>
					Presentaci&oacute;n
				</th>
				<th>
					Fabricante
				</th>
				<th>
					Existencia
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT
					a.id, 
					a.codigo, a.codigo_ims, 
					a.nombre_comercial, a.principio_activo, a.presentacion, 
					b.nombre AS fabricante, a.cantidad_en_mano 
				FROM 
					articulo AS a 
					JOIN fabricante AS b ON b.Id = a.fabricante 
				WHERE 
					a.codigo LIKE '%$articulo%' 
					OR a.nombre_comercial LIKE '%$articulo%' 
					OR a.principio_activo LIKE '%$articulo%' 
					OR a.presentacion LIKE '%$articulo%'
				ORDER BY a.principio_activo;"; 
		$rows = ExecuteRows($sql);
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<?php
		        $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
		        $tipo_documento = 'TDCNET';
		        if($row = ExecuteRow($sql)) $tipo_documento = $row["tipo_documento"];

		        $sql = "SELECT 
		                    SUM(x.cantidad_movimiento) AS cantidad 
		                FROM 
		                    (
		                        SELECT 
		                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		                            a.fecha_vencimiento, 
		                            a.cantidad_movimiento 
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
		                            ) AND a.articulo = " . $value["id"] . " AND a.newdata = 'S' 
		                        UNION ALL SELECT 
		                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		                            a.fecha_vencimiento, 
		                            a.cantidad_movimiento  
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
		                            ) AND a.articulo = " . $value["id"] . " AND a.newdata = 'S' 
		                    ) AS x 
		                WHERE 1;";
				$rows2 = ExecuteRows($sql);
				$onhand = 0;
				foreach ($rows2 as $key2 => $value2) {
					$onhand += intval($value2["cantidad"]);
				}
				?>
				<td>
					<?php
						// (intval($value["cantidad_en_mano"]) > 0) {
						if($onhand > 0) {
					?>
					<input type="radio" name="xArticulo" id="xArticulo" value="<?php echo $value["id"]; ?>">
					<?php } ?>
				</td>
				<td>
					<?php echo $value["codigo"]; ?>
				</td>
				<td>
					<?php echo $value["nombre_comercial"]; ?>
				</td>
				<td>
					<?php echo $value["principio_activo"]; ?>
				</td>
				<td>
					<?php echo $value["presentacion"]; ?>
				</td>
				<td>
					<?php echo $value["fabricante"]; ?>
				</td>
				<td>
					<?php echo $onhand; ?>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td class="text-center" colspan="7">
					<input type="submit" class="btn btn-default" type="button" value="Ver Detalle Existencia">
					&nbsp;
					<a href="TransferenciaArticulo" class="btn btn-default">Regresar</a> 
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>

<?= GetDebugMessage() ?>
