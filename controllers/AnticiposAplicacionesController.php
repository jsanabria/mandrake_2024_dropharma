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

class AnticiposAplicacionesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AnticiposAplicacionesList[/{id}]", [PermissionMiddleware::class], "list.anticipos_aplicaciones")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AnticiposAplicacionesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AnticiposAplicacionesAdd[/{id}]", [PermissionMiddleware::class], "add.anticipos_aplicaciones")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AnticiposAplicacionesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AnticiposAplicacionesView[/{id}]", [PermissionMiddleware::class], "view.anticipos_aplicaciones")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AnticiposAplicacionesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AnticiposAplicacionesEdit[/{id}]", [PermissionMiddleware::class], "edit.anticipos_aplicaciones")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AnticiposAplicacionesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AnticiposAplicacionesDelete[/{id}]", [PermissionMiddleware::class], "delete.anticipos_aplicaciones")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AnticiposAplicacionesDelete");
    }
}
