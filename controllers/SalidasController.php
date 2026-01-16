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

class SalidasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/SalidasList[/{id}]", [PermissionMiddleware::class], "list.salidas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalidasList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/SalidasAdd[/{id}]", [PermissionMiddleware::class], "add.salidas")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalidasAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/SalidasView[/{id}]", [PermissionMiddleware::class], "view.salidas")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalidasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/SalidasEdit[/{id}]", [PermissionMiddleware::class], "edit.salidas")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalidasEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/SalidasDelete[/{id}]", [PermissionMiddleware::class], "delete.salidas")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalidasDelete");
    }
}
