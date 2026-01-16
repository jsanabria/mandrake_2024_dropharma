<?php
session_start();

include "connect.php"; 

$id = $_REQUEST["id"];

$sql = "INSERT INTO abono
			(id, cliente, fecha, metodo_pago, pago, nota, username)
		SELECT 
			NULL, cliente, fecha, 'IMPRIMIR' AS metodo_pago, 0 AS pago, 'REVERSO id: $id' AS nota, username 
		FROM recarga WHERE id = $id;"; 
mysqli_query($link, $sql);

$sql = "SELECT LAST_INSERT_ID() AS abono;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$Abono = $row["abono"];

$sql = "INSERT INTO recarga
			(id, cliente, fecha, metodo_pago, referencia, reverso, 
			monto_moneda, moneda, tasa_moneda, monto_bs, tasa_usd, monto_usd, saldo, 
			nota, username, cobro_cliente_reverso, nro_recibo, nota_recepcion, abono)
		SELECT 
			NULL, cliente, fecha, metodo_pago, referencia, 'S' AS reverso, 
			monto_moneda, moneda, tasa_moneda, (-1)*monto_bs, tasa_usd, (-1)*monto_usd, 0 AS saldo, 
			nota, username, cobro_cliente_reverso, nro_recibo, nota_recepcion, $Abono AS abono
			FROM recarga WHERE id = $id;";
mysqli_query($link, $sql);
$sql = "SELECT LAST_INSERT_ID() AS recarga;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$recarga = $row["recarga"];

$sql = "SELECT cliente FROM recarga WHERE id = $recarga;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cliente = $row["cliente"];

$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga WHERE cliente = $cliente;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$saldo = $row["saldo"];

$sql = "UPDATE recarga SET saldo = $saldo WHERE id = $recarga;";
mysqli_query($link, $sql);


$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$monto_abono = $row["pago"];

$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
mysqli_query($link, $sql);


header("Location: ../AbonoList");
?>