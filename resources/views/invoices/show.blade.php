@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('invoices.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
        ← Kembali ke Daftar Invoice
    </a>

    {{-- Invoice Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Invoice Header --}}
        <div class="bg-blue-700 px-8 py-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium uppercase tracking-wider">Invoice</p>
                    <p class="text-3xl font-bold mt-1 font-mono tracking-tight">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        {{ $invoice->status->badgeClass() }} bg-opacity-20">
                        {{ $invoice->status->label() }}
                    </span>
                    <p class="text-blue-200 text-xs mt-2">
                        Diterbitkan: {{ $invoice->issued_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 space-y-6">

            {{-- Parties --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Dari</p>
                    <p class="font-bold text-gray-900">Depo Bahan Bakar</p>
                    <p class="text-sm text-gray-500">Admin: {{ $invoice->issuer->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Kepada</p>
                    <p class="font-bold text-gray-900">{{ $invoice->order->company->name }}</p>
                    <p class="text-sm text-gray-500">{{ $invoice->order->company->email }}</p>
                    @if($invoice->order->company->npwp)
                        <p class="text-sm text-gray-500">NPWP: {{ $invoice->order->company->npwp }}</p>
                    @endif
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Order Detail --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Detail Pesanan</p>
                <div class="rounded-lg overflow-hidden border border-gray-100">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Volume</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Harga/Liter</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3 text-gray-800">
                                    {{ $invoice->order->fuel_type }}
                                    <span class="block text-xs text-gray-400">No. Pesanan: {{ $invoice->order->order_number }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">
                                    {{ number_format($invoice->order->volume_liters, 0, ',', '.') }} L
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">
                                    Rp {{ number_format($invoice->order->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">
                                    Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Totals --}}
            <div class="flex justify-end">
                <div class="w-72 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>PPN {{ number_format($invoice->ppn_rate * 100, 0) }}%</span>
                        <span>Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between font-bold text-gray-900 text-base">
                        <span>Total</span>
                        <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Virtual Account (if exists) --}}
            @if ($invoice->activeVirtualAccount())
                @php $va = $invoice->activeVirtualAccount(); @endphp
                <div class="rounded-lg bg-amber-50 border border-amber-200 px-5 py-4">
                    <p class="text-xs text-amber-700 font-semibold uppercase tracking-wider mb-2">Nomor Virtual Account</p>
                    <p class="text-2xl font-mono font-bold text-amber-900 tracking-widest">{{ $va->va_number }}</p>
                    <p class="text-xs text-amber-600 mt-1">
                        Bank: {{ $va->bank_code }}
                        &nbsp;|&nbsp;
                        Berlaku hingga: {{ $va->expires_at->format('d M Y, H:i') }}
                        &nbsp;|&nbsp;
                        <span class="{{ $va->isExpired() ? 'text-red-600 font-semibold' : 'text-green-700 font-semibold' }}">
                            {{ $va->isExpired() ? 'Kedaluwarsa' : 'Aktif' }}
                        </span>
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                @can('download', $invoice)
                    {{-- View inline in browser tab --}}
                    <a href="{{ route('invoices.download', $invoice) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat PDF
                    </a>

                    {{-- Force download --}}
                    <a href="{{ route('invoices.download', $invoice) }}?download=1"
                       class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-50 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Unduh PDF
                    </a>

                    {{-- Dead branch kept for compatibility but now unreachable
                         (PDF is always generated on demand in download()) --}}
                    @if(false)
                        <button disabled
                                class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-400 cursor-not-allowed">
                            PDF belum tersedia
                        </button>
                    @endif
                @endcan

                @if ($invoice->status->value === 'issued' && ! $invoice->activeVirtualAccount())
                    @can('create', [\App\Models\VirtualAccount::class])
                        <a href="{{ route('virtual-accounts.store', $invoice) }}"
                           class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm">
                            Bayar via VA
                        </a>
                    @endcan
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
