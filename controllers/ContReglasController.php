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

class ContReglasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContReglasList[/{id}]", [PermissionMiddleware::class], "list.cont_reglas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ContReglasView[/{id}]", [PermissionMiddleware::class], "view.cont_reglas")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContReglasEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_reglas")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasEdit");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ContReglasPreview", [PermissionMiddleware::class], "preview.cont_reglas")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContReglasPreview", null, false);
    }
}
