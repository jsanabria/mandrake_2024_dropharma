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

class TablaRetencionesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TablaRetencionesList[/{id}]", [PermissionMiddleware::class], "list.tabla_retenciones")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaRetencionesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TablaRetencionesAdd[/{id}]", [PermissionMiddleware::class], "add.tabla_retenciones")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaRetencionesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TablaRetencionesView[/{id}]", [PermissionMiddleware::class], "view.tabla_retenciones")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaRetencionesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TablaRetencionesEdit[/{id}]", [PermissionMiddleware::class], "edit.tabla_retenciones")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaRetencionesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TablaRetencionesDelete[/{id}]", [PermissionMiddleware::class], "delete.tabla_retenciones")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaRetencionesDelete");
    }
}
