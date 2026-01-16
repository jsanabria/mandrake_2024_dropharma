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

class CobrosClienteFacturaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteFacturaList[/{id}]", [PermissionMiddleware::class], "list.cobros_cliente_factura")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteFacturaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteFacturaView[/{id}]", [PermissionMiddleware::class], "view.cobros_cliente_factura")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteFacturaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteFacturaEdit[/{id}]", [PermissionMiddleware::class], "edit.cobros_cliente_factura")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteFacturaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteFacturaDelete[/{id}]", [PermissionMiddleware::class], "delete.cobros_cliente_factura")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteFacturaDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/CobrosClienteFacturaPreview", [PermissionMiddleware::class], "preview.cobros_cliente_factura")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteFacturaPreview", null, false);
    }
}
