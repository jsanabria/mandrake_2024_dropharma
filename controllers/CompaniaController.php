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

class CompaniaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/CompaniaList[/{id}]", [PermissionMiddleware::class], "list.compania")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/CompaniaView[/{id}]", [PermissionMiddleware::class], "view.compania")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/CompaniaEdit[/{id}]", [PermissionMiddleware::class], "edit.compania")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CompaniaEdit");
    }
}
