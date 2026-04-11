<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin_penjualan', 'pembeli', 'staf_pembeli', 'manajer_pembeli']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->isAdminPenjualan()) {
            return true;
        }

        // Buyer roles can only see invoices for their company's orders
        return $invoice->order->company_id === $user->company_id;
    }

    public function create(User $user, Order $order): bool
    {
        // Only admin_penjualan can issue invoices, and only for approved orders without existing invoice
        return $user->isAdminPenjualan()
            && $order->isApproved()
            && ! $order->hasInvoice();
    }

    public function download(User $user, Invoice $invoice): bool
    {
        return $this->view($user, $invoice);
    }
}
