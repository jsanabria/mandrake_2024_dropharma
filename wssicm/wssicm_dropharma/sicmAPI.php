<?php 
header('Content-type: application/json; charset=utf-8');

if(isset($_GET['user']) && intval($_GET['user'])) { 

	if(intval($_GET['user']) == 365) {
		if(isset($_GET['app'])) {
			switch(trim($_GET['app'])) {
			case "factura":
				include("connect.php");

				// $id = isset($_GET['id']) ? intval($_GET['id']): 0;

				$sql = "SELECT 
							a.cliente AS CODCLIENTE, 
							a.fecha AS FECHA_FACTURA, 
							b.nombre AS NOMBRE_CLIENTE, 
							b.ci_rif AS RIF, 
							b.web AS CODIGO_SICM, 
							a.nro_documento AS NUMERO_FACTURA 
						FROM 
							salidas AS a 
							JOIN cliente AS b ON b.id = a.cliente 
						WHERE 
							a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND a.documento IN ('FC', 'ND') 
							AND YEAR(a.fecha) >= 2022;";
				$rs = mysqli_query($link, $sql);

				$objFacturas = new stdClass();
				$listaFacturas = [];

				while($row = mysqli_fetch_array($rs)) {
					$facturas = new stdClass();
					$facturas->CODCLIENTE = $row["CODCLIENTE"];
					$facturas->FECHA_FACTURA = $row["FECHA_FACTURA"];
					$facturas->NOMBRE_CLIENTE = $row["NOMBRE_CLIENTE"];
					$facturas->RIF = $row["RIF"];
					$facturas->CODIGO_SICM = $row["CODIGO_SICM"];
					$facturas->NUMERO_FACTURA = $row["NUMERO_FACTURA"];

					$listaFacturas[] = $facturas;
				}

				mysqli_close($link);

				$objFacturas->listaFacturas = $listaFacturas;
				echo json_encode($objFacturas, JSON_UNESCAPED_UNICODE);

				break;
			case "factura_detalle":
				include("connect.php");

				// $id = isset($_GET['id']) ? intval($_GET['id']): 0;

				$sql = "SELECT 
							a.articulo AS CODPROD, 
							CONCAT(IFNULL(c.nombre_comercial, ''), ' ', IFNULL(c.principio_activo, ''), ' ', 
									IFNULL(c.presentacion, ''), ' ', IFNULL(d.nombre, '')) AS NOMBRE_PRODUCTO, 
							c.codigo_de_barra	AS CODIGO_BARRA, 
							a.fecha_vencimiento AS FECHA_LOTE, a.lote AS NOMBRE_LOTE, 
							a.precio_unidad AS PRECIO_PRODUCTO, a.cantidad_articulo AS CANTIDAD_SOLICITADA, b.nro_documento AS NUMERO_FACTURA 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
							JOIN articulo AS c ON c.id = a.articulo 
							JOIN fabricante AS d ON d.Id = a.fabricante 
						WHERE 
							b.tipo_documento = 'TDCFCV' AND b.estatus = 'PROCESADO' AND b.documento IN ('FC', 'ND')
							AND YEAR(b.fecha) >= 2022;";
				$rs = mysqli_query($link, $sql);

				$objFacturaDetalle = new stdClass();
				$listaFacturaDetalle = [];

				while($row = mysqli_fetch_array($rs)) {
					$facturasdetalle = new stdClass();
					$facturasdetalle->CODPROD = $row["CODPROD"];
					$facturasdetalle->NOMBRE_PRODUCTO = $row["NOMBRE_PRODUCTO"];
					$facturasdetalle->CODIGO_BARRA = $row["CODIGO_BARRA"];
					$facturasdetalle->FECHA_LOTE = $row["FECHA_LOTE"];
					$facturasdetalle->NOMBRE_LOTE = $row["NOMBRE_LOTE"];
					$facturasdetalle->PRECIO_PRODUCTO = $row["PRECIO_PRODUCTO"];
					$facturasdetalle->CANTIDAD_SOLICITADA = $row["CANTIDAD_SOLICITADA"];
					$facturasdetalle->NUMERO_FACTURA = $row["NUMERO_FACTURA"];

					$listaFacturaDetalle[] = $facturasdetalle;
				}

				mysqli_close($link);

				$objFacturaDetalle->listaFacturaDetalle = $listaFacturaDetalle;
				echo json_encode($objFacturaDetalle, JSON_UNESCAPED_UNICODE);

				break;
			}
		}
	}
}
?>