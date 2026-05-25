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

class PagosComprasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasList[/{id}]", [PermissionMiddleware::class], "list.pagos_compras")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasAdd[/{id}]", [PermissionMiddleware::class], "add.pagos_compras")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasView[/{id}]", [PermissionMiddleware::class], "view.pagos_compras")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasEdit[/{id}]", [PermissionMiddleware::class], "edit.pagos_compras")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDelete[/{id}]", [PermissionMiddleware::class], "delete.pagos_compras")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDelete");
    }
}
