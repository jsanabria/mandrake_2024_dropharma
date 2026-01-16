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

class PurgaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PurgaList[/{id}]", [PermissionMiddleware::class], "list.purga")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PurgaAdd[/{id}]", [PermissionMiddleware::class], "add.purga")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PurgaView[/{id}]", [PermissionMiddleware::class], "view.purga")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PurgaEdit[/{id}]", [PermissionMiddleware::class], "edit.purga")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PurgaDelete[/{id}]", [PermissionMiddleware::class], "delete.purga")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PurgaDelete");
    }
}
