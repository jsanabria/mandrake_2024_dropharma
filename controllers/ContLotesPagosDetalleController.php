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

class ContLotesPagosDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosDetalleList[/{Id}]", [PermissionMiddleware::class], "list.cont_lotes_pagos_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosDetalleList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosDetalleView[/{Id}]", [PermissionMiddleware::class], "view.cont_lotes_pagos_detalle")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosDetalleView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosDetalleDelete[/{Id}]", [PermissionMiddleware::class], "delete.cont_lotes_pagos_detalle")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosDetalleDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ContLotesPagosDetallePreview", [PermissionMiddleware::class], "preview.cont_lotes_pagos_detalle")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosDetallePreview", null, false);
    }
}
