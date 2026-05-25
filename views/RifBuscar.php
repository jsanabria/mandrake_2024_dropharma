<?php

namespace PHPMaker2024\mandrake;

// Page object
$RifBuscar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

// Limpiar cualquier búfer previo de PHPMaker para evitar fugas de HTML/Espacios
if (ob_get_length()) ob_end_clean();

// Forzar encabezados HTTP de JSON puro
header('Content-Type: application/json; charset=utf-8');

// Capturar y sanitizar los parámetros por GET
$ci_rif = isset($_GET["ci_rif"]) ? trim($_GET["ci_rif"]) : '';
$tipo = isset($_GET["tipo"]) ? strtoupper(trim($_GET["tipo"])) : 'CLIENTE';
$accion = isset($_GET["accion"]) ? strtoupper(trim($_GET["accion"])) : 'I';

if ($ci_rif === '') {
    echo json_encode(["existe" => false, "cantidad" => 0]);
    exit;
}

// 1. Estandarizar el RIF quitando caracteres especiales comunes en Venezuela
$rifLimpio = str_replace(['.', '-', ' '], '', $ci_rif);

// 2. Mapear de forma segura la tabla destino
$tabla = ($tipo === "PROVEEDOR") ? "proveedor" : "cliente";

// 3. Ejecutar consulta limpiando la columna en la BD con REPLACE para evitar falsos negativos
$sql = "SELECT COUNT(ci_rif) AS cantidad 
        FROM $tabla 
        WHERE REPLACE(REPLACE(REPLACE(ci_rif, '.', ''), '-', ''), ' ', '') = '$rifLimpio'";

$cantidad = (int)ExecuteScalar($sql);

// Evaluar duplicado según el contexto (I = Inserción, E = Edición)
$limiteMaximo = ($accion === "I") ? 0 : 1;
$existe = ($cantidad > $limiteMaximo);

// Imprimir la respuesta JSON estructurada
echo json_encode([
    "existe" => $existe,
    "cantidad" => $cantidad,
    "rif_procesado" => $rifLimpio
]);

// Detener el flujo de Slim por completo para que no intente anexar layouts visuales
exit;

?>
<?= GetDebugMessage() ?>
