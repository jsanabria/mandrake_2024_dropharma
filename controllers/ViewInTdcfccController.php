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

class ViewInTdcfccController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcfccList[/{id}]", [PermissionMiddleware::class], "list.view_in_tdcfcc")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcfccList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcfccView[/{id}]", [PermissionMiddleware::class], "view.view_in_tdcfcc")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcfccView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ViewInTdcfccEdit[/{id}]", [PermissionMiddleware::class], "edit.view_in_tdcfcc")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInTdcfccEdit");
    }
}
