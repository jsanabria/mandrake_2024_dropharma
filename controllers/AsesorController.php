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

class AsesorController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AsesorList[/{id}]", [PermissionMiddleware::class], "list.asesor")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AsesorAdd[/{id}]", [PermissionMiddleware::class], "add.asesor")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AsesorView[/{id}]", [PermissionMiddleware::class], "view.asesor")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AsesorEdit[/{id}]", [PermissionMiddleware::class], "edit.asesor")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AsesorDelete[/{id}]", [PermissionMiddleware::class], "delete.asesor")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorDelete");
    }
}
