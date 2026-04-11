@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
@php
    $user = auth()->user();
    $canIssueInvoice = $user->can('create', [\App\Models\Invoice::class, $order]);
    $colorMap = [
        'draft'            => 'bg-gray-100 text-gray-600',
        'pending_approval' => 'bg-yellow-100 text-yellow-800',
        'approved'         => 'bg-blue-100 text-blue-800',
        'waiting_payment'  => 'bg-orange-100 text-orange-800',
        'paid'             => 'bg-green-100 text-green-800',
        'cancelled'        => 'bg-red-100 text-red-700',
        'expired'          => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('orders.index') }}" class="hover:text-gray-800 transition">Pesanan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $order->order_number }}</span>
    </div>

    {{-- Order Detail Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-start justify-between px-7 py-5 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">No. Pesanan</p>
                <p class="text-xl font-bold font-mono text-blue-700 mt-0.5">{{ $order->order_number }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                {{ $colorMap[$order->status->value] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $order->status->label() }}
            </span>
        </div>

        <div class="px-7 py-6 space-y-5">

            {{-- Two-column info grid --}}
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Klien</p>
                    <p class="text-gray-900 font-medium">{{ $order->company->name }}</p>
                    @if($order->company->npwp)
                        <p class="text-gray-500 text-xs">NPWP: {{ $order->company->npwp }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Dibuat oleh</p>
                    <p class="text-gray-900 font-medium">{{ $order->creator->name }}</p>
                    <p class="text-gray-500 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Jenis Bahan Bakar</p>
                    <p class="text-gray-900 font-medium">{{ $order->fuel_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Volume</p>
                    <p class="text-gray-900 font-medium">{{ number_format($order->volume_liters, 0, ',', '.') }} Liter</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Harga per Liter</p>
                    <p class="text-gray-900 font-medium">Rp {{ number_format($order->unit_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Subtotal</p>
                    <p class="text-gray-900 font-semibold text-base">Rp {{ number_format($order->subtotal(), 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Lokasi Pengiriman</p>
                    <p class="text-gray-900 font-medium">{{ $order->delivery_location }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Jadwal Pengiriman</p>
                    <p class="text-gray-900 font-medium">{{ $order->scheduled_at->format('d M Y, H:i') }}</p>
                </div>
                @if($order->approver)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Disetujui oleh</p>
                        <p class="text-gray-900 font-medium">{{ $order->approver->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->approved_at?->format('d M Y, H:i') }}</p>
                    </div>
                @endif
                @if($order->rejection_reason)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-0.5">Alasan Penolakan</p>
                        <p class="text-red-600">{{ $order->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <hr class="border-gray-100">

            {{-- ── US 2.1 CORE ACTION AREA ────────────────────────────────── --}}
            <div>
                @if($order->invoice)
                    {{-- Invoice already issued --}}
                    <div class="flex items-center gap-3 rounded-lg bg-green-50 border border-green-200 px-5 py-4">
                        <svg class="w-5 h-5 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-green-800">Invoice sudah diterbitkan</p>
                            <p class="text-xs text-green-600">No: {{ $order->invoice->invoice_number }}</p>
                        </div>
                        <a href="{{ route('invoices.show', $order->invoice) }}"
                           class="text-sm font-semibold text-green-700 hover:text-green-900 transition">
                            Lihat Invoice →
                        </a>
                    </div>

                @elseif($canIssueInvoice)
                    {{-- ✅ APPROVED + no invoice yet → show "Terbitkan Tagihan" --}}
                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-5 py-4">
                        <div class="flex items-start gap-3 mb-4">
                            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-800">Pesanan siap ditagih</p>
                                <p class="text-xs text-blue-600 mt-0.5">
                                    Invoice akan dibuat otomatis dengan subtotal
                                    <strong>Rp {{ number_format($order->subtotal(), 0, ',', '.') }}</strong>
                                    + PPN 11% =
                                    <strong>Rp {{ number_format($order->subtotal() * 1.11, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('invoices.store', $order) }}"
                              onsubmit="return confirm('Terbitkan invoice untuk pesanan {{ $order->order_number }}?\nStatus pesanan akan berubah menjadi Menunggu Pembayaran.')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5
                                           text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800
                                           transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Terbitkan Tagihan
                            </button>
                        </form>
                    </div>

                @elseif($user->isAdminPenjualan() && ! $order->isApproved())
                    {{-- ❌ Admin but order not approved → show disabled button with reason --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-5 py-4">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Tagihan belum dapat diterbitkan</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Status pesanan harus <strong>Disetujui</strong> sebelum tagihan dapat dibuat.
                                    Status saat ini: <strong>{{ $order->status->label() }}</strong>
                                </p>
                            </div>
                        </div>
                        <button disabled
                                class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-5 py-2.5
                                       text-sm font-semibold text-gray-400 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Terbitkan Tagihan
                        </button>
                    </div>
                @endif
            </div>
            {{-- ── END US 2.1 ACTION AREA ──────────────────────────────────── --}}

        </div>
    </div>

</div>
@endsection
