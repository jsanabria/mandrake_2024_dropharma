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

class CobrosClienteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteList[/{id}]", [PermissionMiddleware::class], "list.cobros_cliente")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteAdd[/{id}]", [PermissionMiddleware::class], "add.cobros_cliente")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteView[/{id}]", [PermissionMiddleware::class], "view.cobros_cliente")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteEdit[/{id}]", [PermissionMiddleware::class], "edit.cobros_cliente")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/CobrosClienteDelete[/{id}]", [PermissionMiddleware::class], "delete.cobros_cliente")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CobrosClienteDelete");
    }
}
