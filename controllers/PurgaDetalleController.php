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

class PurgaDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PurgaDetalleList[/{id}]", [PermissionMiddleware::class], "list.purga_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetalleList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PurgaDetalleAdd[/{id}]", [PermissionMiddleware::class], "add.purga_detalle")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetalleAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PurgaDetalleView[/{id}]", [PermissionMiddleware::class], "view.purga_detalle")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetalleView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PurgaDetalleEdit[/{id}]", [PermissionMiddleware::class], "edit.purga_detalle")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetalleEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PurgaDetalleDelete[/{id}]", [PermissionMiddleware::class], "delete.purga_detalle")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetalleDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/PurgaDetallePreview", [PermissionMiddleware::class], "preview.purga_detalle")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDetallePreview", null, false);
    }
}
