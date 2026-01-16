<?php

namespace PHPMaker2024\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\mandrake\Attributes\Delete;
use PHPMaker2024\mandrake\Attributes\Get;
use PHPMaker2024\mandrake\Attributes\Map;
use PHPMaker2024\mandrake\Attributes\Options;
use PHPMaker2024\mandrake\Attributes\Patch;
use PHPMaker2024\mandrake\Attributes\Post;
use PHPMaker2024\mandrake\Attributes\Put;

/**
 * factura_consignacion_guardar controller
 */
class FacturaConsignacionGuardarController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/FacturaConsignacionGuardar[/{params:.*}]", [PermissionMiddleware::class], "custom.factura_consignacion_guardar")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FacturaConsignacionGuardar");
    }
}
