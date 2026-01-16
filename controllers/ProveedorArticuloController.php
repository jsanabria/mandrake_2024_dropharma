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

class ProveedorArticuloController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ProveedorArticuloList[/{id}]", [PermissionMiddleware::class], "list.proveedor_articulo")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ProveedorArticuloAdd[/{id}]", [PermissionMiddleware::class], "add.proveedor_articulo")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ProveedorArticuloView[/{id}]", [PermissionMiddleware::class], "view.proveedor_articulo")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ProveedorArticuloEdit[/{id}]", [PermissionMiddleware::class], "edit.proveedor_articulo")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ProveedorArticuloDelete[/{id}]", [PermissionMiddleware::class], "delete.proveedor_articulo")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ProveedorArticuloPreview", [PermissionMiddleware::class], "preview.proveedor_articulo")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorArticuloPreview", null, false);
    }
}
