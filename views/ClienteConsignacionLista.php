<?php

namespace PHPMaker2024\mandrake;

// Page object
$ClienteConsignacionLista = &$Page;
?>
<?php
$Page->showMessage();
?>
	<form id="frm" name="frm" method="post" action="ClienteConsignacionArticulos">
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					&nbsp;
				</th>
				<th>
					Cliente
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT
					DISTINCT b.id, b.nombre AS cliente
				FROM
					salidas AS a JOIN cliente AS b ON b.id = a.cliente
				WHERE a.tipo_documento = 'TDCNET' AND 
					a.estatus = 'NUEVO' AND a.consignacion = 'S' ORDER BY b.nombre;";
		$rows = ExecuteRows($sql);
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<td align="center">
					<input type="radio" name="xCliente" id="xCliente" value="<?php echo $value["id"]; ?>">
				</td>
				<td>
					<?php echo $value["cliente"]; ?>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td class="text-center" colspan="4">
					<input type="submit" class="btn btn-default" type="button" value="Ver Consignaciones">
					&nbsp;
					<input type="button" class="btn btn-default" type="button" value="Regresar" onclick="js: history.back();"> 
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>

<?= GetDebugMessage() ?>
