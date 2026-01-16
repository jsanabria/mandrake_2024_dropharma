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

class TasaUsdController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TasaUsdList[/{id}]", [PermissionMiddleware::class], "list.tasa_usd")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TasaUsdList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TasaUsdAdd[/{id}]", [PermissionMiddleware::class], "add.tasa_usd")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TasaUsdAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TasaUsdView[/{id}]", [PermissionMiddleware::class], "view.tasa_usd")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TasaUsdView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TasaUsdEdit[/{id}]", [PermissionMiddleware::class], "edit.tasa_usd")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TasaUsdEdit");
    }
}
