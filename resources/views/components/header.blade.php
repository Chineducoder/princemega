@php
    $navCategories = $categories ?? collect([]);
@endphp

<header
    class="sticky top-0 z-40 bg-paper transition-shadow duration-200"
    x-data="{
        searchOpen: false,
        mobileMenuOpen: false,
        categoriesOpen: false,
        searchQuery: '',
        init() {
            this.$watch('searchOpen', value => {
                if (value) this.$nextTick(() => this.$refs.searchInput?.focus());
            });
        }
    }"
    @keydown.escape.window="searchOpen = false; mobileMenuOpen = false; categoriesOpen = false"
>
    {{-- Top Utility Strip --}}
    <div class="border-b border-rule bg-mist/60">
        <div class="mx-auto flex max-w-[1400px] items-center justify-between px-4 py-1.5 sm:px-6 md:px-8">
            <div class="flex items-center gap-2 min-w-0">
                <span class="inline-block h-1.5 w-1.5 shrink-0 rounded-full bg-ink animate-pulse"></span>
                <p class="micro text-mid font-medium tracking-wider truncate">PORT HARCOURT HUB • WHOLESALE &amp; RETAIL</p>
            </div>
            <nav class="flex shrink-0 items-center gap-4 sm:gap-6" aria-label="Utility">
                <a href="{{ route('checkout.index') }}" class="link-quiet hidden sm:inline-block">
                    <span class="micro text-mid">Order Status</span>
                </a>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('princemega.whatsapp_number')) }}" target="_blank" rel="noopener" class="link-quiet font-semibold text-ink">
                    <span class="micro">WhatsApp Support ↗</span>
                </a>
            </nav>
        </div>
    </div>

    {{-- Main Navigation Bar --}}
    <div class="border-b border-ink bg-paper relative">
        <div class="mx-auto flex max-w-[1400px] items-center justify-between px-4 py-3 sm:px-6 md:px-8 md:py-0">
            
            {{-- Left: Mobile Hamburger & Brand Logo --}}
            <div class="flex items-center gap-3 sm:gap-4">
                <button
                    type="button"
                    class="p-1 text-ink md:hidden hover:opacity-75 focus:outline-none"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Toggle navigation menu"
                    :aria-expanded="mobileMenuOpen"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <a href="{{ route('shop.index') }}" class="flex items-center gap-2.5 py-2 md:py-3.5 group">
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Prince Mega Agency Logo"
                        class="h-8 w-8 sm:h-9 sm:w-9 object-contain rounded-sm transition-opacity group-hover:opacity-85"
                        width="36"
                        height="36"
                    >
                    <span class="text-base sm:text-lg font-bold uppercase tracking-brand text-ink transition-opacity group-hover:opacity-70">
                        PRINCE<span class="mx-1 text-mid font-light">/</span>MEGA<span class="hidden sm:inline">&nbsp;AGENCY</span>
                    </span>
                </a>
            </div>

            {{-- Center: Streamlined Desktop Links --}}
            <nav class="hidden items-center gap-7 md:flex lg:gap-9" aria-label="Primary">
                <a
                    href="{{ route('shop.index') }}"
                    class="nav-link {{ request()->routeIs('shop.index') && !request()->has('view') ? 'text-ink font-semibold' : 'text-mid hover:text-ink' }}"
                    @if(request()->routeIs('shop.index') && !request()->has('view')) aria-current="page" @endif
                >Shop All</a>

                {{-- Categories Dropdown Menu --}}
                <div
                    class="relative"
                    @mouseenter="categoriesOpen = true"
                    @mouseleave="categoriesOpen = false"
                >
                    <button
                        type="button"
                        class="nav-link flex items-center gap-1.5 transition-colors cursor-pointer {{ request()->routeIs('shop.category') ? 'text-ink font-semibold' : 'text-mid hover:text-ink' }}"
                        @click="categoriesOpen = !categoriesOpen"
                        :aria-expanded="categoriesOpen"
                    >
                        <span>Categories</span>
                        <svg class="h-3 w-3 transition-transform duration-200" :class="categoriesOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Flyout Panel --}}
                    <div
                        x-show="categoriesOpen"
                        x-cloak
                        @click.outside="categoriesOpen = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute left-1/2 -translate-x-1/2 top-full w-80 sm:w-96 border border-ink bg-paper p-5 shadow-xl z-50"
                    >
                        <div class="mb-3 border-b border-rule pb-2 flex items-center justify-between">
                            <span class="micro text-mid font-semibold">DEPARTMENTS</span>
                            <a href="{{ route('shop.index') }}" class="micro text-ink underline hover:text-mid">View All</a>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($navCategories as $cat)
                                <a
                                    href="{{ route('shop.category', $cat->slug) }}"
                                    class="group flex items-center justify-between p-2 text-xs uppercase tracking-wider text-mid transition-colors hover:bg-mist hover:text-ink {{ request()->routeIs('shop.category') && request()->route('slug') === $cat->slug ? 'font-bold text-ink bg-mist/70' : '' }}"
                                >
                                    <span>{{ $cat->nav_label ?? $cat->name }}</span>
                                    <span class="opacity-0 group-hover:opacity-100 transition-opacity text-2xs">→</span>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-3 border-t border-rule flex items-center justify-between text-2xs text-mid">
                            <span>Hub: Port Harcourt</span>
                            <a href="{{ route('shop.brands') }}" class="font-medium text-ink hover:underline">Brands Catalog →</a>
                        </div>
                    </div>
                </div>

                <a
                    href="{{ route('shop.brands') }}"
                    class="nav-link {{ request()->routeIs('shop.brands') ? 'text-ink font-semibold' : 'text-mid hover:text-ink' }}"
                    @if(request()->routeIs('shop.brands')) aria-current="page" @endif
                >Brands</a>
                
                <a
                    href="{{ route('shop.index') }}#wholesale"
                    class="nav-link text-mid hover:text-ink"
                >Wholesale</a>
            </nav>

            {{-- Right: Actions (Pricing Mode, Search, Bag) --}}
            <div class="flex items-center gap-2.5 sm:gap-4">
                {{-- Quick Mode Toggle Pill --}}
                <div class="inline-flex items-center border border-ink bg-white text-2xs uppercase shadow-sm">
                    <button
                        type="button"
                        class="px-2.5 sm:px-3 py-1 tracking-wider transition-colors duration-150 cursor-pointer"
                        :class="mode === 'retail' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium'"
                        @click="
                            if (mode !== 'retail') {
                                mode = 'retail'; persist('mode');
                                fetch('{{ route('shop.mode') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                    body: JSON.stringify({ mode: 'retail' })
                                }).then(() => window.location.reload());
                            }
                        "
                        :aria-pressed="mode === 'retail'"
                    >Retail</button>
                    <button
                        type="button"
                        class="border-l border-ink px-2.5 sm:px-3 py-1 tracking-wider transition-colors duration-150 cursor-pointer"
                        :class="mode === 'wholesale' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium'"
                        @click="
                            if (mode !== 'wholesale') {
                                mode = 'wholesale'; persist('mode');
                                fetch('{{ route('shop.mode') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                    body: JSON.stringify({ mode: 'wholesale' })
                                }).then(() => window.location.reload());
                            }
                        "
                        :aria-pressed="mode === 'wholesale'"
                    >Wholesale</button>
                </div>

                {{-- Search Button --}}
                <button
                    type="button"
                    class="flex items-center gap-1.5 p-1.5 text-xs text-mid transition-colors hover:text-ink hover:bg-mist"
                    @click="searchOpen = true"
                    aria-label="Open search dialog"
                >
                    <svg class="h-4 w-4 stroke-current" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="micro hidden xl:inline">Search</span>
                </button>

                {{-- Cart Trigger --}}
                <button
                    type="button"
                    class="relative inline-flex items-center gap-2 border border-ink bg-paper px-2.5 sm:px-3 py-1.5 transition-all duration-150 hover:bg-ink hover:text-paper"
                    @click="$dispatch('open-drawer')"
                    aria-label="Open cart drawer"
                >
                    <span class="micro font-semibold">BAG</span>
                    <span
                        class="price inline-flex min-w-5 h-4 items-center justify-center bg-ink text-paper px-1 text-2xs font-bold leading-none transition-colors group-hover:bg-paper group-hover:text-ink"
                        x-text="String(cart.count || 0).padStart(2, '0')"
                        aria-live="polite"
                    >00</span>
                </button>
            </div>
        </div>

        {{-- Mobile Drawer / Navigation Menu --}}
        <div
            x-show="mobileMenuOpen"
            x-cloak
            class="border-t border-ink bg-paper md:hidden shadow-lg"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
        >
            <div class="px-5 py-4 space-y-4">
                {{-- Purchasing Mode Switch for Mobile --}}
                <div class="border-b border-rule pb-4">
                    <p class="micro text-mid mb-2">PRICING STRUCTURE</p>
                    <div class="grid grid-cols-2 border border-ink bg-white">
                        <button
                            type="button"
                            class="py-2 text-center micro transition-colors cursor-pointer"
                            :class="mode === 'retail' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium'"
                            @click="
                                if (mode !== 'retail') {
                                    mode = 'retail'; persist('mode');
                                    fetch('{{ route('shop.mode') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                        body: JSON.stringify({ mode: 'retail' })
                                    }).then(() => window.location.reload());
                                }
                            "
                            :aria-pressed="mode === 'retail'"
                        >RETAIL</button>
                        <button
                            type="button"
                            class="border-l border-ink py-2 text-center micro transition-colors cursor-pointer"
                            :class="mode === 'wholesale' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium'"
                            @click="
                                if (mode !== 'wholesale') {
                                    mode = 'wholesale'; persist('mode');
                                    fetch('{{ route('shop.mode') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                        body: JSON.stringify({ mode: 'wholesale' })
                                    }).then(() => window.location.reload());
                                }
                            "
                            :aria-pressed="mode === 'wholesale'"
                        >WHOLESALE</button>
                    </div>
                </div>

                {{-- Mobile Navigation Links --}}
                <nav class="flex flex-col space-y-1" aria-label="Mobile Navigation">
                    <a
                        href="{{ route('shop.index') }}"
                        class="flex items-center justify-between py-2.5 text-sm uppercase tracking-wider {{ request()->routeIs('shop.index') && !request()->has('view') ? 'font-bold text-ink' : 'text-mid' }}"
                    >
                        <span>Shop All Products</span>
                        <span class="micro text-mid">→</span>
                    </a>

                    {{-- Collapsible Categories in Mobile --}}
                    <div x-data="{ mobileCatOpen: true }" class="border-y border-rule py-2">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between py-1 text-sm font-semibold uppercase tracking-wider text-ink"
                            @click="mobileCatOpen = !mobileCatOpen"
                        >
                            <span>Departments</span>
                            <span class="text-xs" x-text="mobileCatOpen ? '−' : '+'">−</span>
                        </button>
                        <div x-show="mobileCatOpen" class="grid grid-cols-2 gap-2 pt-2 pb-1 pl-2">
                            @foreach ($navCategories as $cat)
                                <a
                                    href="{{ route('shop.category', $cat->slug) }}"
                                    class="py-1.5 text-xs uppercase tracking-wider {{ request()->routeIs('shop.category') && request()->route('slug') === $cat->slug ? 'font-bold text-ink' : 'text-mid hover:text-ink' }}"
                                >
                                    {{ $cat->nav_label ?? $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a
                        href="{{ route('shop.brands') }}"
                        class="flex items-center justify-between py-2.5 text-sm uppercase tracking-wider {{ request()->routeIs('shop.brands') ? 'font-bold text-ink' : 'text-mid' }}"
                    >
                        <span>Brands Catalog</span>
                        <span class="micro text-mid">→</span>
                    </a>
                    <a
                        href="{{ route('shop.index') }}#wholesale"
                        class="flex items-center justify-between py-2.5 text-sm uppercase tracking-wider text-mid"
                    >
                        <span>Wholesale Tiers</span>
                        <span class="micro text-mid">→</span>
                    </a>
                    <a
                        href="{{ route('checkout.index') }}"
                        class="flex items-center justify-between border-t border-rule py-2.5 text-sm uppercase tracking-wider text-mid"
                    >
                        <span>Order Status</span>
                        <span class="micro text-mid">→</span>
                    </a>
                </nav>

                {{-- Contact & WhatsApp in mobile menu --}}
                <div class="pt-2">
                    <a
                        href="https://wa.me/{{ preg_replace('/\D+/', '', config('princemega.whatsapp_number')) }}"
                        target="_blank"
                        rel="noopener"
                        class="btn-black w-full text-center"
                    >
                        DIRECT WHATSAPP DESK ↗
                    </a>
                </div>
            </div>
        </div>

        {{-- Interactive Search Modal Overlay --}}
        <div
            x-show="searchOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-start justify-center bg-paper/95 p-4 pt-20 sm:pt-28 backdrop-blur-md"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            @click.self="searchOpen = false"
        >
            <div class="w-full max-w-3xl border border-ink bg-paper p-6 sm:p-10 shadow-2xl">
                <div class="flex items-center justify-between border-b-2 border-ink pb-4">
                    <div class="flex flex-1 items-center gap-3">
                        <svg class="h-6 w-6 text-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="search"
                            x-ref="searchInput"
                            x-model="searchQuery"
                            placeholder="SEARCH PRODUCTS, BRANDS, SHADES..."
                            class="w-full bg-transparent text-lg sm:text-2xl font-bold uppercase tracking-tight placeholder:text-mid/40 focus:outline-none"
                            aria-label="Search catalogue"
                        >
                    </div>
                    <button
                        type="button"
                        class="micro ml-4 border border-ink px-3 py-1.5 font-bold hover:bg-ink hover:text-paper transition-colors"
                        @click="searchOpen = false"
                    >
                        ESC / CLOSE
                    </button>
                </div>

                {{-- Quick jump / recommendations tags --}}
                <div class="mt-8">
                    <p class="micro text-mid mb-3">POPULAR DEPARTMENTS &amp; ESSENTIALS</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($navCategories as $cat)
                            <a
                                href="{{ route('shop.category', $cat->slug) }}"
                                class="micro border border-rule px-3 py-1.5 transition-colors hover:border-ink hover:bg-mist"
                                @click="searchOpen = false"
                            >
                                {{ strtoupper($cat->name) }}
                            </a>
                        @endforeach
                        <a
                            href="{{ route('shop.brands') }}"
                            class="micro border border-rule px-3 py-1.5 transition-colors hover:border-ink hover:bg-mist"
                            @click="searchOpen = false"
                        >
                            ALL BRANDS
                        </a>
                    </div>
                </div>

                <div class="mt-8 border-t border-rule pt-4 flex items-center justify-between text-2xs text-mid">
                    <span>Authentic guaranteed cosmetics</span>
                    <span>Port Harcourt Express Hub</span>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Announcement bar --}}
@include('components.announcement-bar')
