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

class ViewArticulosController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewArticulosList[/{id}]", [PermissionMiddleware::class], "list.view_articulos")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewArticulosList");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewArticulosEdit[/{id}]", [PermissionMiddleware::class], "edit.view_articulos")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewArticulosEdit");
    }
}
