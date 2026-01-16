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

class TempConsignacionController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TempConsignacionList[/{id}]", [PermissionMiddleware::class], "list.temp_consignacion")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TempConsignacionList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TempConsignacionAdd[/{id}]", [PermissionMiddleware::class], "add.temp_consignacion")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TempConsignacionAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TempConsignacionView[/{id}]", [PermissionMiddleware::class], "view.temp_consignacion")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TempConsignacionView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TempConsignacionEdit[/{id}]", [PermissionMiddleware::class], "edit.temp_consignacion")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TempConsignacionEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TempConsignacionDelete[/{id}]", [PermissionMiddleware::class], "delete.temp_consignacion")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TempConsignacionDelete");
    }
}
