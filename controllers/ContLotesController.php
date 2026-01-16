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

class ContLotesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContLotesList[/{id}]", [PermissionMiddleware::class], "list.cont_lotes")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContLotesAdd[/{id}]", [PermissionMiddleware::class], "add.cont_lotes")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContLotesView[/{id}]", [PermissionMiddleware::class], "view.cont_lotes")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesView");
    }
}
