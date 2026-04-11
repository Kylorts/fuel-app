<?php

namespace App\Http\Controllers;

use App\Actions\GenerateInvoiceAction;
use App\Actions\GenerateInvoicePdfAction;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Invoice::class);

        $invoices = Invoice::with(['order.company', 'issuer'])
            ->latest('issued_at')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function store(StoreInvoiceRequest $request, Order $order): RedirectResponse
    {
        $invoice = app(GenerateInvoiceAction::class)->execute($order, $request->user());

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} berhasil diterbitkan.");
    }

    public function show(Invoice $invoice): View
    {
        $this->authorize('view', $invoice);

        $invoice->load(['order.company', 'issuer', 'virtualAccounts']);

        return view('invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice, Request $request): Response
    {
        $this->authorize('download', $invoice);

        // Regenerate on-the-fly if the file is missing (e.g. seeded invoices)
        if (! $invoice->pdf_path || ! Storage::exists($invoice->pdf_path)) {
            app(GenerateInvoicePdfAction::class)->execute($invoice);
            $invoice->refresh();
        }

        $pdfContent = Storage::get($invoice->pdf_path);
        $filename   = str_replace('/', '-', $invoice->invoice_number) . '.pdf';

        // ?download=1 forces attachment; default opens inline in browser
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
        ]);
    }
}
