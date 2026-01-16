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

class ViewInController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewInList[/{id}]", [PermissionMiddleware::class], "list.view_in")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewInView[/{id}]", [PermissionMiddleware::class], "view.view_in")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInView");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ViewInPreview", [PermissionMiddleware::class], "preview.view_in")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewInPreview", null, false);
    }
}
