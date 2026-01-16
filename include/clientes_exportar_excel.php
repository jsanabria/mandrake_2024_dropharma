<?php 
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporteClientesAsesor.xls");
header("Pragma: no-cache");
header("Expires: 0");

include "connect.php";
	$sql = "SELECT 
				a.id, 
				c.nombre AS asesor, 
				(SELECT campo_descripcion FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = a.ciudad ) AS ciudad, 
				a.ci_rif AS rif, a.nombre AS nombre, a.contacto AS contacto, 
				a.direccion AS direccion, a.email1 AS email1, a.email2 AS email2, 
				a.codigo_ims AS ims, a.web AS codigo_sicm, 
				(SELECT nombre FROM tarifa WHERE id = a.tarifa ) AS tarifa, 
				IF(a.consignacion='S', 'SI', 'NO') AS consignacion, IF(a.activo='S', 'SI', 'NO') AS activo
			FROM 
				cliente AS a 
				LEFT OUTER JOIN asesor_cliente AS b ON b.cliente = a.id 
				LEFT OUTER JOIN asesor AS c ON c.id = b.asesor 
			ORDER BY 
				c.nombre, a.nombre;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$out = '<div class="container">';
	$out .= '<h4>CLIENTES ASESOR</h4>';
	$out .= '<div class="table-responsive">
	          <table class="table table-striped">
	            <thead>
	              <tr>
	                <th>ID</th>
	                <th>ASESOR</th>
	                <th>CIUDAD</th>
	                <th>CLIENTE</th>
	                <th>RIF</th>
	                <th>CONTACTO</th>
	                <th>DIRECCION</th>
	                <th>EMAIL 1</th>
	                <th>EMAIL 2</th>
	                <th>CODIGO IMS</th>
	                <th>CODIGO SICM</th>
	                <th>TARIFA</th>
	                <th>CONSIGNACION</th>
	                <th>ACTIVO</th>
	              </tr>
	            </thead>
	            <tbody>';
	while( $row = mysqli_fetch_assoc($rs) ) {
                    $out .= '<tr>
                      <td align="left">' . $row["id"] . '</td>
                      <td align="left">' . $row["asesor"] . '</td>
                      <td align="left">' . $row["ciudad"] . '</td>
                      <td align="left">' . $row["rif"] . '</td>
                      <td align="left">' . $row["nombre"] . '</td>
                      <td align="left">' . $row["contacto"] . '</td>
                      <td align="left">' . $row["direccion"] . '</td>
                      <td align="left">' . $row["email1"] . '</td>
                      <td align="left">' . $row["email2"] . '</td>
                      <td align="left">' . $row["ims"] . '</td>
                      <td align="left">' . $row["codigo_sicm"] . '</td>
                      <td align="left">' . $row["tarifa"] . '</td>
                      <td align="left">' . $row["consignacion"] . '</td>
                      <td align="left">' . $row["activo"] . '</td>
                    </tr>';
	}
	     $out .= '</tbody>
	          </table>
	        </div>';
	$out .= '</div>';

	echo "$out";
?>