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

class PagosComprasDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleList[/{id}]", [PermissionMiddleware::class], "list.pagos_compras_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleAdd[/{id}]", [PermissionMiddleware::class], "add.pagos_compras_detalle")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleView[/{id}]", [PermissionMiddleware::class], "view.pagos_compras_detalle")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleEdit[/{id}]", [PermissionMiddleware::class], "edit.pagos_compras_detalle")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleDelete[/{id}]", [PermissionMiddleware::class], "delete.pagos_compras_detalle")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleDelete");
    }
}
