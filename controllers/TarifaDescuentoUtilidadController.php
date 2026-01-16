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

class TarifaDescuentoUtilidadController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/TarifaDescuentoUtilidadList[/{Id}]", [PermissionMiddleware::class], "list.tarifa_descuento_utilidad")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/TarifaDescuentoUtilidadAdd[/{Id}]", [PermissionMiddleware::class], "add.tarifa_descuento_utilidad")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/TarifaDescuentoUtilidadView[/{Id}]", [PermissionMiddleware::class], "view.tarifa_descuento_utilidad")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/TarifaDescuentoUtilidadEdit[/{Id}]", [PermissionMiddleware::class], "edit.tarifa_descuento_utilidad")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/TarifaDescuentoUtilidadDelete[/{Id}]", [PermissionMiddleware::class], "delete.tarifa_descuento_utilidad")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadDelete");
    }

    // preview
    #[Map(["GET","OPTIONS"], "/TarifaDescuentoUtilidadPreview", [PermissionMiddleware::class], "preview.tarifa_descuento_utilidad")]
    public function preview(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TarifaDescuentoUtilidadPreview", null, false);
    }
}
