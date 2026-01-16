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

class ViewInTdcpdcController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcpdcList[/{id}]", [PermissionMiddleware::class], "list.view_in_tdcpdc")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcpdcList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcpdcView[/{id}]", [PermissionMiddleware::class], "view.view_in_tdcpdc")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcpdcView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcpdcEdit[/{id}]", [PermissionMiddleware::class], "edit.view_in_tdcpdc")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcpdcEdit");
    }
}
