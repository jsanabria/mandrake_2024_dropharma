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

class MovimientoBancarioController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/MovimientoBancarioList[/{id}]", [PermissionMiddleware::class], "list.movimiento_bancario")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MovimientoBancarioList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/MovimientoBancarioAdd[/{id}]", [PermissionMiddleware::class], "add.movimiento_bancario")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MovimientoBancarioAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/MovimientoBancarioView[/{id}]", [PermissionMiddleware::class], "view.movimiento_bancario")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MovimientoBancarioView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/MovimientoBancarioEdit[/{id}]", [PermissionMiddleware::class], "edit.movimiento_bancario")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MovimientoBancarioEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/MovimientoBancarioDelete[/{id}]", [PermissionMiddleware::class], "delete.movimiento_bancario")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MovimientoBancarioDelete");
    }
}
