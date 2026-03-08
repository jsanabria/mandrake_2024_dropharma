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

class ContTerceroMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContTerceroMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_tercero_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContTerceroMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContTerceroMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_tercero_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContTerceroMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContTerceroMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_tercero_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContTerceroMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContTerceroMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_tercero_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContTerceroMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContTerceroMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_tercero_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContTerceroMdkDelete");
    }
}
