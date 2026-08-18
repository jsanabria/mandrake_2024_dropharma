<?php

namespace PHPMaker2024\mandrake;

function TFHKA_FormatoMonto($valor)
{
    return number_format(round((float)$valor, 2), 2, ".", "");
}

function TFHKA_FormatoTasa($valor)
{
    return number_format(round((float)$valor, 4), 4, ".", "");
}

function TFHKA_TipoDocumento($documento)
{
    switch (strtoupper(trim((string)$documento))) {
        case "FC": return "01";
        case "NC": return "02";
        case "ND": return "03";
        default: return "";
    }
}

function TFHKA_TipoIdentificacion($rif)
{
    $primer = substr(strtoupper(trim((string)$rif)), 0, 1);
    return in_array($primer, ["V", "J", "E", "P", "G", "C"], true) ? $primer : "";
}

function TFHKA_NumeroIdentificacion($rif)
{
    return preg_replace('/\D+/', '', (string)$rif);
}

function TFHKA_Moneda($moneda)
{
    $cfg = TFHKA_Config();
    $m = strtoupper(trim((string)$moneda));

    if (strpos($m, "BS") === 0 || in_array($m, ["VES", "VED", "VEF"], true)) {
        return $cfg["moneda_bolivar"];
    }
    if (strpos($m, "USD") === 0) return "USD";
    if (strpos($m, "EUR") === 0) return "EUR";

    return substr($m, 0, 3);
}


function TFHKA_FormaPagoMandrake($metodoPago, $monedaMandrake = "")
{
    $metodo = strtoupper(trim((string)$metodoPago));
    $moneda = strtoupper(trim((string)$monedaMandrake));

    switch ($metodo) {
        case "PM": return "02"; // Pago Móvil
        case "TR": return "03"; // Transferencia de fondos
        case "TD": return "05"; // Tarjeta de Débito
        case "TC": return "06"; // Tarjeta de Crédito
        case "CH": return "07"; // Cheque
        case "EF":
            // Efectivo en bolívares = 08; efectivo en divisas = 09.
            return (strpos($moneda, "BS") === 0 || in_array($moneda, ["VED", "VES", "VEF"], true))
                ? "08"
                : "09";
        case "CC": return "99"; // Cuenta por cobrar / crédito
        case "AN": return "99"; // Anticipo
        case "RC": return "99"; // Recarga
        default:   return "99"; // Otros medios de pago
    }
}

function TFHKA_EsRetencionMandrake($metodoPago)
{
    return in_array(strtoupper(trim((string)$metodoPago)), [
        "RI", // Retención IVA 75%
        "RJ", // Retención IVA 100%
        "RR", // Retención ISLR
        "RM", // Retención Municipal
        "IG", // IGTF registrado como línea de cobro
    ], true);
}

