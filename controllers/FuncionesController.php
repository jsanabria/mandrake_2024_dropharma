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

class FuncionesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FuncionesList[/{id}]", [PermissionMiddleware::class], "list.funciones")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FuncionesList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/FuncionesView[/{id}]", [PermissionMiddleware::class], "view.funciones")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FuncionesView");
    }
}
