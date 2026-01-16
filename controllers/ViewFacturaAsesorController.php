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

class ViewFacturaAsesorController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewFacturaAsesorList[/{id}]", [PermissionMiddleware::class], "list.view_factura_asesor")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewFacturaAsesorList");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewFacturaAsesorEdit[/{id}]", [PermissionMiddleware::class], "edit.view_factura_asesor")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewFacturaAsesorEdit");
    }
}
