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

class AsesorClienteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AsesorClienteList[/{id}]", [PermissionMiddleware::class], "list.asesor_cliente")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClienteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AsesorClienteAdd[/{id}]", [PermissionMiddleware::class], "add.asesor_cliente")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClienteAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AsesorClienteView[/{id}]", [PermissionMiddleware::class], "view.asesor_cliente")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClienteView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AsesorClienteEdit[/{id}]", [PermissionMiddleware::class], "edit.asesor_cliente")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClienteEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AsesorClienteDelete[/{id}]", [PermissionMiddleware::class], "delete.asesor_cliente")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClienteDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/AsesorClientePreview", [PermissionMiddleware::class], "preview.asesor_cliente")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorClientePreview", null, false);
    }
}
