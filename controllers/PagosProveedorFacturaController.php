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

class PagosProveedorFacturaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorFacturaList[/{id}]", [PermissionMiddleware::class], "list.pagos_proveedor_factura")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorFacturaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorFacturaView[/{id}]", [PermissionMiddleware::class], "view.pagos_proveedor_factura")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorFacturaView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagosProveedorFacturaDelete[/{id}]", [PermissionMiddleware::class], "delete.pagos_proveedor_factura")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorFacturaDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/PagosProveedorFacturaPreview", [PermissionMiddleware::class], "preview.pagos_proveedor_factura")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosProveedorFacturaPreview", null, false);
    }
}
