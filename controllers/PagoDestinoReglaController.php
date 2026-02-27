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

class PagoDestinoReglaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/PagoDestinoReglaList[/{id}]", [PermissionMiddleware::class], "list.pago_destino_regla")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagoDestinoReglaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/PagoDestinoReglaAdd[/{id}]", [PermissionMiddleware::class], "add.pago_destino_regla")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagoDestinoReglaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/PagoDestinoReglaView[/{id}]", [PermissionMiddleware::class], "view.pago_destino_regla")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagoDestinoReglaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/PagoDestinoReglaEdit[/{id}]", [PermissionMiddleware::class], "edit.pago_destino_regla")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagoDestinoReglaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/PagoDestinoReglaDelete[/{id}]", [PermissionMiddleware::class], "delete.pago_destino_regla")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "PagoDestinoReglaDelete");
    }
}
