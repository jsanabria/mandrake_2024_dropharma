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

class ParametroController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ParametroList[/{id}]", [PermissionMiddleware::class], "list.parametro")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ParametroList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ParametroAdd[/{id}]", [PermissionMiddleware::class], "add.parametro")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ParametroAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ParametroView[/{id}]", [PermissionMiddleware::class], "view.parametro")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ParametroView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ParametroEdit[/{id}]", [PermissionMiddleware::class], "edit.parametro")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ParametroEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ParametroDelete[/{id}]", [PermissionMiddleware::class], "delete.parametro")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ParametroDelete");
    }
}
