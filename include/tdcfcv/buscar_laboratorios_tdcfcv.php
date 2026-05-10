<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

function TdcfcvHtmlResponse(string $html): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($html, JSON_UNESCAPED_UNICODE);
    exit;
}

function TdcfcvH($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$laboratorio = TdcfcvRequestText("laboratorio");

if ($laboratorio === "") {
    TdcfcvHtmlResponse("");
}

$laboratorioSql = AdjustSql($laboratorio);

$rows = ExecuteRows("
    SELECT Id, nombre
    FROM fabricante
    WHERE nombre LIKE '%{$laboratorioSql}%'
    ORDER BY nombre
    LIMIT 15
");

if (!$rows) {
    TdcfcvHtmlResponse(
        '<li class="list-group-item text-muted">No se encontraron laboratorios.</li>'
    );
}

$html = "";

foreach ($rows as $row) {
    $id = intval($row["Id"]);
    $nombre = TdcfcvH($row["nombre"]);
    $nombreJs = JsEncode($row["nombre"]);

    $html .= '<li class="list-group-item list-group-item-action" style="cursor:pointer;" onclick="js:seleccionarLaboratorio(' . $id . ', \'' . $nombreJs . '\')">';
    $html .= $nombre;
    $html .= '</li>';
}

TdcfcvHtmlResponse($html);