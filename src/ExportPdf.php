<?php

namespace PHPMaker2024\mandrake;

/**
 * Class for export to PDF
 */
class ExportPdf extends AbstractExport
{
    // Export
    public function export($fileName = "", $output = true, $save = false)
    {
        throw new \Exception("Export PDF extension disabled");
    }
}
