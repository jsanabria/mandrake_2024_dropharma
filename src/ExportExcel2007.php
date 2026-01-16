<?php

namespace PHPMaker2021\mandrake;

/**
 * Class for export to Excel2007 by PhpSpreadsheet
 */
class ExportExcel2007 extends ExportExcel5
{
    // Field caption by column and row
    public function exportCaptionBy(&$fld, $col, $row)
    {
        $val = $this->convertToUtf8($fld->exportCaption());
        // Example: Use rich text for caption
        $objRichText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); // Rich Text
        $obj = $objRichText->createTextRun($val);
        $obj->getFont()->setBold(true); // Bold
        //$obj->getFont()->setItalic(true);
        //$obj->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN)); // Set color
        $this->PhpSpreadsheet->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValue($objRichText);
    }

    // Export
    public function export()
    {
        global $ExportFileName;
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $ExportFileName . '.xlsx');
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->PhpSpreadsheet, 'Xlsx');
        $objWriter->save('php://output');
        DeleteTempImages();
    }
}
