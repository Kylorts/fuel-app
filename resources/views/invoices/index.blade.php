@extends('layouts.app')

@section('title', 'Daftar Invoice')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar tagihan pesanan bahan bakar</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">No. Invoice</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Pesanan</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Klien</th>
                    <th class="px-5 py-3 text-right font-semibold text-gray-600 uppercase tracking-wider text-xs">Total (Rp)</th>
                    <th class="px-5 py-3 text-center font-semibold text-gray-600 uppercase tracking-wider text-xs">Status</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Diterbitkan</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono font-medium text-blue-700">
                            {{ $invoice->invoice_number }}
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $invoice->order->order_number }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $invoice->order->company->name }}
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">
                            {{ number_format($invoice->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status->badgeClass() }}">
                                {{ $invoice->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">
                            {{ $invoice->issued_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('invoices.show', $invoice) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium text-xs transition">
                                Detail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            Belum ada invoice yang diterbitkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>
        {{ $invoices->links() }}
    </div>

</div>
@endsection
