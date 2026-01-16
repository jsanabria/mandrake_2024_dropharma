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

class ClienteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ClienteList[/{id}]", [PermissionMiddleware::class], "list.cliente")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ClienteAdd[/{id}]", [PermissionMiddleware::class], "add.cliente")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteAdd");
    }

    // addopt
    #[Map(["GET","POST","OPTIONS"], "/ClienteAddopt", [PermissionMiddleware::class], "addopt.cliente")]
    public function addopt(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteAddopt", null, false);
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ClienteView[/{id}]", [PermissionMiddleware::class], "view.cliente")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ClienteEdit[/{id}]", [PermissionMiddleware::class], "edit.cliente")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ClienteDelete[/{id}]", [PermissionMiddleware::class], "delete.cliente")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ClienteDelete");
    }
}
