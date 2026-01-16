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

class ContMesContableController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContMesContableList[/{id}]", [PermissionMiddleware::class], "list.cont_mes_contable")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMesContableList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContMesContableView[/{id}]", [PermissionMiddleware::class], "view.cont_mes_contable")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMesContableView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContMesContableEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_mes_contable")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContMesContableEdit");
    }
}
