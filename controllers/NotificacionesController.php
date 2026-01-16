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

class NotificacionesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotificacionesList[/{Nnotificaciones}]", [PermissionMiddleware::class], "list.notificaciones")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotificacionesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotificacionesAdd[/{Nnotificaciones}]", [PermissionMiddleware::class], "add.notificaciones")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotificacionesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotificacionesView[/{Nnotificaciones}]", [PermissionMiddleware::class], "view.notificaciones")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotificacionesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotificacionesEdit[/{Nnotificaciones}]", [PermissionMiddleware::class], "edit.notificaciones")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotificacionesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotificacionesDelete[/{Nnotificaciones}]", [PermissionMiddleware::class], "delete.notificaciones")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotificacionesDelete");
    }
}
