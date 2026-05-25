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

class ReporteSeniatNeController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ReporteSeniatNeList[/{id}]", [PermissionMiddleware::class], "list.reporte_seniat_ne")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReporteSeniatNeList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ReporteSeniatNeAdd[/{id}]", [PermissionMiddleware::class], "add.reporte_seniat_ne")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReporteSeniatNeAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ReporteSeniatNeView[/{id}]", [PermissionMiddleware::class], "view.reporte_seniat_ne")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReporteSeniatNeView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ReporteSeniatNeEdit[/{id}]", [PermissionMiddleware::class], "edit.reporte_seniat_ne")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReporteSeniatNeEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ReporteSeniatNeDelete[/{id}]", [PermissionMiddleware::class], "delete.reporte_seniat_ne")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ReporteSeniatNeDelete");
    }
}
