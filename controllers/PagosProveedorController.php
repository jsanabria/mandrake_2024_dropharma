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

class PagosProveedorController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorList[/{id}]", [PermissionMiddleware::class], "list.pagos_proveedor")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorAdd[/{id}]", [PermissionMiddleware::class], "add.pagos_proveedor")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorView[/{id}]", [PermissionMiddleware::class], "view.pagos_proveedor")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorEdit[/{id}]", [PermissionMiddleware::class], "edit.pagos_proveedor")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorDelete[/{id}]", [PermissionMiddleware::class], "delete.pagos_proveedor")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorDelete");
    }
}
