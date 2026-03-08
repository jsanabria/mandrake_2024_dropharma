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

class ContAsientoDetalleMdkController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoDetalleMdkList[/{id}]", [PermissionMiddleware::class], "list.cont_asiento_detalle_mdk")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoDetalleMdkList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoDetalleMdkView[/{id}]", [PermissionMiddleware::class], "view.cont_asiento_detalle_mdk")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoDetalleMdkView");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ContAsientoDetalleMdkPreview", [PermissionMiddleware::class], "preview.cont_asiento_detalle_mdk")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoDetalleMdkPreview", null, false);
    }
}
