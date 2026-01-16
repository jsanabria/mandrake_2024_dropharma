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

class TarifaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TarifaList[/{id}]", [PermissionMiddleware::class], "list.tarifa")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TarifaAdd[/{id}]", [PermissionMiddleware::class], "add.tarifa")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TarifaView[/{id}]", [PermissionMiddleware::class], "view.tarifa")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TarifaEdit[/{id}]", [PermissionMiddleware::class], "edit.tarifa")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TarifaDelete[/{id}]", [PermissionMiddleware::class], "delete.tarifa")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDelete");
    }
}
