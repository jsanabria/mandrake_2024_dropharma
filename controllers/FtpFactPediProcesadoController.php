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

class FtpFactPediProcesadoController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/FtpFactPediProcesadoList[/{id}]", [PermissionMiddleware::class], "list.ftp_fact_pedi_procesado")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FtpFactPediProcesadoList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/FtpFactPediProcesadoAdd[/{id}]", [PermissionMiddleware::class], "add.ftp_fact_pedi_procesado")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FtpFactPediProcesadoAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/FtpFactPediProcesadoView[/{id}]", [PermissionMiddleware::class], "view.ftp_fact_pedi_procesado")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FtpFactPediProcesadoView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/FtpFactPediProcesadoEdit[/{id}]", [PermissionMiddleware::class], "edit.ftp_fact_pedi_procesado")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FtpFactPediProcesadoEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/FtpFactPediProcesadoDelete[/{id}]", [PermissionMiddleware::class], "delete.ftp_fact_pedi_procesado")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "FtpFactPediProcesadoDelete");
    }
}
