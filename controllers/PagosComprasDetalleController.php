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

class PagosComprasDetalleController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosComprasDetalleList[/{id}]", [PermissionMiddleware::class], "list.pagos_compras_detalle")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetalleList");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/PagosComprasDetallePreview", [PermissionMiddleware::class], "preview.pagos_compras_detalle")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosComprasDetallePreview", null, false);
    }
}
