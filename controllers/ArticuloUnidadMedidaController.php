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

class ArticuloUnidadMedidaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ArticuloUnidadMedidaList[/{id}]", [PermissionMiddleware::class], "list.articulo_unidad_medida")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ArticuloUnidadMedidaAdd[/{id}]", [PermissionMiddleware::class], "add.articulo_unidad_medida")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ArticuloUnidadMedidaView[/{id}]", [PermissionMiddleware::class], "view.articulo_unidad_medida")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ArticuloUnidadMedidaEdit[/{id}]", [PermissionMiddleware::class], "edit.articulo_unidad_medida")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ArticuloUnidadMedidaDelete[/{id}]", [PermissionMiddleware::class], "delete.articulo_unidad_medida")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ArticuloUnidadMedidaPreview", [PermissionMiddleware::class], "preview.articulo_unidad_medida")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloUnidadMedidaPreview", null, false);
    }
}
