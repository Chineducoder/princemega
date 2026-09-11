@extends('layouts.shop')

@section('title', $product->name.' — Prince Mega Agency')
@section('description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? $product->name), 150))

@section('shop-content')
@php
    $isWholesale = $mode === 'wholesale';
    $primary = $product->images[0] ?? null;
    $shades = $product->shades ?? [];
    $hubShort = strtoupper($hub?->short_code ?? 'ALL HUBS');
@endphp

<section class="mx-auto max-w-[1400px] px-5 py-10 md:px-8 lg:py-14">
    <p class="micro text-mid">
        <a href="{{ route('shop.index') }}" class="hover:underline">SHOP</a> —
        <a href="{{ route('shop.category', $product->category->slug) }}" class="hover:underline">{{ strtoupper($product->category->nav_label ?? $product->category->name) }}</a> —
        {{ strtoupper($product->brand->name ?? '') }}
    </p>

    <div class="mt-8 grid gap-10 lg:grid-cols-12" x-data="productCard({ id: {{ $product->id }}, mode: '{{ $mode }}', price: {{ $product->retail_price }}, moq: {{ $isWholesale ? $product->moq : 1 }}, cartUrl: '{{ route('cart.store') }}', toast: 'Added to your order', shades: @js(collect($shades)->map(fn ($s) => ['name' => $s['name'], 'value' => $s['value']])->values()->all()), tiers: @js(collect($ladder)->map(fn ($r) => ['min' => $r['min'], 'price' => $r['price']])->values()->all()) })">
        {{-- Gallery --}}
        <div class="grid gap-4 lg:col-span-7">
            <div class="overflow-hidden bg-mist">
                <img src="{{ $primary['src'] }}" alt="{{ $product->name }}" class="aspect-[4/5] w-full object-cover transition-transform duration-300 ease-out hover:scale-[1.04]" width="{{ $primary['w'] }}" height="{{ $primary['h'] }}" fetchpriority="high" decoding="async">
            </div>
            @if (count($product->images) > 1)
                <div class="grid grid-cols-4 gap-4">
                    @foreach (array_slice($product->images, 1) as $img)
                        <div class="overflow-hidden bg-mist">
                            <img src="{{ $img['src'] }}" alt="" class="aspect-square w-full object-cover transition-transform duration-300 hover:scale-105" width="{{ $img['w'] }}" height="{{ $img['h'] }}" loading="lazy" decoding="async">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Purchase panel --}}
        <div class="lg:col-span-5">
            <div class="lg:sticky lg:top-32">
                <p class="micro text-mid">{{ strtoupper($product->micro_label) }}</p>
                <h1 class="mt-2 text-3xl leading-tight tracking-tight md:text-4xl">{{ $product->name }}</h1>
                @if ($product->subtitle)
                    <p class="mt-2 text-sm text-mid">{{ str_replace(['\u00b7', '&middot;'], '•', $product->subtitle) }}</p>
                @endif

                <div class="mt-6 flex items-end gap-6">
                    <p class="price text-3xl font-semibold leading-none" x-text="'₦' + formatNaira(price())">₦{{ number_format($isWholesale ? $product->unitPrice('wholesale', $product->moq) : $product->retail_price) }}</p>
                    <p class="micro pb-1 text-mid font-semibold" x-text="mode === 'wholesale' ? '/ MOQ: {{ $product->moq }} Pcs' : 'RETAIL'">
                        {{ $isWholesale ? '/ MOQ: '.$product->moq.' Pcs' : 'RETAIL' }}
                    </p>
                </div>

                @include('components.stock-status', ['status' => $availability['status'], 'hub' => $hubShort])

                @if (count($shades))
                    <div class="mt-6 border-t border-rule pt-6">
                        <p class="micro text-mid">SHADE</p>
                        <div class="mt-3">
                            @include('components.shade-selector', ['shades' => $shades, 'product' => $product])
                        </div>
                    </div>
                @endif

                {{-- Quantity + add --}}
                <div class="mt-6 border-t border-rule pt-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-ink">
                            <button type="button" class="px-3 py-2.5" @click="qty = Math.max({{ $isWholesale ? $product->moq : 1 }}, qty - 1)" aria-label="Decrease quantity">−</button>
                            <input type="number" class="price w-14 border-x border-ink bg-transparent py-2.5 text-center text-sm focus:outline-none" min="{{ $isWholesale ? $product->moq : 1 }}" x-model.number="qty" aria-label="Quantity">
                            <button type="button" class="px-3 py-2.5" @click="qty++" aria-label="Increase quantity">+</button>
                        </div>
                        <p class="micro text-mid" x-text="'₦' + formatNaira(total()) + ' TOTAL'"></p>
                    </div>

                    @if ($availability['status'] !== 'out')
                        <button type="button" class="btn-black mt-5 w-full" @click="add($event)" :disabled="busy">
                            <span x-text="busy ? 'ADDING…' : 'ADD TO ORDER'">ADD TO ORDER</span>
                        </button>
                    @else
                        <button type="button" class="btn-black mt-5 w-full opacity-40" disabled>UNAVAILABLE AT THIS HUB</button>
                    @endif

                    <button type="button" class="btn-outline mt-3 w-full" @click="wishlist = !wishlist" :aria-pressed="wishlist" x-data="{ wishlist: false }">
                        <span x-text="wishlist ? 'SAVED TO WISHLIST ✓' : 'SAVE TO WISHLIST'">SAVE TO WISHLIST</span>
                    </button>
                </div>

                {{-- Tier ladder --}}
                <div class="mt-8 border-t border-ink pt-6">
                    <p class="micro text-mid">WHOLESALE LADDER</p>
                    <table class="mt-3 w-full text-left">
                        <tbody>
                            @foreach ($ladder as $rung)
                                <tr class="border-b border-rule" :class="qty >= {{ $rung['min'] }} ? '' : 'opacity-50'">
                                    <td class="py-2.5 text-sm">{{ $rung['label'] }}</td>
                                    <td class="price py-2.5 text-right text-sm font-semibold">₦{{ number_format($rung['price']) }} / UNIT</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="micro mt-3 text-mid">PRICING UNLOCKS AUTOMATICALLY AS QUANTITY GROWS</p>
                </div>

                {{-- Hub availability --}}
                <div class="mt-8 border-t border-ink pt-6">
                    <p class="micro text-mid">AVAILABILITY BY HUB</p>
                    <ul class="mt-3 divide-y divide-rule border border-rule">
                        @foreach ($hubStock as $hubName => $status)
                            <li class="flex items-center justify-between px-4 py-2.5">
                                <span class="micro">{{ $hubName }}</span>
                                <span class="micro {{ $status === 'OUT' ? 'text-mid' : '' }}">{{ $status === 'OUT' ? 'OUT' : $status }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if ($product->description)
        <div class="mt-14 border-t border-ink pt-10 lg:mt-20">
            <div class="grid gap-8 lg:grid-cols-12">
                <p class="micro text-mid lg:col-span-3">THE DETAIL</p>
                <p class="max-w-2xl text-base leading-relaxed lg:col-span-6">{{ $product->description }}</p>
            </div>
        </div>
    @endif
</section>
@endsection
