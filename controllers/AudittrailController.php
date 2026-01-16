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

class AudittrailController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/AudittrailList[/{id}]", [PermissionMiddleware::class], "list.audittrail")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/AudittrailAdd[/{id}]", [PermissionMiddleware::class], "add.audittrail")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/AudittrailView[/{id}]", [PermissionMiddleware::class], "view.audittrail")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "AudittrailView");
    }
}
