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

class CompaniaCuentaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CompaniaCuentaList[/{id}]", [PermissionMiddleware::class], "list.compania_cuenta")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/CompaniaCuentaAdd[/{id}]", [PermissionMiddleware::class], "add.compania_cuenta")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CompaniaCuentaView[/{id}]", [PermissionMiddleware::class], "view.compania_cuenta")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CompaniaCuentaEdit[/{id}]", [PermissionMiddleware::class], "edit.compania_cuenta")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/CompaniaCuentaDelete[/{id}]", [PermissionMiddleware::class], "delete.compania_cuenta")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/CompaniaCuentaPreview", [PermissionMiddleware::class], "preview.compania_cuenta")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaCuentaPreview", null, false);
    }
}
