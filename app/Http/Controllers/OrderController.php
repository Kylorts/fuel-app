<?php

namespace App\Http\Controllers;

use App\Actions\ApproveOrderAction;
use App\Enums\OrderStatus;
use App\Http\Requests\RejectOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $orders = Order::with(['company', 'creator', 'invoice'])
            ->when($user->isAdminPenjualan(), fn ($q) => $q)
            ->when(! $user->isAdminPenjualan(), fn ($q) => $q->where('company_id', $user->company_id))
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $this->authorize('create', Order::class);

        return view('orders.create');
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $order = Order::create([
            'order_number'      => $this->buildOrderNumber(),
            'company_id'        => $user->company_id,
            'created_by'        => $user->id,
            'fuel_type'         => $request->fuel_type,
            'volume_liters'     => $request->volume_liters,
            'unit_price'        => $request->unit_price,
            'delivery_location' => $request->delivery_location,
            'scheduled_at'      => $request->scheduled_at,
            'status'            => OrderStatus::PENDING_APPROVAL,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil diajukan dan menunggu persetujuan manajer.');
    }

    public function show(Order $order): View
    {
        if (! auth()->user()->isAdminPenjualan()) {
            abort_unless($order->company_id === auth()->user()->company_id, 403);
        }

        $order->load(['company', 'creator', 'approver', 'invoice']);

        return view('orders.show', compact('order'));
    }

    public function approve(Order $order): RedirectResponse
    {
        $this->authorize('approve', $order);

        app(ApproveOrderAction::class)->execute($order, auth()->user());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil disetujui dan diteruskan ke Admin Depo.');
    }

    public function reject(RejectOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update([
            'status'           => OrderStatus::REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('error', 'Pesanan telah ditolak.');
    }

    private function buildOrderNumber(): string
    {
        $prefix = 'ORD/' . now()->format('Ym') . '/';

        $last = Order::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('order_number')
            ->value('order_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
