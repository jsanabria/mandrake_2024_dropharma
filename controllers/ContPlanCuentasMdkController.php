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

class ContPlanCuentasMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContPlanCuentasMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_plan_cuentas_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanCuentasMdkList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContPlanCuentasMdkAdd[/{id}]", [PermissionMiddleware::class], "add.cont_plan_cuentas_mdk")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanCuentasMdkAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContPlanCuentasMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_plan_cuentas_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanCuentasMdkView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContPlanCuentasMdkEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_plan_cuentas_mdk")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanCuentasMdkEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContPlanCuentasMdkDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_plan_cuentas_mdk")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContPlanCuentasMdkDelete");
    }
}
