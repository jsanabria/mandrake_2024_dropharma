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

class PresupustoDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PresupustoDetalleList[/{id}]", [PermissionMiddleware::class], "list.presupusto_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupustoDetalleList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PresupustoDetalleAdd[/{id}]", [PermissionMiddleware::class], "add.presupusto_detalle")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupustoDetalleAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PresupustoDetalleView[/{id}]", [PermissionMiddleware::class], "view.presupusto_detalle")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupustoDetalleView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PresupustoDetalleEdit[/{id}]", [PermissionMiddleware::class], "edit.presupusto_detalle")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupustoDetalleEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PresupustoDetalleDelete[/{id}]", [PermissionMiddleware::class], "delete.presupusto_detalle")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupustoDetalleDelete");
    }
}
