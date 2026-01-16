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

class PagosController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagosList[/{id}]", [PermissionMiddleware::class], "list.pagos")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PagosAdd[/{id}]", [PermissionMiddleware::class], "add.pagos")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagosView[/{id}]", [PermissionMiddleware::class], "view.pagos")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagosDelete[/{id}]", [PermissionMiddleware::class], "delete.pagos")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/PagosPreview", [PermissionMiddleware::class], "preview.pagos")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagosPreview", null, false);
    }
}
