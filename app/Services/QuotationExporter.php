<?php

namespace App\Services;

use App\Models\Quotation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotationExporter
{
    /**
     * Export given quotation to xlsx based on a template with placeholders.
     * Returns the relative public path (e.g. 'storage/quotations/..../file.xlsx').
     */
    public function export(Quotation $quotation, ?string $templatePath = null): string
    {
        $templatePath = $templatePath ?: storage_path('app/templates/Mitra10 - Quotation.xlsx');

        // Load spreadsheet
        $spreadsheet = IOFactory::load($templatePath);

        // Choose the sheet that contains the placeholders (use first if uncertain)
        $sheet = $spreadsheet->getSheetByName('Customer') ?: $spreadsheet->getSheet(0);

        // 1) Replace scalar placeholders everywhere
        $scalars = $this->buildScalarMapping($quotation);
        $this->replaceScalarsInSheet($sheet, $scalars);

        // 2) Write items dynamically
        $this->writeItemsDynamically($sheet, $quotation);

        // Save to public disk
        $dir = 'public/quotations/' . date('Y/m');
        if (!Storage::exists($dir))
            Storage::makeDirectory($dir);

        $safeNo = Str::slug($quotation->quotation_no, '_');
        $filename = $safeNo . '.xlsx';
        $fullStoragePath = $dir . '/' . $filename;

        // Use IOFactory writer
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // Save to a temp path then put into Storage for portability
        $tmp = tempnam(sys_get_temp_dir(), 'qtn_');
        $writer->save($tmp);
        Storage::disk('public')->put($fullStoragePath, file_get_contents($tmp));
        @unlink($tmp);

        // Return public URL-ish path (via storage symlink)
        $relative = Storage::disk('public')->url($fullStoragePath);  // quotations/2025/08/xxx.xlsx
        $quotation->update(['file_path' => $relative]);

        return $relative;
    }

    private function buildScalarMapping(Quotation $q): array
    {
        $created = optional($q->created_date)->toDateString();
        $expired = optional($q->expired_date)->toDateString();

        // keys must match your template placeholders: {{key}}
        return [
            'quotation_no' => $q->quotation_no,
            'deals_id' => $q->deals_id,
            'store_id' => $q->store_id,
            'store_name' => $q->store_name,
            'created_date' => $created,
            'quotation_exp_date' => $expired,
            'quotation_expired_date__in_days' => $q->valid_days,
            'cust_name' => $q->customer_name,
            'payment_term' => $q->payment_term,
            'no_rel_store' => $q->no_rek_store,
            'deal_size' => number_format((float) $q->grand_total, 2, '.', ','),
        ];
    }

    private function replaceScalarsInSheet(Worksheet $sheet, array $map): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestCol; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $val = (string) $cell->getValue();

                if (strpos($val, '{{') !== false) {
                    $cell->setValue($this->replaceScalarsInString($val, $map));
                }
            }
        }
    }

    private function replaceScalarsInString(string $text, array $map): string
    {
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($m) use ($map) {
            $key = $m[1] ?? '';
            return array_key_exists($key, $map) ? (string) $map[$key] : $m[0]; // leave unknown
        }, $text);
    }

    /**
     * Find the row that contains the item placeholders and write all items.
     * Supported placeholders within one row:
     *   {{item_no}}, {{item_name}}, {{uom}}, {{item_qty}}, {{price}}, {{disc}}, {{line_total}}
     * We will clone that row's style, insert more rows, and fill them.
     */
    private function writeItemsDynamically(Worksheet $sheet, Quotation $q): void
    {
        $items = $q->items()->orderBy('row_no')->get();
        if ($items->isEmpty())
            return;

        // 1) Detect template row + columns by scanning the sheet for placeholders
        $anchor = $this->locateItemTemplateRow($sheet);
        if (!$anchor)
            return; // silently skip if no template row

        ['row' => $tmplRow, 'cols' => $cols] = $anchor;

        // If multiple items, insert (count - 1) rows below template row to make room
        $count = $items->count();
        if ($count > 1) {
            $sheet->insertNewRowBefore($tmplRow + 1, $count - 1);
        }

        // 2) Fill rows, clone style from template row to each new row
        $this->cloneRowStyle($sheet, $tmplRow, $count);

        $r = 0;
        foreach ($items as $it) {
            $targetRow = $tmplRow + $r;

            $sheet->setCellValueByColumnAndRow($cols['item_no'] ?? null, $targetRow, $it->item_no);
            $sheet->setCellValueByColumnAndRow($cols['item_name'] ?? null, $targetRow, $it->item_name);
            $sheet->setCellValueByColumnAndRow($cols['uom'] ?? null, $targetRow, $it->uom);
            $sheet->setCellValueByColumnAndRow($cols['item_qty'] ?? null, $targetRow, $it->quantity);
            $sheet->setCellValueByColumnAndRow($cols['price'] ?? null, $targetRow, (float) $it->unit_price);
            $sheet->setCellValueByColumnAndRow($cols['disc'] ?? null, $targetRow, (float) $it->discount_percent);
            $sheet->setCellValueByColumnAndRow($cols['line_total'] ?? null, $targetRow, (float) $it->line_total);

            $r++;
        }
    }

    /**
     * Scan to find the item template row by searching placeholders in any cell.
     * Returns ['row' => int, 'cols' => ['item_no'=>colIndex, ...]]
     */
    private function locateItemTemplateRow(Worksheet $sheet): ?array
    {
        $wanted = [
            'item_no',
            'item_name',
            'uom',
            'item_qty',
            'price',
            'disc',
            'line_total'
        ];

        $foundCols = [];
        $foundRow = null;

        $highestRow = $sheet->getHighestRow();
        $highestCol = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowMap = [];
            for ($col = 1; $col <= $highestCol; $col++) {
                $val = (string) $sheet->getCellByColumnAndRow($col, $row)->getValue();
                if (preg_match('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', $val, $m)) {
                    $key = $m[1];
                    if (in_array($key, $wanted, true)) {
                        $rowMap[$key] = $col;
                    }
                }
            }
            // consider a row valid if at least item_name + one numeric column exist
            if (!empty($rowMap['item_name']) && (isset($rowMap['item_qty']) || isset($rowMap['price']) || isset($rowMap['line_total']))) {
                $foundRow = $row;
                $foundCols = $rowMap;
                break;
            }
        }

        if (!$foundRow)
            return null;

        // Clean placeholders in the template row (we’ll overwrite anyway)
        foreach ($foundCols as $colIndex) {
            $sheet->setCellValueByColumnAndRow($colIndex, $foundRow, '');
        }

        return ['row' => $foundRow, 'cols' => $foundCols];
    }

    /**
     * Clone (basic) row styles from template row to next N-1 rows.
     */
    private function cloneRowStyle(Worksheet $sheet, int $sourceRow, int $count): void
    {
        if ($count <= 1)
            return;
        $highestCol = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($i = 1; $i < $count; $i++) {
            $targetRow = $sourceRow + $i;
            for ($col = 1; $col <= $highestCol; $col++) {
                $srcCell = $sheet->getCellByColumnAndRow($col, $sourceRow);
                $dstCell = $sheet->getCellByColumnAndRow($col, $targetRow);

                // Clone style
                $sheet->duplicateStyle($sheet->getStyleByColumnAndRow($col, $sourceRow), Coordinate::stringFromColumnIndex($col) . $targetRow);

                // Copy column width is per column (not necessary per row)
                // Values will be set later; leave empty for now
                $dstCell->setValueExplicit('', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
        }
    }
}