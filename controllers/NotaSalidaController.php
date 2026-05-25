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

class NotaSalidaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/NotaSalidaList[/{id}]", [PermissionMiddleware::class], "list.nota_salida")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaSalidaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/NotaSalidaAdd[/{id}]", [PermissionMiddleware::class], "add.nota_salida")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaSalidaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/NotaSalidaView[/{id}]", [PermissionMiddleware::class], "view.nota_salida")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaSalidaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/NotaSalidaEdit[/{id}]", [PermissionMiddleware::class], "edit.nota_salida")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaSalidaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/NotaSalidaDelete[/{id}]", [PermissionMiddleware::class], "delete.nota_salida")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "NotaSalidaDelete");
    }
}
