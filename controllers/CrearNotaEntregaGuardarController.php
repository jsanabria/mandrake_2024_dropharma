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
 * crear_nota_entrega_guardar controller
 */
class CrearNotaEntregaGuardarController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/CrearNotaEntregaGuardar[/{params:.*}]", [PermissionMiddleware::class], "custom.crear_nota_entrega_guardar")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CrearNotaEntregaGuardar");
    }
}
