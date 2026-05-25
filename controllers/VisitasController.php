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

class VisitasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/VisitasList[/{id}]", [PermissionMiddleware::class], "list.visitas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VisitasList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/VisitasAdd[/{id}]", [PermissionMiddleware::class], "add.visitas")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VisitasAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/VisitasView[/{id}]", [PermissionMiddleware::class], "view.visitas")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VisitasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/VisitasEdit[/{id}]", [PermissionMiddleware::class], "edit.visitas")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VisitasEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/VisitasDelete[/{id}]", [PermissionMiddleware::class], "delete.visitas")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "VisitasDelete");
    }
}