function TFHKA_ObtenerFormasPagoMandrake($pedido, $totalAPagar, $fechaDocumento, $monedaDocumento, $tasaDia)
{
    $pedido = intval($pedido);
    $cfg = TFHKA_Config();

    $rows = ExecuteRows("\n        SELECT\n            a.metodo_pago,\n            IFNULL(b.valor2, a.metodo_pago) AS metodo_descripcion,\n            a.referencia,\n            IFNULL(a.monto_bs, 0) AS monto_bs,\n            a.moneda,\n            IFNULL(a.monto_moneda, 0) AS monto_moneda,\n            a.banco,\n            d.campo_descripcion AS banco_descripcion\n        FROM cobros_cliente_detalle AS a\n        LEFT JOIN parametro AS b\n            ON b.codigo = '009'\n           AND b.valor1 = a.metodo_pago\n        LEFT JOIN compania_cuenta AS c\n            ON c.id = a.banco\n        LEFT JOIN tabla AS d\n            ON d.campo_codigo = c.banco\n           AND d.tabla = 'BANCO'\n        WHERE a.cobros_cliente IN (\n            SELECT id\n            FROM cobros_cliente\n            WHERE id_documento = {$pedido}\n        )\n          AND a.metodo_pago <> 'IG'\n        ORDER BY a.id\n    ");

    $formas = [];

    foreach ($rows ?: [] as $row) {
        $metodo = strtoupper(trim((string)($row["metodo_pago"] ?? "")));

        // Las retenciones no se informan como medios de pago ordinarios.
        // TFHKA dispone de un flujo específico para aplicar retenciones.
        if (TFHKA_EsRetencionMandrake($metodo)) {
            continue;
        }

        $monedaMandrake = trim((string)($row["moneda"] ?? ""));
        $monedaTFHKA = TFHKA_Moneda($monedaMandrake);
        $montoBs = abs((float)($row["monto_bs"] ?? 0));
        $montoMoneda = abs((float)($row["monto_moneda"] ?? 0));

        $esBolivar = (
            strpos(strtoupper($monedaMandrake), "BS") === 0 ||
            in_array($monedaTFHKA, ["VED", "VES", "VEF"], true)
        );

        $monto = $esBolivar ? $montoBs : $montoMoneda;
        if ($monto <= 0) {
            continue;
        }

        $descripcion = trim((string)($row["metodo_descripcion"] ?? $metodo));
        $banco = trim((string)($row["banco_descripcion"] ?? ""));
        $referencia = trim((string)($row["referencia"] ?? ""));

        if ($banco !== "") {
            $descripcion .= " - " . $banco;
        }
        if ($referencia !== "") {
            $descripcion .= " Ref. " . $referencia;
        }

        $forma = [
            "descripcion" => mb_substr($descripcion, 0, 100, "UTF-8"),
            "fecha" => TFHKA_FechaDocumento($fechaDocumento),
            "forma" => TFHKA_FormaPagoMandrake($metodo, $monedaMandrake),
            "monto" => TFHKA_FormatoMonto($monto),
            "moneda" => $monedaTFHKA,
        ];

        if (!$esBolivar) {
            // Preferimos la tasa real implícita de ese pago.
            $tipoCambio = ($montoMoneda > 0 && $montoBs > 0)
                ? ($montoBs / $montoMoneda)
                : (float)$tasaDia;

            if ($tipoCambio <= 0) {
                throw new \Exception(
                    "El cobro {$metodo} en {$monedaMandrake} no tiene una tasa de cambio válida."
                );
            }

            $forma["tipoCambio"] = TFHKA_FormatoTasa($tipoCambio);
        }

        $formas[] = $forma;
    }

    if ($formas) {
        return $formas;
    }

    // Si todavía no existen cobros (p. ej. factura a crédito), se informa una
    // forma genérica. Luego podemos enlazar CC explícitamente si el flujo lo requiere.
    $formaFallback = [
        "descripcion" => "Cuenta por cobrar",
        "fecha" => TFHKA_FechaDocumento($fechaDocumento),
        "forma" => $cfg["forma_pago_default"],
        "monto" => TFHKA_FormatoMonto($totalAPagar),
        "moneda" => $monedaDocumento,
    ];

    if (!in_array($monedaDocumento, ["VED", "VES", "VEF"], true) && (float)$tasaDia > 0) {
        $formaFallback["tipoCambio"] = TFHKA_FormatoTasa($tasaDia);
    }

    return [$formaFallback];
}

function TFHKA_CodigoImpuesto($alicuota)
{
    $a = round((float)$alicuota, 2);
    if (abs($a - 8.00) < 0.001) return "R";
    if (abs($a - 16.00) < 0.001) return "G";
    if (abs($a - 31.00) < 0.001) return "A";
    if (abs($a) < 0.001) return "E";
    return "";
}

function TFHKA_UnidadMedida($unidadMandrake)
{
    $cfg = TFHKA_Config();
    $u = strtoupper(trim((string)$unidadMandrake));
    $map = [
        "UDM001" => $cfg["unidad_medida_default"],
        "UND" => $cfg["unidad_medida_default"],
        "UNIDAD" => $cfg["unidad_medida_default"],
    ];
    return $map[$u] ?? $cfg["unidad_medida_default"];
}

