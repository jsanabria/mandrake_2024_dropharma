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

class CompraController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CompraList[/{id}]", [PermissionMiddleware::class], "list.compra")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompraList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/CompraAdd[/{id}]", [PermissionMiddleware::class], "add.compra")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompraAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CompraView[/{id}]", [PermissionMiddleware::class], "view.compra")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompraView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CompraEdit[/{id}]", [PermissionMiddleware::class], "edit.compra")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompraEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/CompraDelete[/{id}]", [PermissionMiddleware::class], "delete.compra")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompraDelete");
    }
}
