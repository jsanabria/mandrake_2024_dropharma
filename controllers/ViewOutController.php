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

class ViewOutController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewOutList[/{id}]", [PermissionMiddleware::class], "list.view_out")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewOutView[/{id}]", [PermissionMiddleware::class], "view.view_out")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutView");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ViewOutPreview", [PermissionMiddleware::class], "preview.view_out")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewOutPreview", null, false);
    }
}
