<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $orders = Order::with(['company', 'creator', 'invoice'])
            ->when($user->isAdminPenjualan(), fn ($q) => $q)                     // sees all
            ->when(! $user->isAdminPenjualan(), fn ($q) => $q->where('company_id', $user->company_id))
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        // Non-admin sees only their company's orders
        if (! auth()->user()->isAdminPenjualan()) {
            abort_unless($order->company_id === auth()->user()->company_id, 403);
        }

        $order->load(['company', 'creator', 'approver', 'invoice']);

        return view('orders.show', compact('order'));
    }
}
