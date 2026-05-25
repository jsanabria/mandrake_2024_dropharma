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

class CierreDeCajaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CierreDeCajaList[/{id}]", [PermissionMiddleware::class], "list.cierre_de_caja")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CierreDeCajaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/CierreDeCajaAdd[/{id}]", [PermissionMiddleware::class], "add.cierre_de_caja")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CierreDeCajaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CierreDeCajaView[/{id}]", [PermissionMiddleware::class], "view.cierre_de_caja")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CierreDeCajaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CierreDeCajaEdit[/{id}]", [PermissionMiddleware::class], "edit.cierre_de_caja")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CierreDeCajaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/CierreDeCajaDelete[/{id}]", [PermissionMiddleware::class], "delete.cierre_de_caja")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CierreDeCajaDelete");
    }
}
