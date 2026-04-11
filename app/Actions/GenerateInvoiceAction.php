<?php

namespace App\Actions;

use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceAction
{
    private const PPN_RATE = 0.11;

    public function execute(Order $order, User $issuedBy): Invoice
    {
        // 1. Persist invoice + status change atomically
        $invoice = DB::transaction(function () use ($order, $issuedBy) {
            $subtotal  = $order->subtotal();
            $ppnAmount = round($subtotal * self::PPN_RATE, 2);
            $total     = round($subtotal + $ppnAmount, 2);

            $invoice = Invoice::create([
                'invoice_number' => $this->buildInvoiceNumber(),
                'order_id'       => $order->id,
                'issued_by'      => $issuedBy->id,
                'subtotal'       => $subtotal,
                'ppn_rate'       => self::PPN_RATE,
                'ppn_amount'     => $ppnAmount,
                'total_amount'   => $total,
                'status'         => InvoiceStatus::ISSUED,
                'issued_at'      => now(),
            ]);

            $order->update(['status' => OrderStatus::WAITING_PAYMENT]);

            return $invoice;
        });

        // 2. Generate PDF after the transaction commits (outside tx so a PDF failure
        //    does not roll back the invoice record)
        app(GenerateInvoicePdfAction::class)->execute($invoice);

        return $invoice;
    }

    private function buildInvoiceNumber(): string
    {
        $prefix    = 'INV';
        $yearMonth = now()->format('Ym');
        $sequence  = Invoice::whereYear('issued_at', now()->year)
            ->whereMonth('issued_at', now()->month)
            ->count() + 1;

        return sprintf('%s/%s/%04d', $prefix, $yearMonth, $sequence);
    }
}
