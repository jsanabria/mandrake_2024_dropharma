<?php

namespace PHPMaker2024\mandrake;

function TFHKA_Sql($valor)
{
    return str_replace("'", "''", (string)$valor);
}

function TFHKA_SiguienteIntento($pedido)
{
    $pedido = intval($pedido);
    return intval(ExecuteScalar("
        SELECT IFNULL(MAX(intento), 0) + 1
        FROM fiscal_digital_transaccion
        WHERE proveedor_api = 'TFHKA'
          AND tipo_documento = 'TDCFCV'
          AND id_documento = {$pedido}
    "));
}

function TFHKA_RegistrarIntento($pedido, $documento, $numeroDocumento, $requestJson)
{
    $cfg = TFHKA_Config();
    $pedido = intval($pedido);
    $intento = TFHKA_SiguienteIntento($pedido);

    ExecuteStatement("
        INSERT INTO fiscal_digital_transaccion
            (tipo_documento, id_documento, proveedor_api, ambiente, intento,
             numero_documento, request_json, estatus, fecha_envio, username)
        VALUES
            ('TDCFCV', {$pedido}, 'TFHKA', '" . TFHKA_Sql($cfg["ambiente"]) . "', {$intento},
             '" . TFHKA_Sql($numeroDocumento) . "', '" . TFHKA_Sql($requestJson) . "',
             'ENVIANDO', NOW(), '" . TFHKA_Sql(CurrentUserName()) . "')
    ");

    return intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));
}

function TFHKA_CerrarIntento($id, $data)
{
    $id = intval($id);
    $control = TFHKA_Sql($data["numero_control"] ?? "");
    $transaccion = TFHKA_Sql($data["transaccion_id"] ?? "");
    $codigo = TFHKA_Sql($data["codigo_respuesta"] ?? "");
    $mensaje = TFHKA_Sql($data["mensaje"] ?? "");
    $validaciones = TFHKA_Sql($data["validaciones_json"] ?? "");
    $response = TFHKA_Sql($data["response_json"] ?? "");
    $estatus = TFHKA_Sql($data["estatus"] ?? "ERROR");
    $http = intval($data["codigo_http"] ?? 0);
    $ms = intval($data["tiempo_respuesta_ms"] ?? 0);

    ExecuteStatement("
        UPDATE fiscal_digital_transaccion
        SET numero_control = " . ($control !== "" ? "'{$control}'" : "NULL") . ",
            transaccion_id = " . ($transaccion !== "" ? "'{$transaccion}'" : "NULL") . ",
            codigo_http = {$http},
            codigo_respuesta = " . ($codigo !== "" ? "'{$codigo}'" : "NULL") . ",
            mensaje = " . ($mensaje !== "" ? "'{$mensaje}'" : "NULL") . ",
            validaciones_json = " . ($validaciones !== "" ? "'{$validaciones}'" : "NULL") . ",
            response_json = " . ($response !== "" ? "'{$response}'" : "NULL") . ",
            estatus = '{$estatus}',
            tiempo_respuesta_ms = {$ms},
            fecha_respuesta = NOW()
        WHERE id = {$id}
        LIMIT 1
    ");
}
