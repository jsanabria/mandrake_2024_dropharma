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

class ContPlanctaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContPlanctaList[/{id}]", [PermissionMiddleware::class], "list.cont_plancta")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanctaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContPlanctaAdd[/{id}]", [PermissionMiddleware::class], "add.cont_plancta")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanctaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContPlanctaView[/{id}]", [PermissionMiddleware::class], "view.cont_plancta")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanctaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContPlanctaEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_plancta")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanctaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContPlanctaDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_plancta")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanctaDelete");
    }
}
