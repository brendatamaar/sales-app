<?php

namespace App\Services;

use App\Models\Deal;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class QuotationExporter
{
    public function export(Deal $deal): array
    {
        $templatePath = storage_path('app/templates/mitra10_quotation.xlsx');
        if (!file_exists($templatePath)) {
            throw new \RuntimeException('Template not found: ' . $templatePath);
        }

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // --- utilities ---
        $findCell = function (string $marker) use ($sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach ($cellIterator as $cell) {
                    if (trim((string) $cell->getValue()) === $marker) {
                        return $cell;
                    }
                }
            }
            return null;
        };

        // setValue() by default; setValueExplicit($type) when provided
        $put = function (string $marker, $value, ?string $type = null) use ($findCell) {
            if ($c = $findCell($marker)) {
                if ($type) {
                    $c->setValueExplicit($value, $type);
                } else {
                    $c->setValue($value);
                }
            }
        };

        // --- dates/strings ---
        $dealCreated = $deal->created_date
            ? (is_string($deal->created_date) ? date('d/m/Y', strtotime($deal->created_date)) : $deal->created_date->format('d/m/Y'))
            : date('d/m/Y');

        $exp = $deal->quotation_exp_date ?: ($deal->expired_date ?? null);
        $expStr = $exp
            ? (is_string($exp) ? date('d/m/Y', strtotime($exp)) : $exp->format('d/m/Y'))
            : '';

        // --- header markers ---
        $put('{{QUOTE_NO}}', $deal->quotation_no ?? ('Q-' . $deal->deals_id), DataType::TYPE_STRING);
        $put('{{QUOTE_DATE}}', $dealCreated, DataType::TYPE_STRING);
        $put('{{QUOTE_EXPIRED}}', $expStr, DataType::TYPE_STRING);
        $put('{{QUOTATION_EXP_DATE}}', $expStr, DataType::TYPE_STRING);
        $put('{{STORE_NAME}}', $deal->store_name ?? '', DataType::TYPE_STRING);
        $put('{{STORE_EMAIL}}', $deal->email ?? '', DataType::TYPE_STRING);
        $put('{{NO_REK_STORE}}', $deal->no_rek_store ?? '', DataType::TYPE_STRING);
        $put('{{CUSTOMER_NAME}}', $deal->cust_name ?? '', DataType::TYPE_STRING);
        $put('{{CUSTOMER_PHONE}}', $deal->no_telp_cust ?? '', DataType::TYPE_STRING);
        $put('{{CUSTOMER_ADDRESS}}', $deal->alamat_lengkap ?? ($deal->cust_address ?? ''), DataType::TYPE_STRING);
        $put('{{DEAL_ID}}', $deal->deals_id, DataType::TYPE_STRING);
        $put('{{DEAL_NAME}}', $deal->deal_name ?? '', DataType::TYPE_STRING);
        $put('{{PAYMENT_TERM}}', $deal->payment_term ?? '', DataType::TYPE_STRING);
        $put('{{DEAL_NOTE}}', $deal->notes ?? '', DataType::TYPE_STRING);

        // --- items: prefer dealItems()->with('item'), fallback to items() pivot ---
        $rows = [];
        // ensure relation loaded
        $deal->loadMissing('dealItems.item');

        foreach ($deal->dealItems as $di) {
            $item = $di->item;
            $name = $item && isset($item->item_name) ? $item->item_name : ($di->item_no ?? 'Item');
            $uom = $item && isset($item->uom) ? $item->uom : '';

            // numeric fields from deals_items
            $qty = (float) ($di->quantity ?? 0);
            $price = (float) ($di->unit_price ?? 0);
            $disc = (float) ($di->discount_percent ?? 0);

            // If unit_price/discount not set on row, fallback to master item price/discount
            if (!$price && $item && isset($item->price)) {
                $price = (float) $item->price;
            }
            if (($di->discount_percent === null || $di->discount_percent === '') && $item && isset($item->disc)) {
                $disc = (float) $item->disc;
            }
            // clamp discount
            if ($disc < 0)
                $disc = 0;
            if ($disc > 100)
                $disc = 100;

            $priceAfter = $price * (1 - $disc / 100);
            $total = $qty * $priceAfter;

            $rows[] = [
                'item_name' => $name,
                'uom' => $uom,
                'quantity' => $qty,
                'unit_price' => $price,
                'discount_pct' => $disc,
                'after_price' => $priceAfter,
                'line_total' => $total,
            ];
        }

        // fallback if there are no dealItems but pivot exists
        if (empty($rows)) {
            $deal->loadMissing('items'); // pivot: quantity, unit_price, discount_percent, line_total
            foreach ($deal->items as $it) {
                $qty = (float) ($it->pivot->quantity ?? 0);
                $price = (float) ($it->pivot->unit_price ?? $it->price ?? 0);
                $disc = (float) (($it->pivot->discount_percent ?? $it->disc ?? 0));
                if ($disc < 0)
                    $disc = 0;
                if ($disc > 100)
                    $disc = 100;
                $priceAfter = $price * (1 - $disc / 100);
                $total = $qty * $priceAfter;

                $rows[] = [
                    'item_name' => $it->item_name ?? 'Item',
                    'uom' => $it->uom ?? '',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount_pct' => $disc,
                    'after_price' => $priceAfter,
                    'line_total' => $total,
                ];
            }
        }

        // if still empty, make a 1-line placeholder (optional)
        if (empty($rows)) {
            $rows[] = [
                'item_name' => 'Item',
                'uom' => '',
                'quantity' => 1,
                'unit_price' => (float) ($deal->deal_size ?? 0),
                'discount_pct' => 0,
                'after_price' => (float) ($deal->deal_size ?? 0),
                'line_total' => (float) ($deal->deal_size ?? 0),
            ];
        }

        // --- write rows under {{ITEM_START}} ---
        $itemAnchor = $findCell('{{ITEM_START}}');
        if ($itemAnchor) {
            $startRow = $itemAnchor->getRow();
            $anchorCol = $itemAnchor->getColumn(); // usually 'A'
            $sheet->setCellValue($anchorCol . $startRow, ''); // clear marker

            $row = $startRow;
            $no = 1;
            $grand = 0.0;

            foreach ($rows as $r) {
                if ($row > $startRow) {
                    $sheet->insertNewRowBefore($row, 1);
                    // copy styles from the previous row
                    $from = "B" . ($row - 1) . ":K" . ($row - 1);
                    $to = "B{$row}:K{$row}";
                    $sheet->duplicateStyle($sheet->getStyle($from), $to);

                    // (optional) keep row height consistent
                    $h = $sheet->getRowDimension($row - 1)->getRowHeight();
                    if ($h > -1) {
                        $sheet->getRowDimension($row)->setRowHeight($h);
                    }
                }

                // A:No, B:Item, C:UoM, D:Qty, E:Unit Price, F:Disc %, G:After Disc, H:Total
                $sheet->setCellValue("B{$row}", $no);
                $sheet->setCellValue("F{$row}", $r['item_name']);
                $sheet->setCellValue("G{$row}", $r['quantity']);
                $sheet->setCellValue("H{$row}", $r['uom']);
                $sheet->setCellValue("I{$row}", $r['unit_price']);
                $sheet->setCellValue("J{$row}", $r['discount_pct']);
                $sheet->setCellValue("K{$row}", $r['line_total']);

                $grand += (float) $r['line_total'];
                $no++;
                $row++;
            }

            // Optional: update {{GRAND_TOTAL}} if present
            $put('{{GRAND_TOTAL}}', $grand, DataType::TYPE_NUMERIC);
        }

        // --- save to public storage ---
        $year = date('Y');
        $month = date('m');
        $dir = storage_path("app/public/quotations/{$year}/{$month}");
        if (!is_dir($dir))
            @mkdir($dir, 0775, true);

        $safeStore = preg_replace('/[^A-Za-z0-9\-]+/', '', strtolower($deal->store_name ?? 'quotation'));
        $fileName = sprintf('%s_%s.xlsx', $safeStore, strtolower($deal->deals_id));
        $fullPath = "{$dir}/{$fileName}";

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($fullPath);

        $publicPath = "storage/quotations/{$year}/{$month}/{$fileName}";
        $url = asset($publicPath);

        return ['path' => $fullPath, 'url' => $url];
    }
}
