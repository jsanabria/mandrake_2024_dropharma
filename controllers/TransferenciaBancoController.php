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

class TransferenciaBancoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TransferenciaBancoList[/{id}]", [PermissionMiddleware::class], "list.transferencia_banco")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransferenciaBancoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TransferenciaBancoAdd[/{id}]", [PermissionMiddleware::class], "add.transferencia_banco")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransferenciaBancoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TransferenciaBancoView[/{id}]", [PermissionMiddleware::class], "view.transferencia_banco")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransferenciaBancoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TransferenciaBancoEdit[/{id}]", [PermissionMiddleware::class], "edit.transferencia_banco")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransferenciaBancoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TransferenciaBancoDelete[/{id}]", [PermissionMiddleware::class], "delete.transferencia_banco")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TransferenciaBancoDelete");
    }
}
