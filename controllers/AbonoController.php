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

class AbonoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AbonoList[/{id}]", [PermissionMiddleware::class], "list.abono")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AbonoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AbonoAdd[/{id}]", [PermissionMiddleware::class], "add.abono")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AbonoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AbonoView[/{id}]", [PermissionMiddleware::class], "view.abono")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AbonoView");
    }
}
