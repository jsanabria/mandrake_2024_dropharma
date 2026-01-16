<?php

namespace PHPMaker2024\mandrake;

use \PhpOffice\PhpSpreadsheet;
use DiDom\Document;
use DiDom\Element;

/**
 * Class for export to Excel5/Excel2007 by PhpSpreadsheet
 */
class ExportExcel extends AbstractExport
{
    public static $TextWidthMultiplier = 2; // Cell width multipler for text fields
    public static $WidthMultiplier = 0.15; // Cell width multipler for image fields
    public static $HeightMultiplier = 0.8; // Row height multipler for image fields
    public static $MaxImageWidth = 400; // Max image width <= 400 is recommended
    public $Format = ""; // Excel5/Excel2007
    public $PhpSpreadsheet;
    public $RowType = 0;
    public $PageOrientation = PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT;
    public $PageSize = PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;

    // Constructor
    public function __construct($table = null)
    {
        $this->Format = Config("EXPORT_EXCEL_FORMAT");
        $this->FileExtension = $this->isExcel2007() ? "xlsx" : "xls"; // Set file extension first
        parent::__construct($table);
        $this->PhpSpreadsheet = new PhpSpreadsheet\Spreadsheet();
        $this->PhpSpreadsheet->setActiveSheetIndex(0);
        if ($table && $table->ExportExcelPageOrientation != "") {
            $this->PageOrientation = $table->ExportExcelPageOrientation;
        }
        if ($table && $table->ExportExcelPageSize != "") {
            $this->PageSize = $table->ExportExcelPageSize;
        }
        $pageSetup = $this->getActiveSheet()->getPageSetup();
        $pageSetup->setOrientation($this->PageOrientation);
        $pageSetup->setPaperSize($this->PageSize);
    }

    // Check if Excel2007
    public function isExcel2007()
    {
        return $this->Format == "Excel2007";
    }

    // Convert to UTF-8
    public function convertToUtf8($value)
    {
        $value = RemoveHtml($value);
        $value = HtmlDecode($value);
        //$value = HtmlEncode($value); // No need to encode (unlike PHPWord)
        return ConvertToUtf8($value);
    }

    // Get active sheet
    public function getActiveSheet()
    {
        return $this->PhpSpreadsheet->getActiveSheet();
    }

    // Table header
    public function exportTableHeader()
    {
        // Example - Insert an image at column "A"
        // $this->RowCnt++; // Increase row count
        // $image = $this->createImage("./upload/logo.png"); // Create image from a physical path or a path relative to project folder
        // $image->setCoordinates("A" . $this->RowCnt); // Insert image
    }

    /**
     * Set value by column and row
     *
     * @param int $col Column (1-based)
     * @param int $row Row (1-based)
     * @param mixed $val Value (utf-8 encoded)
     * @param ?array $style Style
     * @return void
     */
    public function setCellValueByColumnAndRow($col, $row, $val, $style = null)
    {
        $sheet = $this->getActiveSheet();
        $sheet->setCellValueByColumnAndRow($col, $row, $val);
        if ($this->Horizontal) { // Adjust column width
            $letter = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $cd = $sheet->getColumnDimension($letter);
            $font = $this->PhpSpreadsheet->getDefaultStyle()->getFont();
            $txt = $val instanceof PhpSpreadsheet\RichText\RichText ? $val->getPlainText() : $val;
            $multiplier = preg_match("/\p{Han}+/u", $txt) ? self::$TextWidthMultiplier : 1; // Handle Chinese
            $w = PhpSpreadsheet\Shared\Font::getTextWidthPixelsApprox($txt, $font, 0) * $multiplier;
            $cd->setWidth(max($w, $cd->getWidth("px")), "px"); // Set column width
            if ($style) {
                $sheet->getCell($letter . $row)->getStyle()->applyFromArray($style);
            }
        }
    }

