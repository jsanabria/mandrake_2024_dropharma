<?php

namespace PHPMaker2024\mandrake;

function TFHKA_HttpJson($method, $url, $body = null, $token = null)
{
    if (!function_exists("curl_init")) {
        return [
            "ok_transport" => false,
            "http_code" => 0,
            "body_raw" => "",
            "json" => null,
            "error" => "La extensión cURL de PHP no está disponible.",
            "elapsed_ms" => 0,
        ];
    }

    $cfg = TFHKA_Config();
    $ch = curl_init();

    $headers = [
        "Content-Type: application/json",
        "Accept: application/json",
    ];

    if ($token !== null && trim((string)$token) !== "") {
        $headers[] = "Authorization: Bearer " . trim((string)$token);
    }

    $opts = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => intval($cfg["connect_timeout"]),
        CURLOPT_TIMEOUT => intval($cfg["timeout"]),
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ];

    if ($body !== null) {
        $opts[CURLOPT_POSTFIELDS] = is_string($body)
            ? $body
            : json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    curl_setopt_array($ch, $opts);

    $ini = microtime(true);
    $raw = curl_exec($ch);
    $elapsed = (int)round((microtime(true) - $ini) * 1000);

    $error = curl_error($ch);
    $httpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
    curl_close($ch);

    $raw = ($raw === false) ? "" : (string)$raw;
    $json = ($raw !== "") ? json_decode($raw, true) : null;

    return [
        "ok_transport" => ($error === ""),
        "http_code" => $httpCode,
        "body_raw" => $raw,
        "json" => is_array($json) ? $json : null,
        "error" => $error,
        "elapsed_ms" => $elapsed,
    ];
}
