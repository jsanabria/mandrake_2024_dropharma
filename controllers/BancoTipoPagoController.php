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

class BancoTipoPagoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/BancoTipoPagoList[/{id}]", [PermissionMiddleware::class], "list.banco_tipo_pago")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BancoTipoPagoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/BancoTipoPagoAdd[/{id}]", [PermissionMiddleware::class], "add.banco_tipo_pago")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BancoTipoPagoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/BancoTipoPagoView[/{id}]", [PermissionMiddleware::class], "view.banco_tipo_pago")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BancoTipoPagoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/BancoTipoPagoEdit[/{id}]", [PermissionMiddleware::class], "edit.banco_tipo_pago")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BancoTipoPagoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/BancoTipoPagoDelete[/{id}]", [PermissionMiddleware::class], "delete.banco_tipo_pago")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BancoTipoPagoDelete");
    }
}
