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

class EntradasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/EntradasList[/{id}]", [PermissionMiddleware::class], "list.entradas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/EntradasAdd[/{id}]", [PermissionMiddleware::class], "add.entradas")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/EntradasView[/{id}]", [PermissionMiddleware::class], "view.entradas")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/EntradasEdit[/{id}]", [PermissionMiddleware::class], "edit.entradas")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/EntradasDelete[/{id}]", [PermissionMiddleware::class], "delete.entradas")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasDelete");
    }
}
