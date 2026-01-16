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

class PedidoOnlineController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PedidoOnlineList[/{id}]", [PermissionMiddleware::class], "list.pedido_online")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidoOnlineList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PedidoOnlineView[/{id}]", [PermissionMiddleware::class], "view.pedido_online")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidoOnlineView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PedidoOnlineDelete[/{id}]", [PermissionMiddleware::class], "delete.pedido_online")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidoOnlineDelete");
    }
}
