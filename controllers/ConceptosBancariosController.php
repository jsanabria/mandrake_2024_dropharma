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

class ConceptosBancariosController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ConceptosBancariosList[/{id}]", [PermissionMiddleware::class], "list.conceptos_bancarios")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConceptosBancariosList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ConceptosBancariosAdd[/{id}]", [PermissionMiddleware::class], "add.conceptos_bancarios")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConceptosBancariosAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ConceptosBancariosView[/{id}]", [PermissionMiddleware::class], "view.conceptos_bancarios")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConceptosBancariosView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ConceptosBancariosEdit[/{id}]", [PermissionMiddleware::class], "edit.conceptos_bancarios")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConceptosBancariosEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ConceptosBancariosDelete[/{id}]", [PermissionMiddleware::class], "delete.conceptos_bancarios")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ConceptosBancariosDelete");
    }
}
