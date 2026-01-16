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

class ContPeriodoContableController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContPeriodoContableList[/{id}]", [PermissionMiddleware::class], "list.cont_periodo_contable")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPeriodoContableList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContPeriodoContableAdd[/{id}]", [PermissionMiddleware::class], "add.cont_periodo_contable")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPeriodoContableAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContPeriodoContableView[/{id}]", [PermissionMiddleware::class], "view.cont_periodo_contable")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPeriodoContableView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContPeriodoContableEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_periodo_contable")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPeriodoContableEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContPeriodoContableDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_periodo_contable")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPeriodoContableDelete");
    }
}
