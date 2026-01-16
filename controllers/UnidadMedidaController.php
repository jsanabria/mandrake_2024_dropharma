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

class UnidadMedidaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/UnidadMedidaList[/{id}]", [PermissionMiddleware::class], "list.unidad_medida")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UnidadMedidaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/UnidadMedidaAdd[/{id}]", [PermissionMiddleware::class], "add.unidad_medida")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UnidadMedidaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/UnidadMedidaView[/{id}]", [PermissionMiddleware::class], "view.unidad_medida")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UnidadMedidaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/UnidadMedidaEdit[/{id}]", [PermissionMiddleware::class], "edit.unidad_medida")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UnidadMedidaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/UnidadMedidaDelete[/{id}]", [PermissionMiddleware::class], "delete.unidad_medida")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UnidadMedidaDelete");
    }
}
