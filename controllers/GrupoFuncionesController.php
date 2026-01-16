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

class GrupoFuncionesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/GrupoFuncionesList[/{id}]", [PermissionMiddleware::class], "list.grupo_funciones")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GrupoFuncionesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/GrupoFuncionesAdd[/{id}]", [PermissionMiddleware::class], "add.grupo_funciones")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GrupoFuncionesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/GrupoFuncionesView[/{id}]", [PermissionMiddleware::class], "view.grupo_funciones")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GrupoFuncionesView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/GrupoFuncionesDelete[/{id}]", [PermissionMiddleware::class], "delete.grupo_funciones")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GrupoFuncionesDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/GrupoFuncionesPreview", [PermissionMiddleware::class], "preview.grupo_funciones")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GrupoFuncionesPreview", null, false);
    }
}
