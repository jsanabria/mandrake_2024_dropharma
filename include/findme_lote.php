<?php
session_start();

include "connect.php";

$codart = $_REQUEST["id"];

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento_inventario = $row["tipo_documento"];

?>
<div class="row">
	<div class="panel panel-default ewGrid entradas_salidas">
		<div class="table-responsive ewGridMiddlePanel">
			  <table class="table table-bordered table-condensed table-striped">
			  		<thead>
				  		<tr>
				  			<th>Fabricante</th>
				  			<th>Art&iacute;culo</th>
				  			<th>Lot, Venc y Exs UNIDAD</th>
				  			<th>Cantidad</th>
				  			<th>Unidad Medida</th>
				  			<th></th>
				  		</tr>
			  		</thead>
			  		<tbody>
					<?php 
					$sql= "SELECT 
								b.nombre AS fabricante, 
								CONCAT(IFNULL(a.principio_activo, ''), ', ', 
										IFNULL(a.presentacion, ''), ', ', 
										IFNULL(a.nombre_comercial, '')) AS articulo 
							FROM 
								articulo AS a 
								LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
							WHERE 
								a.id = $codart;"; 
					$rs = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($rs);

					?>
			  		<tr>
			  			<td><?php echo '<input type="hidden" id="id_articulo" name="id_articulo" value="' . $codart . '">' . $row["fabricante"]; ?></td>
			  			<td><?php echo $row["articulo"]; ?></td>
			  			<td><?php
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
                                   ) AND a.articulo = $codart AND a.newdata = 'S' 
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
                                   ) AND a.articulo = $codart AND a.newdata = 'S' 
                              ) AS x 
                            WHERE 1 
                            GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
                            HAVING SUM(x.cantidad_movimiento) <> 0 
                            ORDER BY x.fecha_vencimiento, x.lote ASC;"; 

							echo '<select id="lote" name="lote" class="form-select form-select-sm" onchange="js:validar_existencia();">';
							//echo '<option value=""></option>';
								$rs2 = mysqli_query($link, $sql);
								while($row2 = mysqli_fetch_array($rs2)) {
									if(intval($row2["cantidad"]) > 0) {
										echo '<option value="' . $row2["lote"] . "|" . $row2["fecha_vencimiento"] . "|" . intval($row2["cantidad"]) . "|" . $row2["codalm"] . '">' . $row2["lote"] . ", " . ($row2["fecha"] == '01/01/1990' ? '' : $row2["fecha"]) . " | " . intval($row2["cantidad"]) . ' | ' . $row2["almacen"] . '</option>';
									}
								}
							echo '</select>'; 
			  				?>
			  			</td>
			  			<td><?php echo '<input type="number" id="cantidad" name="cantidad" class="form-control input-sm" style="width: 80px;" onkeyup="js:validar_existencia();" value="">'; ?></td>
			  			<td>
			  				<select id="unidad" name="unidad" class="form-select form-select-sm" onchange="js:validar_existencia();">
								<option value="UDM001">UNIDAD</option>
							</select>
			  			</td>
			  			<td>
			  				<a class="btn btn-primary" id="agregar" name="agregar" onclick="AgregarItem()" ><span class="fa fa-plus"></span></a>
			  			</td>
			  		</tr>
			  		</tbody>
			  </table>
		</div>
	</div>
</div>
