<?php

namespace PHPMaker2024\mandrake;

function TFHKA_AsegurarEstructura()
{
    ExecuteStatement("
        CREATE TABLE IF NOT EXISTS fiscal_digital_transaccion (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            tipo_documento VARCHAR(10) NOT NULL,
            id_documento BIGINT NOT NULL,
            proveedor_api VARCHAR(30) NOT NULL DEFAULT 'TFHKA',
            ambiente VARCHAR(15) NOT NULL,
            intento INT NOT NULL DEFAULT 1,
            numero_documento VARCHAR(40) NULL,
            numero_control VARCHAR(40) NULL,
            transaccion_id VARCHAR(120) NULL,
            codigo_http INT NULL,
            codigo_respuesta VARCHAR(20) NULL,
            mensaje TEXT NULL,
            validaciones_json LONGTEXT NULL,
            request_json LONGTEXT NULL,
            response_json LONGTEXT NULL,
            estatus VARCHAR(30) NOT NULL DEFAULT 'CREADO',
            tiempo_respuesta_ms INT NULL,
            fecha_envio DATETIME NULL,
            fecha_respuesta DATETIME NULL,
            username VARCHAR(100) NULL,
            PRIMARY KEY (id),
            KEY idx_fd_doc (tipo_documento, id_documento),
            KEY idx_fd_numero (numero_documento),
            KEY idx_fd_transaccion (transaccion_id),
            KEY idx_fd_estatus (estatus)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    ExecuteStatement("
        CREATE TABLE IF NOT EXISTS fiscal_digital_token (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            proveedor_api VARCHAR(30) NOT NULL DEFAULT 'TFHKA',
            ambiente VARCHAR(15) NOT NULL,
            token LONGTEXT NOT NULL,
            expira_en DATETIME NOT NULL,
            actualizado_en DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY ux_fd_token (proveedor_api, ambiente)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}
