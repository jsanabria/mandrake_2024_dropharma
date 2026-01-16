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

class ViewOutTdcfcvController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcfcvList[/{id}]", [PermissionMiddleware::class], "list.view_out_tdcfcv")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcfcvList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcfcvView[/{id}]", [PermissionMiddleware::class], "view.view_out_tdcfcv")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcfcvView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewOutTdcfcvEdit[/{id}]", [PermissionMiddleware::class], "edit.view_out_tdcfcv")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutTdcfcvEdit");
    }
}
