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

class ViewEntradasSalidasController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewEntradasSalidasList[/{id}]", [PermissionMiddleware::class], "list.view_entradas_salidas")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewEntradasSalidasList");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/ViewEntradasSalidasPreview", [PermissionMiddleware::class], "preview.view_entradas_salidas")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewEntradasSalidasPreview", null, false);
    }
}
