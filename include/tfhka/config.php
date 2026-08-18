<?php

namespace PHPMaker2024\mandrake;

function TFHKA_AsegurarParametros()
{
    $parametros = [
        ["116", "USA FACTURACION DIGITAL TFHKA", "N", "S/N"],
        ["117", "AMBIENTE FACTURACION DIGITAL TFHKA", "DEMO", "DEMO/PRODUCCION"],
        ["118", "USUARIO API TFHKA", "", "Preferir variable de entorno TFHKA_USUARIO"],
        ["119", "CLAVE API TFHKA", "", "Preferir variable de entorno TFHKA_CLAVE"],
        ["120", "SERIE DOCUMENTOS TFHKA", "A", "Serie enviada a la imprenta digital"],
        ["121", "FORMA PAGO DEFAULT TFHKA", "99", "Catalogo 11 TFHKA; ajustar al flujo real"],
        ["122", "MONEDA BOLIVAR TFHKA", "VED", "Codigo indicado por documentacion TFHKA"],
        ["123", "UNIDAD MEDIDA DEFAULT TFHKA", "C62", "UNECE Rec20; confirmar mapeo interno"],
    ];

    foreach ($parametros as $p) {
        $codigo = str_replace("'", "''", $p[0]);
        $descripcion = str_replace("'", "''", $p[1]);
        $valor1 = str_replace("'", "''", $p[2]);
        $valor2 = str_replace("'", "''", $p[3]);

        ExecuteStatement("
            INSERT INTO parametro (codigo, descripcion, valor1, valor2)
            SELECT '{$codigo}', '{$descripcion}', '{$valor1}', '{$valor2}'
            FROM DUAL
            WHERE NOT EXISTS (
                SELECT 1 FROM parametro WHERE codigo = '{$codigo}'
            )
        ");
    }
}

function TFHKA_Parametro($codigo, $default = "")
{
    $codigo = str_replace("'", "''", (string)$codigo);
    $valor = ExecuteScalar("
        SELECT valor1
        FROM parametro
        WHERE codigo = '{$codigo}'
        LIMIT 1
    ");

    return ($valor === null) ? $default : trim((string)$valor);
}

function TFHKA_Config()
{
    TFHKA_AsegurarParametros();

    $ambiente = strtoupper(TFHKA_Parametro("117", "DEMO"));
    if (!in_array($ambiente, ["DEMO", "PRODUCCION"], true)) {
        $ambiente = "DEMO";
    }

    $usuarioEnv = getenv("TFHKA_USUARIO");
    $claveEnv = getenv("TFHKA_CLAVE");

    return [
        "ambiente" => $ambiente,
        "base_url" => ($ambiente === "PRODUCCION")
            ? "https://emisionv2.thefactoryhka.com.ve"
            : "https://demoemisionv2.thefactoryhka.com.ve",
        "usuario" => ($usuarioEnv !== false && trim($usuarioEnv) !== "")
            ? trim($usuarioEnv)
            : TFHKA_Parametro("118", ""),
        "clave" => ($claveEnv !== false && trim($claveEnv) !== "")
            ? trim($claveEnv)
            : TFHKA_Parametro("119", ""),
        "serie" => TFHKA_Parametro("120", "A"),
        "forma_pago_default" => TFHKA_Parametro("121", "99"),
        "moneda_bolivar" => strtoupper(TFHKA_Parametro("122", "VED")),
        "unidad_medida_default" => strtoupper(TFHKA_Parametro("123", "C62")),
        "timeout" => 45,
        "connect_timeout" => 15,
    ];
}
