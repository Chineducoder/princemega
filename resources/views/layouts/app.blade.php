<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-initial-mode="{{ $mode ?? 'retail' }}"
      data-initial-hub="{{ $hub?->id ?? '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Prince Mega Agency — Nigeria’s Cosmetics Giant')</title>
    <meta name="description" content="@yield('description', 'Authentic cosmetics, skincare and fragrance for retail customers, resellers and wholesale vendors across Nigeria.')">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans" x-data="shop()">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:bg-ink focus:px-4 focus:py-2 focus:text-paper">Skip to content</a>

    @include('components.header')

    <main id="main">
        @yield('content')
    </main>

    @include('components.footer')

    @include('components.cart-drawer')

    {{-- Minimal monochrome toast --}}
    <div class="pointer-events-none fixed bottom-6 left-1/2 z-[80] -translate-x-1/2" aria-live="polite">
        <template x-for="t in toasts" :key="t.id">
            <div class="bg-ink text-paper text-2xs uppercase tracking-label px-4 py-3 mb-2 animate-toast-in" x-text="t.message"></div>
        </template>
    </div>

    @stack('scripts')
</body>
</html>
