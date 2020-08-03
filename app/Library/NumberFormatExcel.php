<?php

namespace App\Library;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NumberFormatExcel extends NumberFormat
{
    // Pre-defined formats
    const FORMAT_NUMBER_COMMA = '#,##0';
    const FORMAT_CURRENCY_IDR_SIMPLE = '"Rp"#,##0';
    const FORMAT_CURRENCY_IDR = 'Rp#,##0';
    const FORMAT_ACCOUNTING_IDR = '_("Rp"* #,##0_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)';

}
