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

class ViewCuentasPorCobrarController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewCuentasPorCobrarList[/{id}]", [PermissionMiddleware::class], "list.view_cuentas_por_cobrar")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewCuentasPorCobrarList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewCuentasPorCobrarView[/{id}]", [PermissionMiddleware::class], "view.view_cuentas_por_cobrar")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewCuentasPorCobrarView");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ViewCuentasPorCobrarPreview", [PermissionMiddleware::class], "preview.view_cuentas_por_cobrar")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewCuentasPorCobrarPreview", null, false);
    }
}
