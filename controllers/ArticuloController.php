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

class ArticuloController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ArticuloList[/{id}]", [PermissionMiddleware::class], "list.articulo")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ArticuloAdd[/{id}]", [PermissionMiddleware::class], "add.articulo")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ArticuloView[/{id}]", [PermissionMiddleware::class], "view.articulo")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ArticuloEdit[/{id}]", [PermissionMiddleware::class], "edit.articulo")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ArticuloDelete[/{id}]", [PermissionMiddleware::class], "delete.articulo")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ArticuloDelete");
    }
}
