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

class TarifaArticuloController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TarifaArticuloList[/{id}]", [PermissionMiddleware::class], "list.tarifa_articulo")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TarifaArticuloAdd[/{id}]", [PermissionMiddleware::class], "add.tarifa_articulo")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TarifaArticuloView[/{id}]", [PermissionMiddleware::class], "view.tarifa_articulo")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TarifaArticuloEdit[/{id}]", [PermissionMiddleware::class], "edit.tarifa_articulo")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TarifaArticuloDelete[/{id}]", [PermissionMiddleware::class], "delete.tarifa_articulo")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/TarifaArticuloPreview", [PermissionMiddleware::class], "preview.tarifa_articulo")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaArticuloPreview", null, false);
    }
}
