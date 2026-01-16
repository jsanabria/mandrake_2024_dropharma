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

class ContLotesPagosController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosList[/{id}]", [PermissionMiddleware::class], "list.cont_lotes_pagos")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosAdd[/{id}]", [PermissionMiddleware::class], "add.cont_lotes_pagos")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosView[/{id}]", [PermissionMiddleware::class], "view.cont_lotes_pagos")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosView");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContLotesPagosDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_lotes_pagos")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContLotesPagosDelete");
    }
}
