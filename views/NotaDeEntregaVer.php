<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeEntregaVer = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_POST["xNota"];

$sql = "SELECT a.id, 
			b.nombre, a.tipo_documento, a.nro_documento,
			date_format(a.fecha, '%d/%m/%Y') AS fecha,
			a.moneda, a.monto_total  
		FROM 
			salidas AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE
			a.id = $id;";
$row = ExecuteRow($sql);
$id = $row["id"];
$tipo_documento = $row["tipo_documento"];
$cliente = $row["nombre"];
$nro_documento = $row["nro_documento"];
$fecha = $row["fecha"];
$moneda = $row["moneda"];
$monto_total = number_format($row["monto_total"], 2, ".", ",");
?>
	<form id="frm" name="frm" method="post" action="ConfirmPage?page=TDCFCV2&id=<?php echo $id; ?>">
<div class="container">
  <div class="mb-3 row">
  	<input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="staticEmail" class="col-sm-2 col-form-label">Cliente</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $cliente; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Nro. Documento</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $tipo_documento . "; " . $nro_documento; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Fecha</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $fecha; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Moneda</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $moneda; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Monto</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $monto_total; ?>">
    </div>
  </div>


	<table class="table table-bordered">
		<thead>
			<tr>
				<th width="50%">
					Art&iacute;culo
				</th>
				<th width="10%">
					Cantidad
				</th>
				<th width="15%">
					Precio Unidad
				</th>
				<th width="15%">
					Precio
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT
					a.id,
					a.articulo, 
					b.principio_activo,
					ABS(a.cantidad_movimiento) AS cantidad_movimiento,
					a.precio_unidad, a.precio   
				FROM 
					entradas_salidas AS a 
					JOIN articulo AS b ON b.id = a.articulo  
				WHERE a.tipo_documento = 'TDCNET' AND a.id_documento = $id
				ORDER BY a.articulo DESC;";
		$rows = ExecuteRows($sql);
		$i = 1;
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<td>
					<?php echo $value["principio_activo"]; ?>
				</td>
				<td>
					<?php echo intval($value["cantidad_movimiento"]); ?>
				</td>
				<td>
					<input type="text" readonly class="form-control" name="x<?php echo $i; ?>_Costo" id="x<?php echo $i; ?>_Costo" size="6" value="<?php echo number_format($value["precio_unidad"], 2, ".", ","); ?>">
				</td>
				<td>
					<?php echo number_format($value["precio"], 2, ".", ","); ?>
				</td>
			</tr>
			<?php
			$i++;
		}
		$i = count($rows);
		?>
			<tr>
				<td class="text-center" colspan="3">
					<input type="hidden" name="cantidad" value="<?php echo $i; ?>">
					<input type="submit" class="btn btn-default" type="button" value="Procesar Factura">
					&nbsp;
					<a href="Devoluciones" class="btn btn-default">Regresar</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>

<?= GetDebugMessage() ?>
