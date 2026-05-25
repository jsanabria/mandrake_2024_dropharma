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

class ConteoEncabezadoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ConteoEncabezadoList[/{id}]", [PermissionMiddleware::class], "list.conteo_encabezado")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoEncabezadoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ConteoEncabezadoAdd[/{id}]", [PermissionMiddleware::class], "add.conteo_encabezado")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoEncabezadoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ConteoEncabezadoView[/{id}]", [PermissionMiddleware::class], "view.conteo_encabezado")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoEncabezadoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ConteoEncabezadoEdit[/{id}]", [PermissionMiddleware::class], "edit.conteo_encabezado")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoEncabezadoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ConteoEncabezadoDelete[/{id}]", [PermissionMiddleware::class], "delete.conteo_encabezado")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConteoEncabezadoDelete");
    }
}
