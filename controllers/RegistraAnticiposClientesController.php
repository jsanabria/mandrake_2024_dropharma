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
 * registra_anticipos_clientes controller
 */
class RegistraAnticiposClientesController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/RegistraAnticiposClientes[/{params:.*}]", [PermissionMiddleware::class], "custom.registra_anticipos_clientes")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RegistraAnticiposClientes");
    }
}
