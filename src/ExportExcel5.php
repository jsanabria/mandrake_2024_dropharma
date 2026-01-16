<?php

namespace PHPMaker2021\mandrake;

/**
 * Class for export to Excel5 by PhpSpreadsheet
 */
class ExportExcel5 extends ExportBase
{
    protected $PhpSpreadsheet;
    protected $RowType;
    protected $PageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT;
    protected $PageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
    public static $WidthMultiplier = 0.15; // Cell width multipler for image fields
    public static $HeightMultiplier = 0.8; // Row height multipler for image fields
    public static $MaxImageWidth = 400; // Max image width <= 400 is recommended

    // Constructor
    public function __construct(&$tbl, $style = "")
    {
        parent::__construct($tbl, $style);
        $this->PhpSpreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->PhpSpreadsheet->setActiveSheetIndex(0);
        if ($tbl->ExportExcelPageOrientation != "") {
            $this->PageOrientation = $tbl->ExportExcelPageOrientation;
        }
        if ($tbl->ExportExcelPageSize != "") {
            $this->PageSize = $tbl->ExportExcelPageSize;
        }
        $this->PhpSpreadsheet->getActiveSheet()->getPageSetup()->setOrientation($this->PageOrientation);
        $this->PhpSpreadsheet->getActiveSheet()->getPageSetup()->setPaperSize($this->PageSize);
    }

    // Convert to UTF-8
    protected function convertToUtf8($value)
    {
        $value = RemoveHtml($value);
        $value = HtmlDecode($value);
        //$value = HtmlEncode($value); // No need to encode (unlike PHPWord)
        return ConvertToUtf8($value);
    }

    // Set value by column and row
    public function setCellValueByColumnAndRow($col, $row, $val)
    {
        $val = trim($val);
        $this->PhpSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
    }

    // Table header
    public function exportTableHeader()
    {
    }

    // Field aggregate
    public function exportAggregate(&$fld, $type)
    {
        if (!$fld->Exportable) {
            return;
        }
        $this->FldCnt++;
        if ($this->Horizontal) {
            global $Language;
            $val = "";
            if (in_array($type, ["TOTAL", "COUNT", "AVERAGE"])) {
                $val = $Language->phrase($type) . ": " . $this->convertToUtf8($fld->exportValue());
            }
            $this->setCellValueByColumnAndRow($this->FldCnt, $this->RowCnt, $val);
        }
    }

    // Field caption
    public function exportCaption(&$fld)
    {
        if (!$fld->Exportable) {
            return;
        }
        $this->FldCnt++;
        $this->exportCaptionBy($fld, $this->FldCnt, $this->RowCnt);
    }

    // Field caption by column and row
    public function exportCaptionBy(&$fld, $col, $row)
    {
        $val = $this->convertToUtf8($fld->exportCaption());
        $this->setCellValueByColumnAndRow($col, $row, $val); // Plain text
    }

