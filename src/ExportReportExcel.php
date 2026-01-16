<?php

namespace PHPMaker2021\mandrake;

/**
 * Export to Excel
 */
class ExportReportExcel
{
    // Export
    public function __invoke($page, $html)
    {
        global $ExportFileName;
        $format = "Excel5";
        $doc = new \DOMDocument();
        $html = preg_replace('/<meta\b(?:[^"\'>]|"[^"]*"|\'[^\']*\')*>/i', "", $html); // Remove meta tags
        @$doc->loadHTML('<?xml encoding="utf-8">' . ConvertToUtf8($html)); // Convert to utf-8
        $tables = $doc->getElementsByTagName("table");
        $phpspreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $phpspreadsheet->setActiveSheetIndex(0);
        $sheet = $phpspreadsheet->getActiveSheet();
        if ($page->ExportExcelPageOrientation != "") {
            $sheet->getPageSetup()->setOrientation($page->ExportExcelPageOrientation);
        }
        if ($page->ExportExcelPageSize != "") {
            $sheet->getPageSetup()->setPaperSize($page->ExportExcelPageSize);
        }
        $maxImageWidth = ExportExcel5::$MaxImageWidth; // Max image width <= 400 is recommended
        $widthMultiplier = ExportExcel5::$WidthMultiplier; // Cell width multipler for image fields
        $heightMultiplier = ExportExcel5::$HeightMultiplier; // Row height multipler for image fields
        $m = 1;
        $maxcellcnt = 1;
        $div = $doc->getElementById("ew-filter-list");
        if ($div) {
            $parent = $div->parentNode;
            $cls = $parent->getAttribute("class");
            if (!preg_match('/\bd-none\b/', $cls)) {
                $div2 = $doc->getElementById("ew-current-date");
                if ($div2) {
                    $value = trim($div2->textContent);
                    $sheet->setCellValueByColumnAndRow(1, $m, $value);
                    $m++;
                }
                $div2 = $doc->getElementById("ew-current-filters");
                if ($div2) {
                    $value = trim($div2->textContent);
                    $sheet->setCellValueByColumnAndRow(1, $m, $value);
                    $m++;
                }
                $spans = $div->getElementsByTagName("span");
                $spancnt = $spans->length;
                for ($i = 0; $i < $spancnt; $i++) {
                    $span = $spans->item($i);
                    $class = $span->getAttribute("class");
                    if ($class == "ew-filter-caption") {
                        $caption = trim($span->textContent);
                        $i++;
                        $span = $spans->item($i);
                        $class = $span->getAttribute("class");
                        if ($class == "ew-filter-value") {
                            $value = trim($span->textContent);
                            $sheet->setCellValueByColumnAndRow(1, $m, $caption . ": " . $value);
                            $m++;
                        }
                    }
                }
            }
            if ($m > 1)
                $m++;
        }
        foreach ($tables as $table) {
            $tableclass = $table->getAttribute("class");
            $isChart = ContainsText($tableclass, "ew-chart");
            $isTable = ContainsText($tableclass, "ew-table");
            if ($isTable || $isChart) {
                // Check page break for chart (before)
                if ($isChart && $page->ExportChartPageBreak && $table->getAttribute("data-page-break") == "before") {
                    $sheet->setBreak("A" . strval($m), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                    $m++;
                }
                $rows = $table->getElementsByTagName("tr");
                $rowcnt = $rows->length;
                for ($i = 0; $i < $rowcnt; $i++) {
                    $row = $rows->item($i);
                    $cells = $row->childNodes;
                    $cellcnt = $cells->length;
                    $k = 1;
                    for ($j = 0; $j < $cellcnt; $j++) {
                        $cell = $cells->item($j);
                        if ($cell->nodeType != XML_ELEMENT_NODE || $cell->tagName != "td" && $cell->tagName != "th") {
                            continue;
                        }
                        $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($k);
                        $images = $cell->getElementsByTagName("img");
                        if ($images->length > 0) { // Images
                            $totalW = 0;
                            $maxH = 0;
                            foreach ($images as $image) {
                                $fn = $image->getAttribute("src");
                                $path = parse_url($fn, PHP_URL_PATH);
                                $ext = pathinfo($path, PATHINFO_EXTENSION);
                                if (SameText($ext, "php")) { // Image by script
                                    $fn = FullUrl($fn);
                                    $data = file_get_contents($fn);
                                    $fn = TempImage($data);
                                }
                                if (!file_exists($fn) || is_dir($fn)) {
                                    continue;
                                }
                                $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                                $objDrawing->setWorksheet($sheet);
                                $objDrawing->setPath($fn);
                                $objDrawing->setOffsetX($totalW);
                                $objDrawing->setCoordinates($letter . strval($m));
                                $size = [$objDrawing->getWidth(), $objDrawing->getHeight()]; // Get image size
                                if ($size[0] > 0) { // Width
                                    $totalW += $size[0];
                                }
                                $maxH = max($maxH, $size[1]); // Height
                            }
                            if ($totalW > 0 && $isTable) { // Width
                                $sheet->getColumnDimension($letter)->setAutoSize(false)->setWidth($totalW * $widthMultiplier); // Set column width, no auto size
                            }
                            if ($maxH > 0) { // Height
                                $sheet->getRowDimension($m)->setRowHeight($maxH * $heightMultiplier); // Set row height
                            }
                        } else { // Text
                            $value = preg_replace(['/[\r\n\t]+:/', '/[\r\n\t]+\(/'], [":", " ("], trim($cell->textContent)); // Replace extra whitespaces before ":" and "("
                            if ($format == "Excel2007" && $row->parentNode->tagName == "thead") { // Caption
                                $objRichText = new \PhpOffice\PhpSpreadsheet\RichText\RichText(); // Rich Text
                                $obj = $objRichText->createTextRun($value);
                                $obj->getFont()->setBold(true); // Bold
                                //$obj->getFont()->setItalic(true);
                                //$obj->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN)); // Set color
                                $sheet->getCellByColumnAndRow($k, $m)->setValue($objRichText);
                            } else {
                                $sheet->setCellValueByColumnAndRow($k, $m, $value);
                            }
                            $sheet->getColumnDimension($letter)->setAutoSize(true);
                        }
                        if ($cell->hasAttribute("colspan")) {
                            $k += (int)$cell->getAttribute("colspan");
                        } else {
                            $k++;
                        }
                    }
                    if ($k > $maxcellcnt) {
                        $maxcellcnt = $k;
                    }
                    $m++;
                }
                // Check page break for chart (after)
                if ($isChart && $page->ExportChartPageBreak && $table->getAttribute("data-page-break") == "after") {
                    $sheet->setBreak("A" . strval($m), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                // Check page break for table
                if ($isTable) {
                    $node = $table->parentNode;
                    while ($node && $node->getAttribute("class") && !ContainsText($node->getAttribute("class"), "ew-grid")) {
                        $node = $node->parentNode;
                    }
                    if ($node) {
                        $node = $node->nextSibling;
                        while ($node && $node->nodeType != XML_ELEMENT_NODE) {
                            $node = $node->nextSibling;
                        }
                        if ($node && $node->getAttribute("class") && $node->getAttribute("class") == "ew-page-break") {
                            $sheet->setBreak("A" . strval($m), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                        }
                    }
                }
                $m++;
            }
        }
        $sheet->calculateColumnWidths(); // Make the image sizes correct
        if (!Config("DEBUG") && ob_get_length()) {
            ob_end_clean();
        }
        if ($format == "Excel5") {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=' . $ExportFileName . '.xls');
        } else { // Excel2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename=' . $ExportFileName . '.xlsx');
        }
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($phpspreadsheet, ($format == "Excel5") ? "Xls" : "Xlsx");
        $objWriter->save('php://output');
        DeleteTempImages();
        exit();
    }
}
