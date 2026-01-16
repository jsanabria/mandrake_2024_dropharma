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

class AlmacenistaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AlmacenistaList[/{id}]", [PermissionMiddleware::class], "list.almacenista")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenistaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AlmacenistaAdd[/{id}]", [PermissionMiddleware::class], "add.almacenista")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenistaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AlmacenistaView[/{id}]", [PermissionMiddleware::class], "view.almacenista")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenistaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AlmacenistaEdit[/{id}]", [PermissionMiddleware::class], "edit.almacenista")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenistaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AlmacenistaDelete[/{id}]", [PermissionMiddleware::class], "delete.almacenista")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenistaDelete");
    }
}
