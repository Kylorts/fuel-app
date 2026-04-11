<?php

namespace App\Http\Controllers;

use App\Actions\GenerateInvoiceAction;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function download(Invoice $invoice): StreamedResponse
    {
        $this->authorize('download', $invoice);

        abort_unless($invoice->pdf_path && Storage::exists($invoice->pdf_path), 404);

        return Storage::download($invoice->pdf_path, "{$invoice->invoice_number}.pdf");
    }
}
