@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 px-7 py-6 text-white shadow-md">
        <p class="text-blue-100 text-sm">Selamat datang kembali,</p>
        <h1 class="text-2xl font-bold mt-0.5">{{ $user->name }}</h1>
        <p class="mt-1 text-sm text-blue-200">
            {{ ucwords(str_replace('_', ' ', $user->role)) }}
            @if($user->company)
                &mdash; {{ $user->company->name }}
            @endif
        </p>
    </div>

    {{-- Role-based quick-access panels --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Admin Penjualan: Pending invoices --}}
        @if($user->isAdminPenjualan())
            <a href="{{ route('orders.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="shrink-0 rounded-lg bg-blue-100 p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition">Daftar Pesanan</p>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola & terbitkan tagihan untuk pesanan yang disetujui</p>
                </div>
            </a>

            <a href="{{ route('invoices.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="shrink-0 rounded-lg bg-green-100 p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-green-700 transition">Invoice Terbit</p>
                    <p class="text-sm text-gray-500 mt-0.5">Lihat semua tagihan yang telah diterbitkan</p>
                </div>
            </a>
        @endif

        {{-- Buyer roles: Their orders --}}
        @if(! $user->isAdminPenjualan())
            <a href="{{ route('orders.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="shrink-0 rounded-lg bg-orange-100 p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-orange-700 transition">Pesanan Saya</p>
                    <p class="text-sm text-gray-500 mt-0.5">Pantau status pesanan bahan bakar perusahaan</p>
                </div>
            </a>

            <a href="{{ route('invoices.index') }}"
               class="group flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="shrink-0 rounded-lg bg-purple-100 p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 group-hover:text-purple-700 transition">Tagihan</p>
                    <p class="text-sm text-gray-500 mt-0.5">Lihat tagihan & status pembayaran</p>
                </div>
            </a>
        @endif

    </div>

    {{-- US 2.1 Hint for admin --}}
    @if($user->isAdminPenjualan())
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-5 py-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">US 2.1 — Penerbitan Tagihan Otomatis</p>
                    <p class="text-sm text-blue-700 mt-0.5">
                        Buka <a href="{{ route('orders.index') }}" class="underline font-medium">Daftar Pesanan</a>,
                        pilih pesanan berstatus <span class="font-semibold">Disetujui</span>,
                        lalu klik <strong>"Terbitkan Tagihan"</strong> untuk memproses invoice otomatis.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
