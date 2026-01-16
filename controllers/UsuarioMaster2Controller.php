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

class UsuarioMaster2Controller extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/UsuarioMaster2List[/{id}]", [PermissionMiddleware::class], "list.usuario_master2")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2List");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/UsuarioMaster2Add[/{id}]", [PermissionMiddleware::class], "add.usuario_master2")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2Add");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/UsuarioMaster2View[/{id}]", [PermissionMiddleware::class], "view.usuario_master2")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2View");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/UsuarioMaster2Edit[/{id}]", [PermissionMiddleware::class], "edit.usuario_master2")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2Edit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/UsuarioMaster2Delete[/{id}]", [PermissionMiddleware::class], "delete.usuario_master2")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2Delete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/UsuarioMaster2Preview", [PermissionMiddleware::class], "preview.usuario_master2")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "UsuarioMaster2Preview", null, false);
    }
}
