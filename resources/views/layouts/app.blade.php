<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EduGate') — {{ __('Study in Egypt') }}</title>
    <meta name="description" content="{{ __('Helping Arab and Gulf students study at accredited Egyptian universities.') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    @include('partials.nav')

    @if (session('lead_success'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="fixed inset-x-0 top-20 z-50 mx-auto w-[min(92%,640px)]">
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-lg">
                <div class="text-2xl">✅</div>
                <div class="flex-1">
                    <p class="font-semibold text-emerald-900">{{ session('lead_success')['message'] }}</p>
                    <p class="mt-1 text-sm text-emerald-700">
                        {{ __('Your application number is :number', ['number' => session('lead_success')['application_number']]) }}
                    </p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">✕</button>
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
