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

class RecargaController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/RecargaList[/{id}]", [PermissionMiddleware::class], "list.recarga")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RecargaList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/RecargaView[/{id}]", [PermissionMiddleware::class], "view.recarga")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RecargaView");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/RecargaPreview", [PermissionMiddleware::class], "preview.recarga")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "RecargaPreview", null, false);
    }
}
