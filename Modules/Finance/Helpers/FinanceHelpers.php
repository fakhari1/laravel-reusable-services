<?php

namespace Modules\Finance\Helpers;

class FinanceHelpers
{
    public static function calculateInvoiceFinalAmount($invoice, $discount_amount = 0, $tax_amount = 0)
    {
        $totalCount = 0;
        $totalAmount = 0;
        $finalAmount = 0;

        foreach ($invoice['invoice_items'] as $key => $invoiceItem) {
            $totalCount += $invoiceItem['count'];
            $totalAmount += $invoiceItem['unit_price'] * $invoiceItem['count'];
        }

        $finalAmount = $totalAmount + $tax_amount;
        $finalAmount -= $discount_amount;

        return [
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'final_amount' => $finalAmount
        ];
    }
}
