 <?php 
include "connect.php";

$fechad = isset($_REQUEST["fechad"]) ? $_REQUEST["fechad"] : date("Y-m-d");
$fechah = isset($_REQUEST["fechah"]) ? $_REQUEST["fechah"] : date("Y-m-d");
$tipo = $_REQUEST["tipo"];
$tipo_name = isset($_REQUEST["tipo_name"]) ? $_REQUEST["tipo_name"] : '';
$orden = $_REQUEST["orden"];
$torden = $_REQUEST["torden"];

$where = "";
$vlrs = "";

if(is_array($tipo_name)) {
	foreach ($tipo_name  as $key => $value) {
		$vlrs .= ",'$value'";
	}
}

$vlrs = substr($vlrs, 1, strlen($vlrs));

?>
<div class="container">
    <div class="row">
		<div class="col-md-5">
			<div class="row">
				<div class="panel panel-default ewGrid entradas_salidas">
					<div class="table-responsive ewGridMiddlePanel">
						<!--<table class="table table-bordered table-condensed table-striped">-->
						<table class="table table-condensed table-hover table-striped">
							<?php if($tipo == "vendedor") { ?>
							<thead>
								<tr>
									<th>Asesor</th>
									<th>Facturas</th>
									<th>Total</th>
									<th>Unidades</th>
								</tr>
							</thead>
							<?php } else { ?>
							<thead>
								<tr>
									<th>Ciudad</th>
									<th>Facturas</th>
									<th>Total</th>
									<th>Unidades</th>
								</tr>
							</thead>
							<?php } ?>
							<tbody>
								<?php
									if($tipo == "vendedor") {
										if($vlrs != "" and $vlrs != "''") $where = "AND a.asesor IN ($vlrs)"; 

										$sql = "SELECT 
													c.nombre AS asesor, 
													COUNT(a.nro_documento) AS facturas, 
													SUM(a.monto_total) AS total, 
													SUM(a.unidades) AS unidades 
												FROM 
													salidas AS a 
													LEFT OUTER JOIN usuario AS b ON b.username = a.asesor  
													LEFT OUTER JOIN asesor AS c ON c.id = b.asesor 
												WHERE 
													a.fecha BETWEEN '$fechad 00:00:00' AND '$fechah 23:59:59' 
													AND a.estatus = 'PROCESADO' 
													AND a.tipo_documento = 'TDCFCV' 
													AND IFNULL(a.documento, '') = 'FC' 
													$where 
												GROUP BY 
													c.nombre 
												ORDER BY $orden $torden;";  
										$rs = mysqli_query($link, $sql);

										$out_data = "";
										$out_data = "['Asesor', 'Facturas'],";

										$out_data2 = "";
										$out_data2 = "['Asesor', 'Totales'],";

										$out_data3 = "";
										$out_data3 = "['Asesor', 'Unidades'],";
										while($row = mysqli_fetch_array($rs)) {
											echo '<tr>';
												echo '<td>' . $row["asesor"] . '</td>';
												echo '<td>' . $row["facturas"] . '</td>';
												echo '<td>' . number_format($row["total"], 2, ",", ".") . '</td>';
												echo '<td>' . number_format($row["unidades"], 0, "", ".") . '</td>';
											echo '</tr>';

											$out_data .= "['" . $row["asesor"] . "', " . $row["facturas"] . "] ,";
											$out_data2 .= "['" . $row["asesor"] . "', " . $row["total"] . "] ,";
											$out_data3 .= "['" . $row["asesor"] . "', " . $row["unidades"] . "] ,";
										}
										$out_data = substr($out_data, 0, strlen($out_data)-1);
										$out_data2 = substr($out_data2, 0, strlen($out_data2)-1);
										$out_data3 = substr($out_data3, 0, strlen($out_data3)-1);
									} 
									else if($tipo == "ciudad") { 
										if($vlrs != "" and $vlrs != "''") $where = "AND c.campo_codigo IN ($vlrs)"; 

										$sql = "SELECT
													c.campo_descripcion AS ciudad, 
													COUNT(a.nro_documento) AS facturas, 
													SUM(a.monto_total) AS total, SUM(a.unidades) AS unidades 
												FROM 
													salidas AS a 
													LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
													LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD' 
												WHERE 
													a.fecha BETWEEN '$fechad 00:00:00' AND '$fechah 23:59:59' 
													AND a.estatus  = 'PROCESADO' AND a.tipo_documento = 'TDCFCV' 
													AND IFNULL(a.documento, '') = 'FC' 
													$where 
												GROUP BY 
													c.campo_descripcion 
												ORDER BY $orden $torden;";  
										$rs = mysqli_query($link, $sql);

										$out_data = "";
										$out_data = "['Ciudad', 'Facturas'],";

										$out_data2 = "";
										$out_data2 = "['Ciudad', 'Totales'],";

										$out_data3 = "";
										$out_data3 = "['Ciudad', 'Unidades'],";
										while($row = mysqli_fetch_array($rs)) {
											echo '<tr>';
												echo '<td>' . $row["ciudad"] . '</td>';
												echo '<td>' . $row["facturas"] . '</td>';
												echo '<td>' . number_format($row["total"], 2, ",", ".") . '</td>';
												echo '<td>' . number_format($row["unidades"], 0, "", ".") . '</td>';
											echo '</tr>';

											$out_data .= "['" . $row["ciudad"] . "', " . $row["facturas"] . "] ,";
											$out_data2 .= "['" . $row["ciudad"] . "', " . $row["total"] . "] ,";
											$out_data3 .= "['" . $row["ciudad"] . "', " . $row["unidades"] . "] ,";
										}
										$out_data = substr($out_data, 0, strlen($out_data)-1);
										$out_data2 = substr($out_data2, 0, strlen($out_data2)-1);
										$out_data3 = substr($out_data3, 0, strlen($out_data3)-1);
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-7">
			<div class="row">
				<div id="piechart" style="width: 600px; height: 400px;"></div>
			</div>
			<div class="row">
				<div id="piechart2" style="width: 600px; height: 400px;"></div>
			</div>
			<div class="row">
				<div id="piechart3" style="width: 600px; height: 400px;"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
        <?php echo $out_data; ?>
    ]);

    var options = {
      title: 'Por Facturas'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);


    var data2 = google.visualization.arrayToDataTable([
      <?php echo $out_data2; ?>
    ]);

    var options2 = {
      title: 'Por Total'
    };

    var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));

    chart2.draw(data2, options2);


    var data3 = google.visualization.arrayToDataTable([
      <?php echo $out_data3; ?>
    ]);

    var options3 = {
      title: 'Por Unidades'
    };

    var chart3 = new google.visualization.PieChart(document.getElementById('piechart3'));

    chart3.draw(data3, options3);
  }
</script>

