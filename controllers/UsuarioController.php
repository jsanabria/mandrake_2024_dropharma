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

class UsuarioController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/UsuarioList[/{id}]", [PermissionMiddleware::class], "list.usuario")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/UsuarioAdd[/{id}]", [PermissionMiddleware::class], "add.usuario")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/UsuarioView[/{id}]", [PermissionMiddleware::class], "view.usuario")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/UsuarioEdit[/{id}]", [PermissionMiddleware::class], "edit.usuario")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/UsuarioDelete[/{id}]", [PermissionMiddleware::class], "delete.usuario")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioDelete");
    }
}
