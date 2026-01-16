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

class BannerController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/BannerList[/{id}]", [PermissionMiddleware::class], "list.banner")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BannerList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/BannerAdd[/{id}]", [PermissionMiddleware::class], "add.banner")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BannerAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/BannerView[/{id}]", [PermissionMiddleware::class], "view.banner")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BannerView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/BannerEdit[/{id}]", [PermissionMiddleware::class], "edit.banner")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BannerEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/BannerDelete[/{id}]", [PermissionMiddleware::class], "delete.banner")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "BannerDelete");
    }
}
