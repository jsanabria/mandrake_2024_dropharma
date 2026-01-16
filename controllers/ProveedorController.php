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

class ProveedorController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ProveedorList[/{id}]", [PermissionMiddleware::class], "list.proveedor")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ProveedorAdd[/{id}]", [PermissionMiddleware::class], "add.proveedor")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ProveedorView[/{id}]", [PermissionMiddleware::class], "view.proveedor")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ProveedorEdit[/{id}]", [PermissionMiddleware::class], "edit.proveedor")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ProveedorDelete[/{id}]", [PermissionMiddleware::class], "delete.proveedor")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProveedorDelete");
    }
}
