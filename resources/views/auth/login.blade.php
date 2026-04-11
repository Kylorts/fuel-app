<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — FuelApp B2B</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h2l1 2h13l1-2h2M5 10V7a2 2 0 012-2h10a2 2 0 012 2v3M9 21h6M12 17v4"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">FuelApp B2B</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem Supply Chain Bahan Bakar</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
            <p class="text-sm text-gray-500 mb-6">Masuk ke akun Anda untuk melanjutkan</p>

            {{-- Session Error --}}
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nama@perusahaan.com"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kata Sandi
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border px-4 py-2.5 text-sm shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white
                               hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                    Masuk
                </button>
            </form>

            {{-- Demo Accounts hint --}}
            <div class="mt-6 rounded-lg bg-gray-50 border border-gray-100 p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Akun Demo</p>
                <div class="space-y-1 text-xs text-gray-600 font-mono">
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-semibold">admin@fuelapp.com</span>
                        <span class="text-gray-400">Admin Penjualan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-green-700 font-semibold">manajer@ptabc.com</span>
                        <span class="text-gray-400">Manajer Pembeli</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-orange-700 font-semibold">staf@ptabc.com</span>
                        <span class="text-gray-400">Staf Pembeli</span>
                    </div>
                    <p class="text-gray-400 mt-1">Kata sandi semua akun: <strong class="text-gray-600">password</strong></p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