    // Field value by column and row
    public function exportValueBy(&$fld, $col, $row)
    {
        $val = "";
        $sheet = $this->PhpSpreadsheet->getActiveSheet();
        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        if ($fld->ExportFieldImage && $fld->ViewTag == "IMAGE") { // Image
            $imagefn = $fld->getTempImage();
            if (!$fld->UploadMultiple || !ContainsString($imagefn, ",")) {
                $fn = ServerMapPath($imagefn, true);
                if ($imagefn != "" && file_exists($fn) && !is_dir($fn)) {
                    $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $objDrawing->setWorksheet($sheet);
                    $objDrawing->setPath($fn);
                    $objDrawing->setCoordinates($letter . strval($row));
                    if (self::$MaxImageWidth > 0 && $objDrawing->getWidth() > self::$MaxImageWidth) {
                        $objDrawing->setWidth(self::$MaxImageWidth);
                    }
                    $size = [$objDrawing->getWidth(), $objDrawing->getHeight()]; // Get image size
                    if ($size[0] > 0) { // Width
                        $sheet->getColumnDimension($letter)->setWidth($size[0] * self::$WidthMultiplier); // Set column width
                    }
                    if ($size[1] > 0) { // Height
                        $sheet->getRowDimension($row)->setRowHeight($size[1] * self::$HeightMultiplier); // Set row height
                    }
                }
            } else {
                $totalW = 0;
                $maxH = 0;
                $ar = explode(",", $imagefn);
                foreach ($ar as $imagefn) {
                    $fn = ServerMapPath($imagefn, true);
                    if ($imagefn != "" && file_exists($fn) && !is_dir($fn)) {
                        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $objDrawing->setWorksheet($sheet);
                        $objDrawing->setPath($fn);
                        $objDrawing->setOffsetX($totalW);
                        $objDrawing->setCoordinates($letter . strval($row));
                        if (self::$MaxImageWidth > 0 && $objDrawing->getWidth() > self::$MaxImageWidth) {
                            $objDrawing->setWidth(self::$MaxImageWidth);
                        }
                        $size = [$objDrawing->getWidth(), $objDrawing->getHeight()]; // Get image size
                        if ($size[0] > 0) { // Width
                            $totalW += $size[0];
                        }
                        $maxH = max($maxH, $size[1]); // Height
                    }
                }
                if ($totalW > 0 && $this->Horizontal) { // Width
                    $sheet->getColumnDimension($letter)->setAutoSize(true)->setWidth($totalW * self::$WidthMultiplier); // Set column width, no auto size
                }
                if ($maxH > 0) { // Height
                    $sheet->getRowDimension($row)->setRowHeight($maxH * self::$HeightMultiplier); // Set row height
                }
            }
        } elseif ($fld->ExportFieldImage && $fld->ExportHrefValue != "") { // Export custom view tag
            $imagefn = $fld->ExportHrefValue;
            $fn = ServerMapPath($imagefn, true);
            if ($imagefn != "" && file_exists($fn) && !is_dir($fn)) {
                $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $objDrawing->setWorksheet($sheet);
                $objDrawing->setPath($fn);
                $objDrawing->setCoordinates($letter . strval($row));
                if (self::$MaxImageWidth > 0 && $objDrawing->getWidth() > self::$MaxImageWidth) {
                    $objDrawing->setWidth(self::$MaxImageWidth);
                }
                $size = [$objDrawing->getWidth(), $objDrawing->getHeight()]; // Get image size
                if ($size[0] > 0 && $this->Horizontal) { // Width
                    $sheet->getColumnDimension($letter)->setAutoSize(true)->setWidth($size[0] * self::$WidthMultiplier); // Set column width
                }
                if ($size[1] > 0) { // Height
                    $sheet->getRowDimension($row)->setRowHeight($size[1] * self::$HeightMultiplier); // Set row height
                }
            }
        } else { // Formatted Text
            $val = $this->convertToUtf8($fld->exportValue());
            if ($this->RowType > 0) { // Not table header/footer
                if (in_array($fld->Type, [4, 5, 6, 14, 131]) && $fld->Lookup === null) { // If float or currency
                    $val = $this->convertToUtf8($fld->CurrentValue); // Use original value instead of formatted value
                }
            }
            $this->setCellValueByColumnAndRow($col, $row, $val);
            if ($this->Horizontal) {
                $sheet->getColumnDimension($letter)->setAutoSize(true);
            }
        }
    }

    // Begin a row
    public function beginExportRow($rowCnt = 0, $useStyle = true)
    {
        $this->RowCnt++;
        $this->FldCnt = 0;
        $this->RowType = $rowCnt;
    }

    // End a row
    public function endExportRow($rowCnt = 0)
    {
    }

    // Empty row
    public function exportEmptyRow()
    {
        $this->RowCnt++;
    }

    // Page break
    public function exportPageBreak()
    {
    }

    // Export a field
    public function exportField(&$fld)
    {
        if (!$fld->Exportable) {
            return;
        }
        $this->FldCnt++;
        if ($this->Horizontal) {
            $this->exportValueBy($fld, $this->FldCnt, $this->RowCnt);
        } else { // Vertical, export as a row
            $this->RowCnt++;
            $this->exportCaptionBy($fld, 1, $this->RowCnt);
            $this->exportValueBy($fld, 2, $this->RowCnt);
        }
    }

    // Table footer
    public function exportTableFooter()
    {
    }

    // Add HTML tags
    public function exportHeaderAndFooter()
    {
    }

    // Export
    public function export()
    {
        global $ExportFileName;
        if (!Config("DEBUG") && ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $ExportFileName . '.xls');
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->PhpSpreadsheet, 'Xls');
        $objWriter->save('php://output');
        DeleteTempImages();
    }

    // Destructor
    public function __destruct()
    {
        DeleteTempImages();
    }
}
