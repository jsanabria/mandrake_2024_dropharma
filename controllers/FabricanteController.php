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

class FabricanteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FabricanteList[/{Id}]", [PermissionMiddleware::class], "list.fabricante")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FabricanteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/FabricanteAdd[/{Id}]", [PermissionMiddleware::class], "add.fabricante")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FabricanteAdd");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/FabricanteEdit[/{Id}]", [PermissionMiddleware::class], "edit.fabricante")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FabricanteEdit");
    }
}
