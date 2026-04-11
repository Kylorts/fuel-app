@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Bahan Bakar</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            @if(auth()->user()->isAdminPenjualan())
                Semua pesanan masuk dari klien
            @else
                Pesanan dari {{ auth()->user()->company->name }}
            @endif
        </p>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                    @if(auth()->user()->isAdminPenjualan())
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Klien</th>
                    @endif
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis BBM</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Volume (L)</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Dibuat</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono font-medium text-blue-700">
                            {{ $order->order_number }}
                        </td>
                        @if(auth()->user()->isAdminPenjualan())
                            <td class="px-5 py-3 text-gray-600">{{ $order->company->name }}</td>
                        @endif
                        <td class="px-5 py-3 text-gray-700">{{ $order->fuel_type }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">
                            {{ number_format($order->volume_liters, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $colorMap = [
                                    'draft'            => 'bg-gray-100 text-gray-600',
                                    'pending_approval' => 'bg-yellow-100 text-yellow-700',
                                    'approved'         => 'bg-blue-100 text-blue-700',
                                    'waiting_payment'  => 'bg-orange-100 text-orange-700',
                                    'paid'             => 'bg-green-100 text-green-700',
                                    'cancelled'        => 'bg-red-100 text-red-600',
                                    'expired'          => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $colorMap[$order->status->value] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('orders.show', $order) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium text-xs transition">
                                Detail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->links() }}</div>

</div>
@endsection
