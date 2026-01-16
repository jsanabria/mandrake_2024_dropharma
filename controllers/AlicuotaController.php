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

class AlicuotaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AlicuotaList[/{id}]", [PermissionMiddleware::class], "list.alicuota")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlicuotaList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AlicuotaAdd[/{id}]", [PermissionMiddleware::class], "add.alicuota")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlicuotaAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AlicuotaView[/{id}]", [PermissionMiddleware::class], "view.alicuota")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlicuotaView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/AlicuotaEdit[/{id}]", [PermissionMiddleware::class], "edit.alicuota")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlicuotaEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/AlicuotaDelete[/{id}]", [PermissionMiddleware::class], "delete.alicuota")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AlicuotaDelete");
    }
}
