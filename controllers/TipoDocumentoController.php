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

class TipoDocumentoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TipoDocumentoList[/{id}]", [PermissionMiddleware::class], "list.tipo_documento")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TipoDocumentoList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TipoDocumentoView[/{id}]", [PermissionMiddleware::class], "view.tipo_documento")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TipoDocumentoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TipoDocumentoEdit[/{id}]", [PermissionMiddleware::class], "edit.tipo_documento")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TipoDocumentoEdit");
    }
}
