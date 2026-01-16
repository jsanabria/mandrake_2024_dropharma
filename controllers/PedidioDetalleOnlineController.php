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

class PedidioDetalleOnlineController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PedidioDetalleOnlineList[/{id}]", [PermissionMiddleware::class], "list.pedidio_detalle_online")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidioDetalleOnlineList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PedidioDetalleOnlineView[/{id}]", [PermissionMiddleware::class], "view.pedidio_detalle_online")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidioDetalleOnlineView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PedidioDetalleOnlineDelete[/{id}]", [PermissionMiddleware::class], "delete.pedidio_detalle_online")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidioDetalleOnlineDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/PedidioDetalleOnlinePreview", [PermissionMiddleware::class], "preview.pedidio_detalle_online")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PedidioDetalleOnlinePreview", null, false);
    }
}
