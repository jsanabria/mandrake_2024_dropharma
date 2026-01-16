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

class ContAsientoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoList[/{id}]", [PermissionMiddleware::class], "list.cont_asiento")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoAdd[/{id}]", [PermissionMiddleware::class], "add.cont_asiento")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoAdd");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoEdit[/{id}]", [PermissionMiddleware::class], "edit.cont_asiento")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ContAsientoDelete[/{id}]", [PermissionMiddleware::class], "delete.cont_asiento")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ContAsientoPreview", [PermissionMiddleware::class], "preview.cont_asiento")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ContAsientoPreview", null, false);
    }
}
