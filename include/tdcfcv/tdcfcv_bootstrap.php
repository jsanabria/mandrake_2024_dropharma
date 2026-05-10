<?php

namespace PHPMaker2024\mandrake;

ob_start();

$rootPath = dirname(__DIR__, 2);

require_once $rootPath . "/vendor/autoload.php";

$configFile = is_file($rootPath . "/src/config.development.php")
    ? $rootPath . "/src/config.development.php"
    : $rootPath . "/src/config.production.php";

$configData = require $configFile;

$configData = array_replace_recursive([
    "ENVIRONMENT" => "development",
    "DEBUG" => false,
    "MYSQL_CHARSET" => "utf8mb4",
    "DB_TIME_ZONE" => "",
    "ENCRYPT_USER_NAME_AND_PASSWORD" => false,
    "POSTGRESQL_CHARSET" => "",
    "ORACLE_CHARSET" => ""
], $configData);

require_once $rootPath . "/src/constants.php";
require_once $rootPath . "/src/phpfn.php";

$GLOBALS["ConfigData"] = new \Dflydev\DotAccessData\Data($configData);
$ConfigData = &$GLOBALS["ConfigData"];

$GLOBALS["EventDispatcher"] = new \Symfony\Component\EventDispatcher\EventDispatcher();
$EventDispatcher = &$GLOBALS["EventDispatcher"];

require_once $rootPath . "/src/userfn.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * Respuesta JSON estándar para endpoints TDCFCV.
 */
function TdcfcvJsonResponse(array $data): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function TdcfcvRequest(string $key, mixed $default = null): mixed
{
    return $_REQUEST[$key] ?? $default;
}

function TdcfcvRequestInt(string $key, int $default = 0): int
{
    return isset($_REQUEST[$key]) ? intval($_REQUEST[$key]) : $default;
}

function TdcfcvRequestFloat(string $key, float $default = 0): float
{
    return isset($_REQUEST[$key]) ? floatval($_REQUEST[$key]) : $default;
}

function TdcfcvRequestText(string $key, string $default = ""): string
{
    return isset($_REQUEST[$key]) ? trim((string)$_REQUEST[$key]) : $default;
}

function TdcfcvCurrentUser(): string
{
    if (function_exists(__NAMESPACE__ . "\\CurrentUserName")) {
        $user = CurrentUserName();
        if ($user !== null && $user !== "") {
            return $user;
        }
    }

    if (!empty($_SESSION["mdk_CurrentUserName"])) {
        return (string)$_SESSION["mdk_CurrentUserName"];
    }

    return isset($_REQUEST["username"]) ? trim((string)$_REQUEST["username"]) : "";
}

function TdcfcvJsonError(string $mensaje, array $extra = []): void
{
    TdcfcvJsonResponse(array_merge([
        "estatus" => "0",
        "mensaje" => $mensaje
    ], $extra));
}

function TdcfcvJsonOk(array $data = []): void
{
    TdcfcvJsonResponse(array_merge([
        "estatus" => "1",
        "mensaje" => "OK"
    ], $data));
}