function TFHKA_FechaDocumento($fecha)
{
    $ts = strtotime((string)$fecha);
    return date("d/m/Y", $ts ?: time());
}

function TFHKA_HoraDocumento($fecha)
{
    $ts = strtotime((string)$fecha);
    return date("h:i:s a", $ts ?: time());
}

function TFHKA_AsegurarNumeroDocumentoMandrake($pedido, $documento)
{
    $pedido = intval($pedido);

    $actual = trim((string)ExecuteScalar("
        SELECT nro_documento FROM salidas WHERE id = {$pedido} LIMIT 1
    "));
    if ($actual !== "") {
        return $actual;
    }

    switch (strtoupper($documento)) {
        case "FC": $codigoParametro = "003"; break;
        case "NC": $codigoParametro = "010"; break;
        case "ND": $codigoParametro = "011"; break;
        default: throw new \Exception("Tipo de documento no soportado para TFHKA.");
    }

    $serieDoc = strtoupper($documento) . "_DOC";
    $numeroDoc = intval(ReservarConsecutivoDocumento("TDCFCV", $serieDoc));

    $p = ExecuteRow("
        SELECT valor2, valor3 FROM parametro
        WHERE codigo = '{$codigoParametro}' LIMIT 1
    ");
    if (!$p) {
        throw new \Exception("No existe configuración del correlativo {$codigoParametro}.");
    }

    $numero = trim((string)($p["valor2"] ?? "")) .
        str_pad($numeroDoc, intval($p["valor3"] ?? 0), "0", STR_PAD_LEFT);

    ExecuteStatement("
        UPDATE salidas
        SET nro_documento = '" . TFHKA_Sql($numero) . "',
            fecha = NOW(),
            username = '" . TFHKA_Sql(CurrentUserName()) . "'
        WHERE id = {$pedido}
          AND (nro_documento IS NULL OR nro_documento = '')
        LIMIT 1
    ");

    return trim((string)ExecuteScalar("
        SELECT nro_documento FROM salidas WHERE id = {$pedido} LIMIT 1
    "));
}

function TFHKA_ObtenerDocumentoAfectado($cabecera)
{
    $docAfe = intval($cabecera["doc_afe"] ?? 0);
    if ($docAfe > 0) {
        $row = ExecuteRow("SELECT * FROM salidas WHERE id = {$docAfe} LIMIT 1");
        if ($row) return $row;
    }

    $nro = trim((string)($cabecera["doc_afectado"] ?? ""));
    if ($nro !== "") {
        $row = ExecuteRow("
            SELECT * FROM salidas
            WHERE nro_documento = '" . TFHKA_Sql($nro) . "'
            ORDER BY id DESC LIMIT 1
        ");
        if ($row) return $row;
    }

    return null;
}

function TFHKA_ConstruirDocumento($pedido)
{
    $pedido = intval($pedido);

    $cabecera = ExecuteRow("
        SELECT s.*, s.email AS cliente_email
        FROM salidas AS s
        WHERE s.id = {$pedido}
        LIMIT 1
    ");
    if (!$cabecera) {
        throw new \Exception("No se encontró la cabecera del documento.");
    }

    $documento = strtoupper(trim((string)($cabecera["documento"] ?? "")));
    $numeroDocumento = TFHKA_AsegurarNumeroDocumentoMandrake($pedido, $documento);

    $cabecera = ExecuteRow("
        SELECT s.*, s.email AS cliente_email
        FROM salidas AS s
        WHERE s.id = {$pedido}
        LIMIT 1
    ");

    $detalles = ExecuteRows("
        SELECT es.*, a.articulo_inventario
        FROM entradas_salidas AS es
        LEFT JOIN articulo AS a ON a.id = es.articulo
        WHERE es.id_documento = {$pedido}
          AND es.tipo_documento = 'TDCFCV'
        ORDER BY es.id
    ");

    $errores = [];
    $rif = trim((string)($cabecera["cliente_ci_rif"] ?? ""));

    if (TFHKA_TipoDocumento($documento) === "") $errores[] = "Tipo de documento no soportado.";
    if ($numeroDocumento === "") $errores[] = "Falta número de documento.";
    if (TFHKA_TipoIdentificacion($rif) === "") $errores[] = "Tipo de identificación del comprador inválido.";
    if (TFHKA_NumeroIdentificacion($rif) === "") $errores[] = "Número de identificación del comprador vacío.";
    if (trim((string)($cabecera["cliente_nombre"] ?? "")) === "") $errores[] = "Razón social del comprador vacía.";
    if (trim((string)($cabecera["cliente_direccion"] ?? "")) === "") $errores[] = "Dirección fiscal del comprador vacía.";
    if (!$detalles) $errores[] = "Documento sin renglones.";

    foreach ($detalles as $i => $d) {
        if (trim((string)($d["articulo_descripcion"] ?? "")) === "") {
            $errores[] = "Renglón " . ($i + 1) . ": falta descripción fiscal congelada.";
        }
        if (TFHKA_CodigoImpuesto($d["alicuota"] ?? 0) === "") {
            $errores[] = "Renglón " . ($i + 1) . ": alícuota no contemplada en el catálogo TFHKA recibido.";
        }
    }

    if ($errores) {
        throw new \Exception("Validación local TFHKA: " . implode(" | ", $errores));
    }

    $cfg = TFHKA_Config();
    $fechaBase = $cabecera["fecha"] ?? date("Y-m-d H:i:s");
    $moneda = TFHKA_Moneda($cabecera["moneda"] ?? "Bs.");
    $tasaCambio = (float)($cabecera["tasa_dia"] ?? 0);

    $identificacion = [
        "tipoDocumento" => TFHKA_TipoDocumento($documento),
        "numeroDocumento" => (string)$numeroDocumento,
        "tipoTransaccion" => ($documento === "FC") ? "01" : "02",
        "fechaEmision" => TFHKA_FechaDocumento($fechaBase),
        "fechaVencimiento" => TFHKA_FechaDocumento(
            ($documento === "FC" && intval($cabecera["dias_credito"] ?? 0) > 0)
                ? date("Y-m-d H:i:s", strtotime($fechaBase . " +" . intval($cabecera["dias_credito"]) . " days"))
                : $fechaBase
        ),
        "horaEmision" => TFHKA_HoraDocumento($fechaBase),
        "tipoDePago" => (($cabecera["entregado"] ?? "S") === "N") ? "Crédito" : "Contado",
        "serie" => $cfg["serie"],
        "tipoDeVenta" => "Interna",
        "moneda" => $moneda,
    ];

    if (in_array($documento, ["NC", "ND"], true)) {
        $afectado = TFHKA_ObtenerDocumentoAfectado($cabecera);
        if (!$afectado) {
            throw new \Exception("No se localizó la factura afectada requerida por {$documento}.");
        }

        $identificacion["serieFacturaAfectada"] = $cfg["serie"];
        $identificacion["numeroFacturaAfectada"] = (string)($afectado["nro_documento"] ?? "");
        $identificacion["fechaFacturaAfectada"] = TFHKA_FechaDocumento($afectado["fecha"] ?? "");
        $identificacion["montoFacturaAfectada"] = TFHKA_FormatoMonto($afectado["total"] ?? 0);
        $identificacion["comentarioFacturaAfectada"] =
            trim((string)($cabecera["nota"] ?? "")) !== ""
                ? mb_substr(trim((string)$cabecera["nota"]), 0, 255, "UTF-8")
                : (($documento === "NC") ? "Nota de Crédito" : "Nota de Débito");
    }

    $comprador = [
        "tipoIdentificacion" => TFHKA_TipoIdentificacion($rif),
        "numeroIdentificacion" => TFHKA_NumeroIdentificacion($rif),
        "razonSocial" => mb_substr(trim((string)$cabecera["cliente_nombre"]), 0, 100, "UTF-8"),
        "direccion" => mb_substr(trim((string)$cabecera["cliente_direccion"]), 0, 255, "UTF-8"),
        "pais" => "VE",
        "notificar" => "No",
    ];

    $telefono = preg_replace('/\D+/', '', (string)($cabecera["cliente_telefono"] ?? ""));
    if ($telefono !== "") $comprador["telefono"] = [substr($telefono, 0, 20)];

    $correo = trim((string)($cabecera["cliente_email"] ?? ""));
    if ($correo !== "" && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $comprador["correo"] = [mb_substr($correo, 0, 50, "UTF-8")];
    }

    $items = [];
    $impuestos = [];
    $gravado = 0.0;
    $exento = 0.0;
    $ivaTotal = 0.0;
    $antesDescuento = 0.0;
    $descuentoTotal = 0.0;

    $desc2 = max(0, (float)($cabecera["descuento2"] ?? 0));
    $desc3 = max(0, (float)($cabecera["descuento3"] ?? 0));

    foreach ($detalles as $idx => $d) {
        $cantidad = abs((float)($d["cantidad_movimiento"] ?? 0));
        if ($cantidad <= 0) $cantidad = abs((float)($d["cantidad_articulo"] ?? 0));
        if ($cantidad <= 0) throw new \Exception("Renglón " . ($idx + 1) . ": cantidad inválida.");

        $precioLinea = (float)($d["precio"] ?? 0);
        $precioAntes = (float)($d["precio_unidad_sin_desc"] ?? 0) * $cantidad;
        if ($precioAntes <= 0) $precioAntes = $precioLinea;

        $precioItem = $precioLinea;
        $precioItem -= $precioItem * ($desc2 / 100);
        $precioItem -= $precioItem * ($desc3 / 100);
        $precioItem = round($precioItem, 2);

        $alicuota = (float)($d["alicuota"] ?? 0);
        $codigoImp = TFHKA_CodigoImpuesto($alicuota);
        $iva = round($precioItem * ($alicuota / 100), 2);
        $totalItem = round($precioItem + $iva, 2);
        $unitario = round($precioItem / $cantidad, 2);

        $antesDescuento += $precioAntes;
        $descuentoMonto = max(0, round($precioAntes - $precioItem, 2));
        $descuentoTotal += $descuentoMonto;

        if ($alicuota > 0) $gravado += $precioItem;
        else $exento += $precioItem;
        $ivaTotal += $iva;

        if (!isset($impuestos[$codigoImp])) {
            $impuestos[$codigoImp] = [
                "codigoTotalImp" => $codigoImp,
                "alicuotaImp" => TFHKA_FormatoMonto($alicuota),
                "base" => 0.0,
                "iva" => 0.0,
            ];
        }
        $impuestos[$codigoImp]["base"] += $precioItem;
        $impuestos[$codigoImp]["iva"] += $iva;

        $item = [
            "numeroLinea" => (string)($idx + 1),
            "codigoPLU" => mb_substr(trim((string)($d["articulo_codigo"] ?? "")), 0, 20, "UTF-8"),
            "indicadorBienoServicio" => (($d["articulo_inventario"] ?? "N") === "S") ? "1" : "2",
            "descripcion" => mb_substr(trim((string)$d["articulo_descripcion"]), 0, 255, "UTF-8"),
            "cantidad" => TFHKA_FormatoMonto($cantidad),
            "unidadMedida" => TFHKA_UnidadMedida($d["articulo_unidad_medida"] ?? ""),
            "precioUnitario" => TFHKA_FormatoMonto($unitario),
            "precioItem" => TFHKA_FormatoMonto($precioItem),
            "codigoImpuesto" => $codigoImp,
            "tasaIVA" => TFHKA_FormatoMonto($alicuota),
            "valorIVA" => TFHKA_FormatoMonto($iva),
            "valorTotalItem" => TFHKA_FormatoMonto($totalItem),
        ];

        if ($descuentoMonto > 0.009) {
            $item["descuentoMonto"] = TFHKA_FormatoMonto($descuentoMonto);
            $item["precioAntesDescuento"] = TFHKA_FormatoMonto($precioAntes);
        }

        $items[] = $item;
    }

    $gravado = round($gravado, 2);
    $exento = round($exento, 2);
    $ivaTotal = round($ivaTotal, 2);
    $subtotal = round($gravado + $exento, 2);

    $montoIgtf = array_key_exists("monto_igtf", $cabecera)
        ? max(0, (float)$cabecera["monto_igtf"])
        : 0.0;
    $alicuotaIgtf = array_key_exists("alicuota_igtf", $cabecera)
        ? max(0, (float)$cabecera["alicuota_igtf"])
        : 0.0;

    $conIva = round($subtotal + $ivaTotal, 2);
    $aPagar = round($conIva + $montoIgtf, 2);

    $impuestosSubtotal = [];
    foreach ($impuestos as $imp) {
        $impuestosSubtotal[] = [
            "codigoTotalImp" => $imp["codigoTotalImp"],
            "alicuotaImp" => $imp["alicuotaImp"],
            "baseImponibleImp" => TFHKA_FormatoMonto($imp["base"]),
            "valorTotalImp" => TFHKA_FormatoMonto($imp["iva"]),
        ];
    }

    if ($montoIgtf > 0.009) {
        if ($alicuotaIgtf <= 0) $alicuotaIgtf = 3.0;
        $baseIgtf = round($montoIgtf / ($alicuotaIgtf / 100), 2);
        $impuestosSubtotal[] = [
            "codigoTotalImp" => "IGTF",
            "alicuotaImp" => TFHKA_FormatoMonto($alicuotaIgtf),
            "baseImponibleImp" => TFHKA_FormatoMonto($baseIgtf),
            "valorTotalImp" => TFHKA_FormatoMonto($montoIgtf),
        ];
    }

    $totales = [
        "nroItems" => (string)count($items),
        "montoGravadoTotal" => TFHKA_FormatoMonto($gravado),
        "montoExentoTotal" => TFHKA_FormatoMonto($exento),
        "subtotal" => TFHKA_FormatoMonto($subtotal),
        "totalAPagar" => TFHKA_FormatoMonto($aPagar),
        "totalIVA" => TFHKA_FormatoMonto($ivaTotal),
        "montoTotalConIVA" => TFHKA_FormatoMonto($conIva),
        "impuestosSubtotal" => $impuestosSubtotal,
    ];

    if ($descuentoTotal > 0.009) {
        $totales["totalDescuento"] = TFHKA_FormatoMonto($descuentoTotal);
        $totales["subtotalAntesDescuento"] = TFHKA_FormatoMonto($antesDescuento);
        $totales["listaDescBonificacion"] = [[
            "descDescuento" => "Descuentos aplicados por Mandrake",
            "montoDescuento" => TFHKA_FormatoMonto($descuentoTotal),
        ]];
    }

    // Formas de pago reales aplicadas a la factura en Mandrake.
    $totales["formasPago"] = TFHKA_ObtenerFormasPagoMandrake(
        $pedido,
        $aPagar,
        $fechaBase,
        $moneda,
        $tasaCambio
    );

    return [
        "json" => [
            "documentoElectronico" => [
                "encabezado" => [
                    "identificacionDocumento" => $identificacion,
                    "comprador" => $comprador,
                    "totales" => $totales,
                ],
                "detallesItems" => $items,
            ],
        ],
        "cabecera" => $cabecera,
        "numero_documento" => $numeroDocumento,
        "total_calculado" => $aPagar,
    ];
}
