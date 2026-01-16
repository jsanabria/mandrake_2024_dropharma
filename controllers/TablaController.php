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

class TablaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TablaList[/{id}]", [PermissionMiddleware::class], "list.tabla")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TablaAdd[/{id}]", [PermissionMiddleware::class], "add.tabla")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TablaView[/{id}]", [PermissionMiddleware::class], "view.tabla")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TablaEdit[/{id}]", [PermissionMiddleware::class], "edit.tabla")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TablaEdit");
    }
}
