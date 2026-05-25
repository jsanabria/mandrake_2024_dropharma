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

class PresupuestoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PresupuestoList[/{id}]", [PermissionMiddleware::class], "list.presupuesto")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupuestoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PresupuestoAdd[/{id}]", [PermissionMiddleware::class], "add.presupuesto")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupuestoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PresupuestoView[/{id}]", [PermissionMiddleware::class], "view.presupuesto")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupuestoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PresupuestoEdit[/{id}]", [PermissionMiddleware::class], "edit.presupuesto")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupuestoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PresupuestoDelete[/{id}]", [PermissionMiddleware::class], "delete.presupuesto")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PresupuestoDelete");
    }
}
