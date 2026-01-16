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

class ViewOutTdcpdvController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcpdvList[/{id}]", [PermissionMiddleware::class], "list.view_out_tdcpdv")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcpdvList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcpdvView[/{id}]", [PermissionMiddleware::class], "view.view_out_tdcpdv")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcpdvView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcpdvEdit[/{id}]", [PermissionMiddleware::class], "edit.view_out_tdcpdv")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcpdvEdit");
    }
}
