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

class EntradasSalidasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/EntradasSalidasList[/{id}]", [PermissionMiddleware::class], "list.entradas_salidas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/EntradasSalidasAdd[/{id}]", [PermissionMiddleware::class], "add.entradas_salidas")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/EntradasSalidasView[/{id}]", [PermissionMiddleware::class], "view.entradas_salidas")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/EntradasSalidasEdit[/{id}]", [PermissionMiddleware::class], "edit.entradas_salidas")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/EntradasSalidasDelete[/{id}]", [PermissionMiddleware::class], "delete.entradas_salidas")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/EntradasSalidasPreview", [PermissionMiddleware::class], "preview.entradas_salidas")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EntradasSalidasPreview", null, false);
    }
}
