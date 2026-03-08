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

class ContBancoCuentaMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContBancoCuentaMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_banco_cuenta_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContBancoCuentaMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContBancoCuentaMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_banco_cuenta_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContBancoCuentaMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContBancoCuentaMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_banco_cuenta_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContBancoCuentaMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContBancoCuentaMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_banco_cuenta_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContBancoCuentaMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContBancoCuentaMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_banco_cuenta_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContBancoCuentaMdkDelete");
    }
}
