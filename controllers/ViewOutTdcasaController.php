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

class ViewOutTdcasaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcasaList[/{id}]", [PermissionMiddleware::class], "list.view_out_tdcasa")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcasaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcasaView[/{id}]", [PermissionMiddleware::class], "view.view_out_tdcasa")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcasaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcasaEdit[/{id}]", [PermissionMiddleware::class], "edit.view_out_tdcasa")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcasaEdit");
    }
}
