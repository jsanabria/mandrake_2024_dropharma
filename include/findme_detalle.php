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
										$sql = "SELECT COUNT(*) AS cantidad FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = '$id_documento';";
										$rs = mysqli_query($link, $sql);
										$row = mysqli_fetch_array($rs);										
										$cantidad = $row["cantidad"];  

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
														a.tipo_documento = '$tipo_documento' AND a.id_documento = '$id_documento' 
													 ORDER BY articulo LIMIT $i, 1;";
											$rs = mysqli_query($link, $sql);
											$row = mysqli_fetch_array($rs);										

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
											a.monto_total, a.alicuota_iva, a.iva, a.total, 
											a.tasa_dia, a.monto_usd, IFNULL(a.descuento, 0) AS descuento, a.monto_sin_descuento 
										FROM 
											salidas AS a 
										WHERE 
											a.id = $id_documento;"; 
										$rs = mysqli_query($link, $sql);
										$row = mysqli_fetch_array($rs);										
							?>
							  <table class="table table-bordered table-condensed table-striped">
							  		<thead>
								  		<tr>
								  			<th colspan="2">Resumen Venta</th>
								  		</tr>
							  		</thead>
							  		<tbody>
							  			<tr>
								  			<td>Monto a aplicar <?php echo number_format($row["descuento"], 2, ",", ".");  ?>% de Descuento</td>
								  			<td><?php echo number_format($row["monto_sin_descuento"], 2, ",", "."); ?></td>
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
								  			<td><?php echo number_format($row["monto_total"], 2, ",", ".") ?></td>
								  		</tr>
							  			<tr>
								  			<td>Alicuota IVA</td>
								  			<td><?php echo number_format($row["alicuota_iva"], 2, ",", ".") ?></td>
								  		</tr>
							  			<tr>
								  			<td>IVA</td>
								  			<td><?php echo number_format($row["iva"], 2, ",", ".") ?></td>
								  		</tr>
							  			<tr>
								  			<td>Total</td>
								  			<td><?php echo number_format($row["total"], 2, ",", ".") ?></td>
								  		</tr>
							  			<tr>
								  			<td>Tasa del D&iacute;a</td>
								  			<td><?php echo number_format($row["tasa_dia"], 2, ",", ".") ?></td>
								  		</tr>
							  			<tr>
								  			<td>Monto USD</td>
								  			<td><?php echo number_format($row["monto_usd"], 2, ",", ".") ?></td>
								  		</tr>
								  	</tbody>
							   </table>
						</div>
					</div>
				<div>


<input type="hidden" id="id" name="id" value="<?php echo $id_documento; ?>">
<input type="hidden" id="cantidad" name="cantidad" value="<?php echo $cantidad; ?>">
<input type="hidden" id="username" name="username" value="<?php echo $username;  ?>">

