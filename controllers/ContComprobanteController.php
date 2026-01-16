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

class ContComprobanteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContComprobanteList[/{id}]", [PermissionMiddleware::class], "list.cont_comprobante")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContComprobanteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContComprobanteAdd[/{id}]", [PermissionMiddleware::class], "add.cont_comprobante")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContComprobanteAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContComprobanteView[/{id}]", [PermissionMiddleware::class], "view.cont_comprobante")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContComprobanteView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContComprobanteEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_comprobante")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContComprobanteEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContComprobanteDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_comprobante")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContComprobanteDelete");
    }
}
