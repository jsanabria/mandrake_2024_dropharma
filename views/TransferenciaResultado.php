<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaResultado = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$salida = $_REQUEST["salida"];
$entrada = $_REQUEST["entrada"];

$sql = "SELECT 
			a.id, 
			(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo_documento, 
			a.nro_documento, a.documento, 
			(SELECT SUM(cantidad_articulo) AS cant 
			FROM entradas_salidas 
			WHERE id_documento = a.id AND tipo_documento = 'TDCAEN') AS unidades,
			a.nota, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, a.tipo_documento AS td 
		FROM entradas AS a WHERE a.id = $entrada 
		UNION ALL 
		SELECT 
			a.id, 
			(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo_documento, 
			a.nro_documento, a.documento, a.unidades,
			a.nota, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, a.tipo_documento AS td  
		FROM salidas AS a WHERE a.id = $salida;";
$rows = ExecuteRows($sql);

?>
<div class="container">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Id</th>
        <th>Tipo</th>
        <th>Nro Documento</th>
        <th>Fecha</th>
        <th>Nota</th>
        <th>Unidades</th>
      </tr>
    </thead>
    <tbody>
<?php
foreach ($rows as $key => $value) {
	echo '<tr>';
		if($value["td"] == "TDCASA")
        	echo '<td><a href="ViewOutList?showmaster=view_out_tdcasa&fk_id=' . $value["id"] . '&fk_tipo_documento=' . $value["td"] . '" target="_blank">' . $value["id"] . '</a></td>';
        else
        	echo '<td><a href="ViewInList?showmaster=view_in_tdcaen&fk_id=' . $value["id"] . '&fk_tipo_documento=' . $value["td"] . '" target="_blank">' . $value["id"] . '</a></td>';
        echo '<td>' . $value["tipo_documento"] . '</td>';
        echo '<td>' . $value["nro_documento"] . '</td>';
        echo '<td>' . $value["fecha"] . '</td>';
        echo '<td>' . $value["nota"] . '</td>';
        echo '<td>' . $value["unidades"] . '</td>';
    echo '</tr>';
}
?>
		<tr>
			<td colspan="6">
				<a href="TransferenciaArticulo" class="btn btn-primary">Realizar Otra Transferencia</a>
			</td>
		</th>
    </tbody>
  </table>
 

<?= GetDebugMessage() ?>
