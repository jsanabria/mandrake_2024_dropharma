<?php 
include "connect.php";

$date = isset($_REQUEST["fecha"]) ? $_REQUEST["fecha"] : date("Y-m-d");

?>
<div class="row">
	<div class="panel panel-default ewGrid entradas_salidas">
		<div class="table-responsive ewGridMiddlePanel">
			<!--<table class="table table-bordered table-condensed table-striped">-->
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th>Usuario</th>
						<th>Grupo</th>
						<th>Asesor</th>
						<th>Cliente</th>
						<th>Fecha</th>
						<th>Acci&oacute;n</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql = "SELECT 
									IFNULL(b.nombre, a.user) AS usuario, 
									c.userlevelname AS grupo, 
									IF(b.asesor IS NULL, 'NO', 'SI') AS asesor, 
									IF(b.cliente IS NULL, 'NO', 'SI') AS cliente, 
									DATE_FORMAT(a.datetime, '%d/%m/%Y %h:%i:%s %p') AS fecha, 
									a.action 
								FROM 
									audittrail AS a  
									LEFT OUTER JOIN usuario AS b ON b.username = a.user 
									LEFT OUTER JOIN userlevels AS c ON c.userlevelid = b.userlevelid 
								WHERE 
									DATE_FORMAT(a.datetime, '%Y-%m-%d') = '$date' 
									AND a.action IN ('login', 'logout') AND a.user <> '-1' 
								ORDER BY a.datetime DESC;"; 
						$rs = mysqli_query($link, $sql);
						while($row = mysqli_fetch_array($rs)) {
							echo '<tr>';
								echo '<td>' . $row["usuario"] . '</td>';
								echo '<td>' . $row["grupo"] . '</td>';
								echo '<td>' . $row["asesor"] . '</td>';
								echo '<td>' . $row["cliente"] . '</td>';
								echo '<td>' . $row["fecha"] . '</td>';
								echo '<td>' . $row["action"] . '</td>';
							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
