<?php

namespace PHPMaker2024\mandrake;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\mandrake\Attributes\Delete;
use PHPMaker2024\mandrake\Attributes\Get;
use PHPMaker2024\mandrake\Attributes\Map;
use PHPMaker2024\mandrake\Attributes\Options;
use PHPMaker2024\mandrake\Attributes\Patch;
use PHPMaker2024\mandrake\Attributes\Post;
use PHPMaker2024\mandrake\Attributes\Put;

class ViewFacturasCobranzaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewFacturasCobranzaList[/{id}]", [PermissionMiddleware::class], "list.view_facturas_cobranza")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewFacturasCobranzaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewFacturasCobranzaView[/{id}]", [PermissionMiddleware::class], "view.view_facturas_cobranza")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewFacturasCobranzaView");
    }
}
