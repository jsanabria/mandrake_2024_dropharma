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

class AlmacenController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AlmacenList[/{id}]", [PermissionMiddleware::class], "list.almacen")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AlmacenAdd[/{id}]", [PermissionMiddleware::class], "add.almacen")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AlmacenView[/{id}]", [PermissionMiddleware::class], "view.almacen")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlmacenView");
    }
}
