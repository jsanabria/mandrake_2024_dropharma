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

class ContReglasHrController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContReglasHrList[/{id}]", [PermissionMiddleware::class], "list.cont_reglas_hr")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasHrList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContReglasHrView[/{id}]", [PermissionMiddleware::class], "view.cont_reglas_hr")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasHrView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContReglasHrEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_reglas_hr")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasHrEdit");
    }
}
