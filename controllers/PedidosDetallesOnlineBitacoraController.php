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

class PedidosDetallesOnlineBitacoraController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PedidosDetallesOnlineBitacoraList[/{id}]", [PermissionMiddleware::class], "list.pedidos_detalles_online_bitacora")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidosDetallesOnlineBitacoraList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PedidosDetallesOnlineBitacoraAdd[/{id}]", [PermissionMiddleware::class], "add.pedidos_detalles_online_bitacora")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidosDetallesOnlineBitacoraAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PedidosDetallesOnlineBitacoraView[/{id}]", [PermissionMiddleware::class], "view.pedidos_detalles_online_bitacora")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidosDetallesOnlineBitacoraView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PedidosDetallesOnlineBitacoraEdit[/{id}]", [PermissionMiddleware::class], "edit.pedidos_detalles_online_bitacora")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidosDetallesOnlineBitacoraEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PedidosDetallesOnlineBitacoraDelete[/{id}]", [PermissionMiddleware::class], "delete.pedidos_detalles_online_bitacora")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidosDetallesOnlineBitacoraDelete");
    }
}
