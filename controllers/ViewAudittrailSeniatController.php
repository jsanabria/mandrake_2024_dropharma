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

class ViewAudittrailSeniatController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/ViewAudittrailSeniatList[/{id}]", [PermissionMiddleware::class], "list.view_audittrail_seniat")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewAudittrailSeniatList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ViewAudittrailSeniatAdd[/{id}]", [PermissionMiddleware::class], "add.view_audittrail_seniat")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewAudittrailSeniatAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ViewAudittrailSeniatView[/{id}]", [PermissionMiddleware::class], "view.view_audittrail_seniat")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ViewAudittrailSeniatView");
    }
}