    // Field aggregate
    public function exportAggregate($fld, $type)
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
    public function exportCaption($fld)
    {
        if (!$fld->Exportable) {
            return;
        }
        $this->FldCnt++;
        $this->exportCaptionBy($fld, $this->FldCnt, $this->RowCnt);
    }

    // Field caption by column and row
    public function exportCaptionBy($fld, $col, $row)
    {
        $val = $this->convertToUtf8($fld->exportCaption());
        $val = $this->createRichText($val);
        $this->setCellValueByColumnAndRow($col, $row, $val);
    }

    // Get value as RichText if Excel2007
    public function createRichText($val)
    {
        if ($this->isExcel2007()) {
            $richText = new PhpSpreadsheet\RichText\RichText(); // Rich Text
            $obj = $richText->createTextRun($val);
            $obj->getFont()->setBold(true); // Bold
            return $richText;
        }
        return $val;
    }

    // Create image
    public function createImage($imagefn)
    {
        $sheet = $this->getActiveSheet();
        $drawing = new PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setWorksheet($sheet);
        $drawing->setPath($imagefn);
        if (self::$MaxImageWidth > 0 && $drawing->getWidth() > self::$MaxImageWidth) {
            $drawing->setWidth(self::$MaxImageWidth);
        }
        return $drawing;
    }

