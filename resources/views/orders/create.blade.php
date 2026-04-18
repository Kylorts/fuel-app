@extends('layouts.app')

@section('title', 'Buat Pesanan Bahan Bakar')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('orders.index') }}" class="hover:text-gray-800 transition">Pesanan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Buat Pesanan Baru</span>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-7 py-5 border-b border-gray-100">
            <h1 class="text-lg font-bold text-gray-900">Ajukan Kuota Bahan Bakar</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi form di bawah untuk mengajukan permintaan bahan bakar ke manajer.</p>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" class="px-7 py-6 space-y-5">
            @csrf

            {{-- Fuel Type --}}
            <div>
                <label for="fuel_type" class="block text-sm font-semibold text-gray-700 mb-1">
                    Jenis Bahan Bakar <span class="text-red-500">*</span>
                </label>
                <select id="fuel_type"
                        name="fuel_type"
                        class="w-full rounded-lg border @error('fuel_type') border-red-400 bg-red-50 @else border-gray-300 @enderror
                               px-4 py-2.5 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled {{ old('fuel_type') ? '' : 'selected' }}>-- Pilih jenis BBM --</option>
                    @foreach(['Solar', 'Solar B30', 'Solar Industri', 'Pertamax', 'Pertalite'] as $ft)
                        <option value="{{ $ft }}" {{ old('fuel_type') === $ft ? 'selected' : '' }}>{{ $ft }}</option>
                    @endforeach
                </select>
                @error('fuel_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Volume --}}
            <div>
                <label for="volume_liters" class="block text-sm font-semibold text-gray-700 mb-1">
                    Jumlah Liter <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text"
                           inputmode="numeric"
                           id="volume_liters"
                           name="volume_liters"
                           value="{{ old('volume_liters') }}"
                           placeholder="Contoh: 10000"
                           autocomplete="off"
                           onkeydown="return allowNumbersOnly(event)"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full rounded-lg border @error('volume_liters') border-red-400 bg-red-50 @else border-gray-300 @enderror
                                  px-4 py-2.5 pr-12 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none">L</span>
                </div>
                @error('volume_liters')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unit Price --}}
            <div>
                <label for="unit_price" class="block text-sm font-semibold text-gray-700 mb-1">
                    Harga per Liter <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium pointer-events-none">Rp</span>
                    <input type="text"
                           inputmode="numeric"
                           id="unit_price"
                           name="unit_price"
                           value="{{ old('unit_price') }}"
                           placeholder="Contoh: 11500"
                           autocomplete="off"
                           onkeydown="return allowNumbersOnly(event)"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full rounded-lg border @error('unit_price') border-red-400 bg-red-50 @else border-gray-300 @enderror
                                  pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @error('unit_price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Delivery Location --}}
            <div>
                <label for="delivery_location" class="block text-sm font-semibold text-gray-700 mb-1">
                    Tujuan Pengiriman (Lokasi Pabrik) <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="delivery_location"
                       name="delivery_location"
                       value="{{ old('delivery_location') }}"
                       placeholder="Contoh: Kawasan Industri MM2100, Bekasi"
                       class="w-full rounded-lg border @error('delivery_location') border-red-400 bg-red-50 @else border-gray-300 @enderror
                              px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('delivery_location')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Scheduled Date --}}
            <div>
                <label for="scheduled_at" class="block text-sm font-semibold text-gray-700 mb-1">
                    Jadwal Pengiriman <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local"
                       id="scheduled_at"
                       name="scheduled_at"
                       value="{{ old('scheduled_at') }}"
                       min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                       class="w-full rounded-lg border @error('scheduled_at') border-red-400 bg-red-50 @else border-gray-300 @enderror
                              px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('scheduled_at')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info box --}}
            <div class="rounded-lg bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-700">
                Pesanan akan disimpan dengan status <strong>Menunggu Persetujuan</strong> dan dikirim ke manajer untuk disetujui.
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5
                               text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                    Ajukan Pesanan
                </button>
                <a href="{{ route('orders.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold
                          text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>

<script>
function allowNumbersOnly(e) {
    // Allow: backspace, delete, tab, escape, enter
    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
    if (allowedKeys.includes(e.key)) return true;
    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
    if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) return true;
    // Block anything that's not a digit
    if (!/^\d$/.test(e.key)) {
        e.preventDefault();
        return false;
    }
    return true;
}
</script>
@endsection
