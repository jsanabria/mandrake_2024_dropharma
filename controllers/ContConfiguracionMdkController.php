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

class ContConfiguracionMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContConfiguracionMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_configuracion_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContConfiguracionMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContConfiguracionMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_configuracion_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContConfiguracionMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContConfiguracionMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_configuracion_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContConfiguracionMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContConfiguracionMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_configuracion_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContConfiguracionMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContConfiguracionMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_configuracion_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContConfiguracionMdkDelete");
    }
}