    /**
     * Add image by column and row
     *
     * @param mixed $imagefn Image file or Drawing
     * @param ?int $col Column
     * @param ?int $row Row
     * @return void
     */
    public function addImage($imagefn, $break = false, $col = null, $row = null)
    {
        $col ??= 1; // Column A if not specified
        $row ??= ++$this->RowCnt; // Increment if not specified
        $sheet = $this->getActiveSheet();
        if (SameText($break, "before")) {
            $sheet->setBreak("A" . strval($row), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
            $this->RowCnt++;
        }
        $drawing = is_string($imagefn) ? $this->createImage($imagefn) : $imagefn;
        $letter = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $drawing->setCoordinates($letter . strval($row));
        $width = $drawing->getWidth();
        if ($width > 0) { // Width
            $cd = $sheet->getColumnDimension($letter);
            $cd->setWidth(max($drawing->getOffsetX() + $width * self::$WidthMultiplier, $cd->getWidth())); // Set column width
        }
        $height = $drawing->getHeight();
        if ($height > 0) { // Height
            $rd = $sheet->getRowDimension($row);
            $rd->setRowHeight(max($height * self::$HeightMultiplier, $rd->getRowHeight())); // Set row height
        }
        if (SameText($break, "after")) {
            $sheet->setBreak("A" . strval($row), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
            $this->RowCnt++;
        }
        return $drawing;
    }

    // Field value by column and row
    public function exportValueBy($fld, $col, $row)
    {
        $val = "";
        $sheet = $this->getActiveSheet();
        $letter = PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        if ($fld->ExportFieldImage && $fld->ViewTag == "IMAGE") { // Image
            $ar = $fld->getTempImage();
            $totalW = 0;
            foreach ($ar as $imagefn) {
                if (StartsString("data:", $imagefn)) { // Handle base64 image
                    $imagefn = TempImageFromBase64Url($imagefn);
                }
                $fn = realpath($imagefn);
                if ($imagefn != "" && file_exists($fn) && !is_dir($fn)) {
                    $drawing = $this->createImage($fn);
                    $drawing->setOffsetX($totalW);
                    $this->addImage($drawing, false, $col, $row);
                    $totalW += $drawing->getWidth();
                }
            }
        } elseif ($fld->ExportFieldImage && $fld->ExportHrefValue != "") { // Export custom view tag
            $imagefn = $fld->ExportHrefValue;
            if (StartsString("data:", $imagefn)) { // Handle base64 image
                $imagefn = TempImageFromBase64Url($imagefn);
            }
            $fn = realpath($imagefn);
            if ($imagefn != "" && file_exists($fn) && !is_dir($fn)) {
                $this->addImage($fn, false, $col, $row);
            }
        } else { // Formatted Text
            $val = $this->convertToUtf8($fld->exportValue());
            if ($this->RowType > 0) { // Not table header/footer
                if (in_array($fld->Type, [4, 5, 6, 14, 131]) && $fld->Lookup === null) { // If float or currency
                    $val = $this->convertToUtf8($fld->CurrentValue); // Use original value instead of formatted value
                }
            }
            $style = preg_match('/\\btext-align:\\s?(left|center|right|justify)\\b/', $fld->CellCssStyle, $m) ? ["alignment" => ["horizontal" => $m[1]]] : null;
            $this->setCellValueByColumnAndRow($col, $row, $val, $style);
        }
    }

    // Begin a row
    public function beginExportRow($rowCnt = 0)
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
        $this->getActiveSheet()->setBreak("A" . strval($this->RowCnt), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
    }

    // Export a field
    public function exportField($fld)
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
        $this->RowCnt++; // Increment row count
    }

    // Load HTML directly (for report)
    public function loadHtml($html)
    {
        $doc = &$this->getDocument($html);
        $this->adjustPageBreak($doc);
        $metas = $doc->find("meta");
        array_walk($metas, fn($el) => $el->remove()); // Remove meta tags
        $tables = $doc->find(self::$Selectors);
        $sheet = $this->getActiveSheet();
        $break = $this->Table ? $this->Table->ExportPageBreaks : true;
        $maxImageWidth = self::$MaxImageWidth; // Max image width <= 400 is recommended
        $textWidthMultiplier = self::$TextWidthMultiplier; // Cell width multipler for text fields
        $widthMultiplier = self::$WidthMultiplier; // Cell width multipler for image fields
        $heightMultiplier = self::$HeightMultiplier; // Row height multipler for image fields
        $m = $this->RowCnt ?: 1;
        $maxCellCnt = 1;
        $div = $doc->find("#ew-filter-list");
        if (count($div) > 0) {
            $classes = $div[0]->parent()->classes();
            if (!$classes->contains("d-none")) {
                $div2 = $doc->find("#ew-current-date");
                if ($div2) {
                    $value = trim($div2[0]->text());
                    $sheet->setCellValueByColumnAndRow(1, $m, $value);
                    $m++;
                }
                $div2 = $doc->find("#ew-current-filters");
                if ($div2) {
                    $value = trim($div2[0]->text());
                    $sheet->setCellValueByColumnAndRow(1, $m, $value);
                    $m++;
                }
                $spans = $div[0]->find("span");
                $spancnt = count($spans);
                for ($i = 0; $i < $spancnt; $i++) {
                    $span = $spans[$i];
                    $class = $span->getAttribute("class") ?? "";
                    if ($class == "ew-filter-caption") {
                        $caption = trim($span->text());
                        $i++;
                        $span = $spans[$i];
                        $class = $span->getAttribute("class") ?? "";
                        if ($class == "ew-filter-value") {
                            $value = trim($span->text());
                            $sheet->setCellValueByColumnAndRow(1, $m, $caption . ": " . $value);
                            $m++;
                        }
                    }
                }
            }
            if ($m > 1) {
                $m++;
            }
        }
        foreach ($tables as $table) {
            $classes = $table->classes();
            $isChart = $classes->contains("ew-chart");
            $isTable = $classes->contains("ew-table");
            // Check page break for chart (before)
            if ($isChart && $break && $classes->contains("break-before-page")) {
                $sheet->setBreak("A" . strval($m), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                $m++;
            }
            if ($isTable) {
                $rows = $table->find("tr");
                foreach ($rows as $row) {
                    $cells = $row->children();
                    $k = 1;
                    foreach ($cells as $cell) {
                        if (!$cell->isElementNode() || $cell->tagName() != "td" && $cell->tagName() != "th") {
                            continue;
                        }
                        $images = $cell->find("img");
                        if (count($images) > 0) { // Images
                            $totalW = 0;
                            foreach ($images as $image) {
                                $fn = $image->getAttribute("src") ?? "";
                                $path = parse_url($fn, PHP_URL_PATH);
                                $ext = pathinfo($path, PATHINFO_EXTENSION);
                                if (SameText($ext, "php")) { // Image by script
                                    $fn = FullUrl($fn);
                                    $data = file_get_contents($fn);
                                    $fn = TempImage($data);
                                }
                                if (StartsString("data:", $fn)) { // Handle base64 image
                                    $fn = TempImageFromBase64Url($fn);
                                }
                                if (!file_exists($fn) || is_dir($fn)) {
                                    continue;
                                }
                                $drawing = $this->createImage($fn);
                                $drawing->setOffsetX($totalW);
                                $this->addImage($drawing, false, $k, $m);
                                $totalW += $drawing->getWidth();
                            }
                        } else { // Text
                            $value = preg_replace(['/[\r\n\t]+:/', '/[\r\n\t]+\(/'], [":", " ("], trim($cell->text())); // Replace extra whitespaces before ":" and "("
                            $value = SameText($row->parent()->tagName(), "thead") ? $this->createRichText($value) : $value; // Caption
                            $this->setCellValueByColumnAndRow($k, $m, $value);
                        }
                        if ($cell->hasAttribute("colspan")) {
                            $k += intval($cell->getAttribute("colspan") ?? 0);
                        } else {
                            $k++;
                        }
                    }
                    if ($k > $maxCellCnt) {
                        $maxCellCnt = $k;
                    }
                    $m++;
                }
            } else { // div
                $k = 1;
                $images = $table->find("img");
                if (count($images) > 0) { // Images
                    $totalW = 0;
                    foreach ($images as $image) {
                        $fn = $image->getAttribute("src") ?? "";
                        $path = parse_url($fn, PHP_URL_PATH);
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        if (SameText($ext, "php")) { // Image by script
                            $fn = FullUrl($fn);
                            $data = file_get_contents($fn);
                            $fn = TempImage($data);
                        }
                        if (StartsString("data:", $fn)) { // Handle base64 image
                            $fn = TempImageFromBase64Url($fn);
                        }
                        if (!file_exists($fn) || is_dir($fn)) {
                            continue;
                        }
                        $drawing = $this->createImage($fn);
                        $drawing->setOffsetX($totalW);
                        $this->addImage($drawing, false, $k, $m);
                        $totalW += $drawing->getWidth();
                    }
                } else { // Text
                    $value = trim($table->text());
                    $this->setCellValueByColumnAndRow($k, $m, $value);
                }
                $m++;
            }
            // Check page break for chart (after)
            if ($isChart && $break && $classes->contains("break-after-page")) {
                $sheet->setBreak("A" . strval($m), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
            }
            // Check page break for table
            if ($isTable) {
                $node = $table->closest(".ew-grid");
                if ($node) {
                    $node = $node->nextSibling();
                    while ($node && !$node->isElementNode()) {
                        $node = $node->nextSibling();
                    }
                    if ($node?->isElementNode() && $node->classes()->contains("break-before-page")) {
                        $sheet->setBreak("A" . strval($m), PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                    }
                }
            }
            $m++;
        }
        $sheet->calculateColumnWidths(); // Make the image sizes correct
        $this->RowCnt = $m;
    }

    // Export
    public function export($fileName = "", $output = true, $save = false)
    {
        $writer = PhpSpreadsheet\IOFactory::createWriter($this->PhpSpreadsheet, $this->isExcel2007() ? "Xlsx" : "Xls");
        if ($save) { // Save to folder
            $file = ExportPath(true) . $this->getSaveFileName();
            @$writer->save($file);
        }
        if ($output) { // Output
            $this->writeHeaders($fileName);
            @$writer->save("php://output");
        }
    }

    // Destructor
    public function __destruct()
    {
    }
}
