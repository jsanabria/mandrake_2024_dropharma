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

class ContCentroCostoMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContCentroCostoMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_centro_costo_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContCentroCostoMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContCentroCostoMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_centro_costo_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContCentroCostoMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContCentroCostoMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_centro_costo_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContCentroCostoMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContCentroCostoMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_centro_costo_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContCentroCostoMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContCentroCostoMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_centro_costo_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContCentroCostoMdkDelete");
    }
}
