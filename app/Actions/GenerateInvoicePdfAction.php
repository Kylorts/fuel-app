<?php

namespace App\Actions;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoicePdfAction
{
    public function execute(Invoice $invoice): string
    {
        $invoice->loadMissing(['order.company', 'issuer']);

        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice])
            ->setPaper('a4', 'portrait');

        $filename = 'invoices/' . str_replace('/', '-', $invoice->invoice_number) . '.pdf';

        Storage::put($filename, $pdf->output());

        $invoice->update(['pdf_path' => $filename]);

        return $filename;
    }
}
