@extends('layouts.shop')

@section('title', 'Prince Mega Agency — Nigeria’s Cosmetics Giant')
@section('description', 'Authentic cosmetics, skincare and fragrance — available for retail customers, resellers and wholesale vendors. Free Port Harcourt delivery.')

@section('shop-content')
    {{-- ============ HERO ============ --}}
    <section class="border-b border-ink bg-paper relative overflow-hidden">
        <div class="mx-auto max-w-[1400px]">
            <div class="grid items-stretch lg:grid-cols-12 min-h-[560px] lg:min-h-[620px]">
                
                {{-- Left Content Column --}}
                <div class="flex flex-col justify-between p-6 sm:p-10 md:p-12 lg:col-span-6 lg:p-14 border-b lg:border-b-0 lg:border-r border-ink">
                    <div>
                        {{-- Main Editorial Headline --}}
                        <h1 class="display-xl text-4xl sm:text-6xl md:text-7xl lg:text-[5.2rem] xl:text-[6rem] tracking-tight text-ink font-bold leading-[0.92]">
                            BEAUTY.<br>AT SCALE.
                        </h1>

                        <p class="mt-6 max-w-lg text-base sm:text-lg text-mid leading-relaxed">
                            The premier agency for authentic skincare, makeup, and fragrance. Supplying retail customers, resellers, and wholesale beauty businesses nationwide from Port Harcourt.
                        </p>

                        {{-- Action Buttons --}}
                        <div class="mt-8 flex flex-wrap items-center gap-3.5 sm:gap-4">
                            <a href="#catalogue" class="btn-black">
                                EXPLORE CATALOGUE <span aria-hidden="true" class="ml-1">→</span>
                            </a>
                            <a href="#wholesale" class="btn-outline">
                                WHOLESALE TIERS
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Visual Composition --}}
                <div class="relative lg:col-span-6 flex flex-col justify-between bg-mist">
                    <div class="relative h-[360px] sm:h-[440px] lg:h-full w-full overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1600&h=1100&q=80"
                            alt="Cosmetics campaign — foundations, palettes and beauty tools arranged editorially"
                            class="h-full w-full object-cover grayscale-[10%] transition-transform duration-700 hover:scale-105"
                            width="1600"
                            height="1100"
                            fetchpriority="high"
                            decoding="async"
                        >
                        
                        {{-- Top Floating Overlay Badge --}}
                        <div class="absolute top-4 left-4 sm:top-6 sm:left-6 border border-ink bg-paper/95 px-3 py-2 backdrop-blur-sm shadow-md">
                            <p class="micro font-bold text-ink">CAMPAIGN SS26</p>
                            <p class="text-2xs text-mid">AUTHENTIC SKINCARE &amp; COSMETICS</p>
                        </div>

                        {{-- Bottom Floating Hub Status Pill --}}
                        <div class="absolute bottom-4 right-4 sm:bottom-6 sm:right-6 border border-ink bg-ink text-paper px-4 py-2.5 shadow-lg">
                            <p class="micro font-bold flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-paper animate-ping"></span>
                                READY FOR PICKUP &amp; DELIVERY
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============ MODE + HUB ENGINE ============ --}}
    <section class="border-b border-ink bg-paper">
        <div class="mx-auto flex max-w-[1400px] flex-wrap items-center justify-between gap-4 px-5 py-5 md:px-8">
            <div class="flex flex-wrap items-center gap-6">
                <span class="micro text-mid">PURCHASE AS</span>
                @include('components.mode-switcher')
            </div>
            <p class="micro text-mid" x-text="mode === 'wholesale' ? 'WHOLESALE TIERS ACTIVE — MOQ APPLIES' : 'RETAIL PRICING — NO MOQ'">RETAIL PRICING — NO MOQ</p>
        </div>
    </section>

    {{-- ============ CATEGORY DEPARTMENTS SHOWCASE ============ --}}
    @php
        $departmentData = [
            'skincare' => [
                'title' => 'SKINCARE',
                'image' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '01 / ACTIVE CLINICAL',
                'desc' => 'Toners, Serums, Sunscreens & Barrier Care',
                'cta' => 'EXPLORE',
            ],
            'makeup' => [
                'title' => 'MAKEUP',
                'image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '02 / COMPLEXION',
                'desc' => 'Foundations, Powders, Concealers & Lips',
                'cta' => 'EXPLORE',
            ],
            'fragrance' => [
                'title' => 'FRAGRANCE',
                'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '03 / EAU DE PARFUM',
                'desc' => 'Designer Scents, Flacons & Daily Mists',
                'cta' => 'EXPLORE',
            ],
            'hair' => [
                'title' => 'HAIR CARE',
                'image' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '04 / TEXTURE & CARE',
                'desc' => 'Growth Oils, Shampoos & Deep Butters',
                'cta' => 'EXPLORE',
            ],
            'body' => [
                'title' => 'BODY CARE',
                'image' => 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '05 / HYDRATION',
                'desc' => 'Moisturizing Milks, Creams & Scrubs',
                'cta' => 'EXPLORE',
            ],
            'tools' => [
                'title' => 'BEAUTY TOOLS',
                'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '06 / PRO APPARATUS',
                'desc' => 'Precision Brushes, Blenders & Sponges',
                'cta' => 'EXPLORE',
            ],
            'wholesale' => [
                'title' => 'WHOLESALE TIERS',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '07 / B2B DISTRIBUTION',
                'desc' => 'Bulk Carton Packs & Reseller Discounts',
                'cta' => 'VIEW TIERS',
            ],
            'brands' => [
                'title' => 'BRAND CATALOGUE',
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=700&h=900&q=80',
                'tag' => '08 / AUTHENTIC HOUSES',
                'desc' => 'The Ordinary, CeraVe, Cantu & 100+ Brands',
                'cta' => 'ALL BRANDS',
            ],
        ];
    @endphp

    <section class="border-b border-ink bg-paper" aria-label="Departments">
        <div class="mx-auto max-w-[1400px] px-5 py-8 md:px-8 lg:py-12">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 border-b border-ink pb-3 mb-6">
                <div>
                    <h2 class="text-xl uppercase tracking-tight sm:text-2xl lg:text-3xl font-bold text-ink">SHOP BY DEPARTMENT</h2>
                    <p class="micro text-mid mt-0.5">CURATED SECTORS · RETAIL &amp; WHOLESALE SUPPLY</p>
                </div>
                <a href="{{ route('shop.index') }}" class="micro font-bold text-ink hover:underline hidden sm:inline-block">
                    VIEW ALL →
                </a>
            </div>

            <div class="grid grid-cols-2 gap-2.5 sm:gap-4 md:grid-cols-4">
                @foreach ($categories as $cat)
                    @php
                        $dept = $departmentData[$cat->slug] ?? [
                            'title' => strtoupper($cat->nav_label ?? $cat->name),
                            'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=700&h=900&q=80',
                            'tag' => 'DEPARTMENT',
                            'desc' => 'Authentic Cosmetics & Skincare',
                            'cta' => 'EXPLORE',
                        ];
                    @endphp
                    <a
                        href="{{ route('shop.category', $cat->slug) }}"
                        class="group flex flex-col justify-between overflow-hidden border border-ink bg-paper transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        {{-- Image Showcase Container (Reduced height) --}}
                        <div class="relative aspect-[16/10] w-full overflow-hidden bg-mist border-b border-ink">
                            <img
                                src="{{ $dept['image'] }}"
                                alt="{{ $dept['title'] }}"
                                class="h-full w-full object-cover grayscale-[10%] transition-transform duration-700 ease-out group-hover:scale-105 group-hover:grayscale-0"
                                loading="lazy"
                            >
                            {{-- Department Index Pill --}}
                            <div class="absolute top-2 left-2 bg-paper border border-ink px-1.5 py-0.5 shadow-sm">
                                <span class="text-3xs font-bold text-ink tracking-widest">{{ $dept['tag'] }}</span>
                            </div>
                        </div>

                        {{-- Bottom High-Contrast Info Panel (Compact) --}}
                        <div class="flex flex-1 flex-col justify-between p-2.5 sm:p-3 bg-paper">
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-tight text-ink group-hover:opacity-75 transition-opacity">
                                    {{ $dept['title'] }}
                                </h3>
                                <p class="text-3xs sm:text-2xs text-mid mt-0.5 line-clamp-1">
                                    {{ $dept['desc'] }}
                                </p>
                            </div>

                            <div class="mt-2 pt-2 border-t border-rule flex items-center justify-between text-3xs font-bold text-ink">
                                <span>{{ $dept['cta'] }}</span>
                                <span class="transition-transform group-hover:translate-x-0.5 font-bold">→</span>
                            </div>
                        </div>
                    </a>
                @endforeach

                {{-- Dedicated Wholesale Card --}}
                @php
                    $wholesaleDept = $departmentData['wholesale'];
                @endphp
                <a
                    href="{{ route('shop.index') }}#wholesale"
                    class="group flex flex-col justify-between overflow-hidden border border-ink bg-paper transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="relative aspect-[16/10] w-full overflow-hidden bg-mist border-b border-ink">
                        <img
                            src="{{ $wholesaleDept['image'] }}"
                            alt="Wholesale Distribution"
                            class="h-full w-full object-cover grayscale-[10%] transition-transform duration-700 ease-out group-hover:scale-105 group-hover:grayscale-0"
                            loading="lazy"
                        >
                        <div class="absolute top-2 left-2 bg-paper border border-ink px-1.5 py-0.5 shadow-sm">
                            <span class="text-3xs font-bold text-ink tracking-widest">{{ $wholesaleDept['tag'] }}</span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-2.5 sm:p-3 bg-paper">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-tight text-ink group-hover:opacity-75 transition-opacity">
                                {{ $wholesaleDept['title'] }}
                            </h3>
                            <p class="text-3xs sm:text-2xs text-mid mt-0.5 line-clamp-1">
                                {{ $wholesaleDept['desc'] }}
                            </p>
                        </div>

                        <div class="mt-2 pt-2 border-t border-rule flex items-center justify-between text-3xs font-bold text-ink">
                            <span>{{ $wholesaleDept['cta'] }}</span>
                            <span class="transition-transform group-hover:translate-x-0.5 font-bold">→</span>
                        </div>
                    </div>
                </a>

                {{-- Dedicated Brands Directory Card --}}
                @php
                    $brandsDept = $departmentData['brands'];
                @endphp
                <a
                    href="{{ route('shop.brands') }}"
                    class="group flex flex-col justify-between overflow-hidden border border-ink bg-paper transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="relative aspect-[16/10] w-full overflow-hidden bg-mist border-b border-ink">
                        <img
                            src="{{ $brandsDept['image'] }}"
                            alt="Brands Directory"
                            class="h-full w-full object-cover grayscale-[10%] transition-transform duration-700 ease-out group-hover:scale-105 group-hover:grayscale-0"
                            loading="lazy"
                        >
                        <div class="absolute top-2 left-2 bg-paper border border-ink px-1.5 py-0.5 shadow-sm">
                            <span class="text-3xs font-bold text-ink tracking-widest">{{ $brandsDept['tag'] }}</span>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col justify-between p-2.5 sm:p-3 bg-paper">
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-tight text-ink group-hover:opacity-75 transition-opacity">
                                {{ $brandsDept['title'] }}
                            </h3>
                            <p class="text-3xs sm:text-2xs text-mid mt-0.5 line-clamp-1">
                                {{ $brandsDept['desc'] }}
                            </p>
                        </div>

                        <div class="mt-2 pt-2 border-t border-rule flex items-center justify-between text-3xs font-bold text-ink">
                            <span>{{ $brandsDept['cta'] }}</span>
                            <span class="transition-transform group-hover:translate-x-0.5 font-bold">→</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ============ TRENDING PRODUCTS ============ --}}
    <section class="border-b border-ink bg-paper" aria-label="Trending products">
        <div class="mx-auto max-w-[1400px] px-5 py-12 md:px-8 lg:py-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-ink pb-5 mb-10">
                <div>
                    <span class="micro text-mid font-semibold block mb-1">MOST REQUESTED · INSTANT HUB DISPATCH</span>
                    <h2 class="text-3xl uppercase tracking-tight sm:text-4xl lg:text-5xl font-bold text-ink">TRENDING PRODUCTS</h2>
                </div>
                <div class="flex items-center gap-6">
                    <p class="micro text-mid hidden sm:block">0{{ count($featured) }} CURATED HIGHLIGHTS</p>
                    <a href="#catalogue" class="micro font-bold text-ink border-b border-ink pb-0.5 hover:opacity-75 transition-opacity">
                        VIEW ALL PRODUCTS →
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 sm:gap-y-12 md:grid-cols-3 lg:grid-cols-4 lg:gap-x-8">
                @foreach ($featured as $product)
                    <x-product-card :product="$product" :mode="$mode" :availability="$availability" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ FEATURED PICKS — ASYMMETRIC SPOTLIGHT ============ --}}
    @if (count($editorial))
        <section class="border-b border-ink bg-paper" aria-label="Featured picks">
            <div class="mx-auto max-w-[1400px] px-5 py-12 md:px-8 lg:py-16">
                <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 border-b border-ink pb-4">
                    <div>
                        <span class="micro text-mid font-semibold block mb-1">EDITOR'S SPOTLIGHT</span>
                        <h2 class="text-3xl uppercase tracking-tight sm:text-4xl font-bold text-ink">FEATURED PICKS</h2>
                    </div>
                    <p class="micro text-mid">0{{ count($editorial) }} HANDPICKED PRODUCTS · SELECTED FOR YOU</p>
                </div>

                <div class="mt-10 grid items-stretch gap-8 lg:grid-cols-12">
                    <a href="{{ route('shop.product', $editorial[0]->slug) }}" class="group relative block overflow-hidden bg-mist lg:col-span-7">
                        <img
                            src="{{ $editorial[0]->images[0]['src'] }}"
                            alt="{{ $editorial[0]->name }}"
                            class="h-72 w-full object-cover transition-transform duration-300 ease-out group-hover:scale-[1.03] lg:h-[560px]"
                            width="900" height="1125"
                            loading="lazy" decoding="async"
                        >
                    </a>

                    <div class="flex flex-col justify-between gap-8 lg:col-span-5">
                        <div>
                            <p class="micro text-mid">{{ strtoupper($editorial[0]->micro_label) }}</p>
                            <h3 class="mt-2 text-xl leading-tight tracking-tight md:text-2xl">
                                <a href="{{ route('shop.product', $editorial[0]->slug) }}" class="hover:underline underline-offset-2">{{ $editorial[0]->name }}</a>
                            </h3>
                            <div class="mt-4">
                                {{-- Retail pricing --}}
                                <div x-show="mode === 'retail'" class="flex items-baseline gap-1.5">
                                    <span class="price text-xl sm:text-2xl font-bold text-ink">₦{{ number_format($editorial[0]->retail_price) }}</span>
                                    <span class="micro text-mid font-semibold">RETAIL</span>
                                </div>
                                {{-- Wholesale pricing --}}
                                <div x-show="mode === 'wholesale'" x-cloak class="flex items-baseline gap-1.5">
                                    <span class="price text-xl sm:text-2xl font-bold text-ink">₦{{ number_format($editorial[0]->unitPrice('wholesale', $editorial[0]->moq)) }}</span>
                                    <span class="micro text-mid font-semibold">/ MOQ: {{ $editorial[0]->moq }} Pcs</span>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-ink pt-8">
                            <div class="grid grid-cols-2 gap-x-4 gap-y-10">
                                @foreach (array_slice($editorial->all(), 1) as $product)
                                    <x-product-card :product="$product" :mode="$mode" :availability="$availability" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ FULL CATALOGUE ============ --}}
    <section id="catalogue" class="border-b border-ink scroll-mt-24" aria-label="Full catalogue">
        <div class="mx-auto max-w-[1400px] px-5 py-14 md:px-8 lg:py-20">
            <div class="flex flex-wrap items-baseline justify-between gap-4 border-b border-ink pb-4">
                <h2 class="text-2xl uppercase tracking-tight md:text-4xl">ALL DEPARTMENTS</h2>
                <p class="micro text-mid">{{ sprintf('%02d', count($products)) }} PRODUCTS · PRICES IN ₦</p>
            </div>
            <div class="mt-10">
                <x-product-grid :products="$products" :mode="$mode" :availability="$availability" />
            </div>
        </div>
    </section>

    {{-- ============ WHOLESALE ENGINE ============ --}}
    <section id="wholesale" class="scroll-mt-24" aria-label="Wholesale">
        <div class="mx-auto max-w-[1400px] px-5 py-14 md:px-8 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <p class="micro text-mid">RESELLERS · VENDORS · SALONS · STORES</p>
                    <h2 class="display-xl mt-4 text-5xl lg:text-6xl">WHOLESALE<br>WITHOUT<br>FRICTION.</h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-mid">
                        Tiered unit pricing unlocks as your quantity grows. Switch the storefront to wholesale — every product shows its ladder.
                    </p>
                    <div class="mt-8">
                        @include('components.mode-switcher')
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <p class="micro text-mid">EXAMPLE LADDER — THE ORDINARY NIACINAMIDE 10% + ZINC 1%</p>
                    <table class="mt-4 w-full border-collapse text-left">
                        <thead>
                            <tr class="border-y border-ink">
                                <th class="micro py-3 font-normal text-mid">QUANTITY</th>
                                <th class="micro py-3 font-normal text-mid">UNIT PRICE</th>
                                <th class="micro py-3 text-right font-normal text-mid">SAVE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products->first()->ladder() as $i => $rung)
                                <tr class="border-b border-rule">
                                    <td class="py-3 text-sm">{{ $rung['label'] }}</td>
                                    <td class="price py-3 text-sm font-semibold">₦{{ number_format($rung['price']) }}</td>
                                    <td class="price py-3 text-right text-sm text-mid">
                                        @if ($i > 0)
                                            −{{ number_format(round((1 - $rung['price'] / $products->first()->ladder()[0]['price']) * 100)) }}%
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="micro mt-4 text-mid">MOQ {{ $products->first()->moq }} PCS · FREE PH DELIVERY ON WHOLESALE ROUTES</p>
                </div>
            </div>
        </div>
    </section>
@endsection
