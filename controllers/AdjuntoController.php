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

class AdjuntoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoList[/{id}]", [PermissionMiddleware::class], "list.adjunto")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AdjuntoAdd[/{id}]", [PermissionMiddleware::class], "add.adjunto")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoAdd");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/AdjuntoPreview", [PermissionMiddleware::class], "preview.adjunto")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AdjuntoPreview", null, false);
    }
}
