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

class AsesorFabricanteController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AsesorFabricanteList[/{id}]", [PermissionMiddleware::class], "list.asesor_fabricante")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricanteList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AsesorFabricanteAdd[/{id}]", [PermissionMiddleware::class], "add.asesor_fabricante")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricanteAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AsesorFabricanteView[/{id}]", [PermissionMiddleware::class], "view.asesor_fabricante")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricanteView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AsesorFabricanteEdit[/{id}]", [PermissionMiddleware::class], "edit.asesor_fabricante")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricanteEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AsesorFabricanteDelete[/{id}]", [PermissionMiddleware::class], "delete.asesor_fabricante")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricanteDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/AsesorFabricantePreview", [PermissionMiddleware::class], "preview.asesor_fabricante")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AsesorFabricantePreview", null, false);
    }
}
