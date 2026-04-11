<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fuel Supply Chain') — FuelApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="text-xl font-bold text-blue-700 tracking-tight">
                    FuelApp B2B
                </a>
                {{-- Nav Links --}}
                @auth
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('orders.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Pesanan
                    </a>
                    <a href="{{ route('invoices.index') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('invoices.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Invoice
                    </a>
                </div>
                @endauth

                <div class="flex items-center gap-3">
                    @auth
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-700 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:text-red-600 hover:border-red-200 transition">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if (session('success'))
            <div class="mb-4 flex items-center gap-3 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

</body>
</html>
