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

class ContMetodoCuentaMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContMetodoCuentaMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_metodo_cuenta_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMetodoCuentaMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContMetodoCuentaMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_metodo_cuenta_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMetodoCuentaMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContMetodoCuentaMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_metodo_cuenta_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMetodoCuentaMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContMetodoCuentaMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_metodo_cuenta_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMetodoCuentaMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContMetodoCuentaMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_metodo_cuenta_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMetodoCuentaMdkDelete");
    }
}
