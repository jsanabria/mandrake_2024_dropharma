<?php

namespace PHPMaker2024\mandrake;

function TFHKA_Base64UrlDecode($data)
{
    $data = strtr((string)$data, "-_", "+/");
    $pad = strlen($data) % 4;
    if ($pad) {
        $data .= str_repeat("=", 4 - $pad);
    }
    return base64_decode($data);
}

function TFHKA_ExpiracionJwt($token)
{
    $partes = explode(".", (string)$token);
    if (count($partes) < 2) {
        return null;
    }

    $payload = json_decode(TFHKA_Base64UrlDecode($partes[1]), true);
    if (!is_array($payload) || empty($payload["exp"])) {
        return null;
    }

    $exp = intval($payload["exp"]);
    return ($exp > time()) ? $exp : null;
}

function TFHKA_ExtraerToken($json, $raw = "")
{
    if (is_array($json)) {
        $candidatos = [
            $json["token"] ?? null,
            $json["Token"] ?? null,
            $json["resultado"]["token"] ?? null,
            $json["resultado"]["Token"] ?? null,
            $json["resultado"] ?? null,
        ];

        foreach ($candidatos as $candidato) {
            if (is_string($candidato) && substr_count($candidato, ".") >= 2) {
                return trim($candidato);
            }
        }
    }

    $raw = trim((string)$raw);
    if (substr_count($raw, ".") >= 2 && strpos($raw, "{") === false) {
        return trim($raw, "\" \r\n\t");
    }

    return "";
}

function TFHKA_ObtenerTokenCache($ambiente)
{
    TFHKA_AsegurarEstructura();

    $ambiente = str_replace("'", "''", $ambiente);
    $row = ExecuteRow("
        SELECT token
        FROM fiscal_digital_token
        WHERE proveedor_api = 'TFHKA'
          AND ambiente = '{$ambiente}'
          AND expira_en > DATE_ADD(NOW(), INTERVAL 5 MINUTE)
        LIMIT 1
    ");

    return $row ? (string)$row["token"] : "";
}

function TFHKA_GuardarTokenCache($ambiente, $token)
{
    $expTs = TFHKA_ExpiracionJwt($token);

    // Los documentos recibidos indican 10 h en una guía y 12 h en otra.
    // Si el JWT contiene "exp", prevalece ese dato. Si no, usamos 9 h.
    if (!$expTs) {
        $expTs = time() + 9 * 3600;
    }

    $ambiente = str_replace("'", "''", $ambiente);
    $tokenSql = str_replace("'", "''", $token);
    $expira = date("Y-m-d H:i:s", $expTs);

    ExecuteStatement("
        INSERT INTO fiscal_digital_token
            (proveedor_api, ambiente, token, expira_en, actualizado_en)
        VALUES
            ('TFHKA', '{$ambiente}', '{$tokenSql}', '{$expira}', NOW())
        ON DUPLICATE KEY UPDATE
            token = VALUES(token),
            expira_en = VALUES(expira_en),
            actualizado_en = NOW()
    ");
}

function TFHKA_InvalidarToken($ambiente)
{
    $ambiente = str_replace("'", "''", $ambiente);
    ExecuteStatement("
        DELETE FROM fiscal_digital_token
        WHERE proveedor_api = 'TFHKA'
          AND ambiente = '{$ambiente}'
    ");
}

function TFHKA_Autenticar($forzarNuevo = false)
{
    $cfg = TFHKA_Config();
    TFHKA_AsegurarEstructura();

    if (!$forzarNuevo) {
        $token = TFHKA_ObtenerTokenCache($cfg["ambiente"]);
        if ($token !== "") {
            return ["ok" => true, "token" => $token, "origen" => "CACHE", "mensaje" => "Token reutilizado."];
        }
    }

    if ($cfg["usuario"] === "" || $cfg["clave"] === "") {
        return [
            "ok" => false,
            "token" => "",
            "origen" => "CONFIG",
            "mensaje" => "Faltan credenciales TFHKA. Configure TFHKA_USUARIO/TFHKA_CLAVE o parametros 118/119.",
        ];
    }

    $resp = TFHKA_HttpJson(
        "POST",
        $cfg["base_url"] . "/api/Autenticacion",
        ["usuario" => $cfg["usuario"], "clave" => $cfg["clave"]]
    );

    if (!$resp["ok_transport"]) {
        return [
            "ok" => false,
            "token" => "",
            "origen" => "HTTP",
            "mensaje" => "Error de comunicación al autenticar: " . $resp["error"],
            "http" => $resp,
        ];
    }

    $token = TFHKA_ExtraerToken($resp["json"], $resp["body_raw"]);
    if ($token === "") {
        return [
            "ok" => false,
            "token" => "",
            "origen" => "API",
            "mensaje" => "TFHKA no devolvió un token JWT reconocible.",
            "http" => $resp,
        ];
    }

    TFHKA_GuardarTokenCache($cfg["ambiente"], $token);

    return [
        "ok" => true,
        "token" => $token,
        "origen" => "API",
        "mensaje" => "Autenticación correcta.",
        "http" => $resp,
    ];
}
