<?php

namespace PHPMaker2024\mandrake;

// Page object
$ActualizarNotaEntrega = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php 

$id = $_REQUEST["id"];
$nota = $_REQUEST["nota"];

$sql = "UPDATE salidas SET nota = '$nota' WHERE id = '$id'";
Execute($sql);


$sql = "SELECT tipo_documento FROM salidas WHERE id = '$id'"; 
$row = ExecuteRow($sql);
$tipo = $row["tipo_documento"];


$sql = "DELETE FROM entradas_salidas 
		WHERE id_documento = '$id' AND tipo_documento = '$tipo' 
			AND IFNULL(cantidad_movimiento, 0) = 0";
Execute($sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 22-09-2021 Habia dejado de actualizar este proceso aqui, ya deben quedar las unidades actualizadas
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $id;";
Execute($sql);

/////////////
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$tasa = floatval(ExecuteScalar($sql));

$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$moneda = ExecuteScalar($sql);

$sql = "SELECT
            SUM(precio) AS precio, 
            SUM((precio * (alicuota/100))) AS iva, 
            SUM(precio) + SUM((precio * (alicuota/100))) AS total 
        FROM entradas_salidas
        WHERE tipo_documento = '$tipo_documento' AND 
            id_documento = '$id'"; 
$row = ExecuteRow($sql);
$precio = floatval($row["precio"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);

/*** Indico que alicuota iva se coloca en el encabezado del documento ***/
$sql = "SELECT 
            COUNT(DISTINCT alicuota ) AS cantidad  
        FROM 
            entradas_salidas
        WHERE 
            tipo_documento = '$tipo_documento' 
            AND id_documento = '$id';";
$row = ExecuteRow($sql);
if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
    $sql = "SELECT 
                DISTINCT alicuota 
            FROM 
                entradas_salidas
            WHERE 
                tipo_documento = '$tipo_documento' 
                AND id_documento = '$id';";
    $row = ExecuteRow($sql);
    $alicuota = floatval($row["alicuota"]);
}

$monto_usd = $total/(substr(strtoupper($moneda), 0, 2) == "BS" ? 1, $tasa);
$sql = "UPDATE salidas 
        SET
            monto_total = $precio,
            alicuota_iva = $alicuota,
            iva = $iva,
            total = $total, monto_usd = $monto_usd 
        WHERE id = '$id'"; 
Execute($sql);
/////////////

/**************/

echo "1";
die();
?>

<?= GetDebugMessage() ?>
