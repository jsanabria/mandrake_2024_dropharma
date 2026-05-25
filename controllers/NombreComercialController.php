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

class NombreComercialController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NombreComercialList[/{codigo:.*}]", [PermissionMiddleware::class], "list.nombre_comercial")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NombreComercialList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NombreComercialAdd[/{codigo:.*}]", [PermissionMiddleware::class], "add.nombre_comercial")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NombreComercialAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NombreComercialView[/{codigo:.*}]", [PermissionMiddleware::class], "view.nombre_comercial")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NombreComercialView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NombreComercialEdit[/{codigo:.*}]", [PermissionMiddleware::class], "edit.nombre_comercial")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NombreComercialEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NombreComercialDelete[/{codigo:.*}]", [PermissionMiddleware::class], "delete.nombre_comercial")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NombreComercialDelete");
    }
}
