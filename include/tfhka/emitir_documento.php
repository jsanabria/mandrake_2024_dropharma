<?php

namespace PHPMaker2024\mandrake;

function TFHKA_ValorArray($array, $paths, $default = "")
{
    foreach ($paths as $path) {
        $cur = $array;
        $ok = true;

        foreach (explode(".", $path) as $parte) {
            if (!is_array($cur) || !array_key_exists($parte, $cur)) {
                $ok = false;
                break;
            }
            $cur = $cur[$parte];
        }

        if ($ok && $cur !== null && $cur !== "") {
            return $cur;
        }
    }
    return $default;
}

function TFHKA_EmitirDocumento($pedido)
{
    TFHKA_AsegurarEstructura();

    try {
        $doc = TFHKA_ConstruirDocumento($pedido);
    } catch (\Throwable $e) {
        return ["ok" => false, "codigo" => "LOCAL", "mensaje" => $e->getMessage()];
    }

    $cfg = TFHKA_Config();
    $payload = $doc["json"];
    $requestJson = json_encode(
        $payload,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
    );

    $documento = strtoupper(trim((string)($doc["cabecera"]["documento"] ?? "")));
    $numeroDocumento = $doc["numero_documento"];

    $idLog = TFHKA_RegistrarIntento($pedido, $documento, $numeroDocumento, $requestJson);

    $auth = TFHKA_Autenticar(false);
    if (!$auth["ok"]) {
        TFHKA_CerrarIntento($idLog, [
            "codigo_http" => intval($auth["http"]["http_code"] ?? 0),
            "codigo_respuesta" => "AUTH",
            "mensaje" => $auth["mensaje"],
            "response_json" => $auth["http"]["body_raw"] ?? "",
            "estatus" => "ERROR_AUTH",
            "tiempo_respuesta_ms" => intval($auth["http"]["elapsed_ms"] ?? 0),
        ]);
        return ["ok" => false, "codigo" => "AUTH", "mensaje" => $auth["mensaje"]];
    }

    $url = $cfg["base_url"] . "/api/Emision";
    $resp = TFHKA_HttpJson("POST", $url, $payload, $auth["token"]);

    // Solo una renovación de token ante 401.
    if ($resp["http_code"] === 401) {
        TFHKA_InvalidarToken($cfg["ambiente"]);
        $auth2 = TFHKA_Autenticar(true);
        if ($auth2["ok"]) {
            $resp = TFHKA_HttpJson("POST", $url, $payload, $auth2["token"]);
        }
    }

    $json = is_array($resp["json"]) ? $resp["json"] : [];

    $codigo = (string)TFHKA_ValorArray($json, [
        "codigo", "Codigo", "resultado.codigo", "resultado.Codigo"
    ], "");

    $mensaje = (string)TFHKA_ValorArray($json, [
        "mensaje", "Mensaje", "message", "resultado.mensaje"
    ], ($resp["error"] !== "" ? $resp["error"] : "Sin mensaje de respuesta."));

    $control = (string)TFHKA_ValorArray($json, [
        "resultado.numeroControl", "resultado.NumeroControl", "numeroControl", "NumeroControl"
    ], "");

    $transaccion = (string)TFHKA_ValorArray($json, [
        "resultado.transaccionId", "resultado.TransaccionId", "transaccionId", "TransaccionId"
    ], "");

    $validaciones = TFHKA_ValorArray($json, [
        "validaciones", "Validaciones", "resultado.validaciones"
    ], []);

    $ok = (
        $resp["ok_transport"] &&
        $resp["http_code"] >= 200 &&
        $resp["http_code"] < 300 &&
        $codigo === "200"
    );

    TFHKA_CerrarIntento($idLog, [
        "numero_control" => $control,
        "transaccion_id" => $transaccion,
        "codigo_http" => $resp["http_code"],
        "codigo_respuesta" => $codigo,
        "mensaje" => $mensaje,
        "validaciones_json" => is_array($validaciones)
            ? json_encode($validaciones, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : (string)$validaciones,
        "response_json" => $resp["body_raw"],
        "estatus" => $ok ? "PROCESADO" : (($codigo === "201") ? "DUPLICADO" : "ERROR"),
        "tiempo_respuesta_ms" => $resp["elapsed_ms"],
    ]);

    if (!$ok) {
        $detalle = (is_array($validaciones) && $validaciones)
            ? " Validaciones: " . json_encode($validaciones, JSON_UNESCAPED_UNICODE)
            : "";

        return [
            "ok" => false,
            "codigo" => ($codigo !== "" ? $codigo : (string)$resp["http_code"]),
            "mensaje" => trim($mensaje . $detalle),
            "numero_documento" => $numeroDocumento,
            "numero_control" => $control,
            "transaccion_id" => $transaccion,
            "http_code" => $resp["http_code"],
        ];
    }

    if ($control === "") {
        return [
            "ok" => false,
            "codigo" => "SIN_CONTROL",
            "mensaje" => "TFHKA respondió 200 pero no se encontró numeroControl en la respuesta.",
            "numero_documento" => $numeroDocumento,
            "transaccion_id" => $transaccion,
        ];
    }

    return [
        "ok" => true,
        "codigo" => "200",
        "mensaje" => $mensaje,
        "numero_documento" => $numeroDocumento,
        "numero_control" => $control,
        "transaccion_id" => $transaccion,
        "http_code" => $resp["http_code"],
        "ambiente" => $cfg["ambiente"],
    ];
}
