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

class AdjuntoSalidaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoSalidaList[/{id}]", [PermissionMiddleware::class], "list.adjunto_salida")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoSalidaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoSalidaAdd[/{id}]", [PermissionMiddleware::class], "add.adjunto_salida")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoSalidaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoSalidaView[/{id}]", [PermissionMiddleware::class], "view.adjunto_salida")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoSalidaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoSalidaEdit[/{id}]", [PermissionMiddleware::class], "edit.adjunto_salida")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoSalidaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoSalidaDelete[/{id}]", [PermissionMiddleware::class], "delete.adjunto_salida")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoSalidaDelete");
    }
}
