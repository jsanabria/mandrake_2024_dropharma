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

class ConteoDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ConteoDetalleList[/{id}]", [PermissionMiddleware::class], "list.conteo_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoDetalleList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ConteoDetalleAdd[/{id}]", [PermissionMiddleware::class], "add.conteo_detalle")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoDetalleAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ConteoDetalleView[/{id}]", [PermissionMiddleware::class], "view.conteo_detalle")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoDetalleView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ConteoDetalleEdit[/{id}]", [PermissionMiddleware::class], "edit.conteo_detalle")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoDetalleEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ConteoDetalleDelete[/{id}]", [PermissionMiddleware::class], "delete.conteo_detalle")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoDetalleDelete");
    }
}
