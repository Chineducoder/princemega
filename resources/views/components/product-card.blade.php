@php
    $cardMode = $mode ?? 'retail';
    $avail = $availability[$product->id] ?? ['status' => 'in', 'stock' => 0, 'hub' => 'ALL HUBS'];
    $images = $product->images ?? [];
    $primary = $images[0] ?? null;
    $secondary = $images[1] ?? null;
    $shades = $product->shades ?? [];
    $isWholesale = $cardMode === 'wholesale';
    $ladder = $product->ladder();
    $hubLabel = strtoupper($avail['hub'] ?? 'ALL HUBS');

    $cardConfig = [
        'id' => $product->id,
        'mode' => $cardMode,
        'price' => $product->retail_price,
        'moq' => $isWholesale ? $product->moq : 1,
        'cartUrl' => route('cart.store'),
        'toast' => 'Added to your bag — '.$product->name,
        'shades' => array_map(fn ($s) => ['name' => $s['name'], 'value' => $s['value']], $shades),
        'tiers' => array_map(fn ($r) => ['min' => $r['min'], 'price' => $r['price']], $ladder),
    ];
@endphp

<article
    class="group relative flex flex-col justify-between border border-ink bg-paper transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
    x-data="productCard(@js($cardConfig))"
>
    {{-- Top Media Window --}}
    <div class="relative aspect-[4/5] sm:aspect-square w-full overflow-hidden bg-mist border-b border-ink">
        {{-- Primary Product Image Link --}}
        <a href="{{ route('shop.product', $product->slug) }}" class="block h-full w-full" aria-label="{{ $product->name }}">
            <img
                src="{{ $primary['src'] }}"
                alt="{{ $product->name }}"
                width="{{ $primary['w'] }}"
                height="{{ $primary['h'] }}"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover grayscale-[8%] transition-transform duration-700 ease-out group-hover:scale-105 group-hover:grayscale-0"
            >
            @if ($secondary)
                <img
                    src="{{ $secondary['src'] }}"
                    alt=""
                    width="{{ $secondary['w'] }}"
                    height="{{ $secondary['h'] }}"
                    loading="lazy"
                    decoding="async"
                    class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-300 ease-out group-hover:opacity-100"
                >
            @endif
        </a>

        {{-- Badges Strip (Top-Left) --}}
        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1 pointer-events-none">
            @if ($product->is_featured)
                <span class="bg-ink text-paper text-2xs uppercase tracking-label font-bold px-2 py-0.5 border border-ink shadow-sm">
                    TRENDING
                </span>
            @endif
            <span
                x-show="mode === 'wholesale'"
                x-cloak
                class="bg-paper text-ink text-2xs uppercase tracking-label font-bold px-2 py-0.5 border border-ink shadow-sm"
            >
                MOQ {{ $product->moq }}
            </span>
        </div>

        {{-- Wishlist Button (Top-Right) --}}
        <button
            type="button"
            class="absolute right-2.5 top-2.5 z-10 border border-ink bg-paper px-2 py-1 text-2xs font-bold uppercase transition-all duration-200 hover:bg-ink hover:text-paper shadow-sm cursor-pointer"
            @click.stop="wishlist = !wishlist"
            :aria-pressed="wishlist"
            aria-label="Add {{ $product->name }} to wishlist"
            x-data="{ wishlist: false }"
            :class="wishlist ? 'bg-ink text-paper opacity-100' : 'opacity-0 group-hover:opacity-100'"
        >
            <span x-text="wishlist ? 'SAVED ✓' : 'SAVE'">SAVE</span>
        </button>
    </div>

    {{-- Product Information Details --}}
    <div class="flex flex-1 flex-col justify-between p-4 bg-paper">
        <div>
            {{-- Brand / Category Micro-Header --}}
            <div class="flex items-center justify-between gap-2">
                <span class="micro font-bold text-mid truncate">{{ strtoupper($product->brand->name ?? $product->micro_label) }}</span>
                <span class="micro text-mid/70">{{ strtoupper($product->category->name ?? '') }}</span>
            </div>

            {{-- Product Name --}}
            <h3 class="mt-1 text-sm sm:text-base font-bold text-ink leading-snug tracking-tight">
                <a href="{{ route('shop.product', $product->slug) }}" class="hover:underline underline-offset-2">
                    {{ $product->name }}
                </a>
            </h3>

            {{-- Subtitle / Volume --}}
            @if ($product->subtitle)
                <p class="text-2xs text-mid mt-0.5 line-clamp-1">
                    {{ str_replace(['\u00b7', '&middot;'], '•', $product->subtitle) }}
                </p>
            @endif

            {{-- Availability status indicator --}}
            <div class="mt-2.5 flex items-center gap-1.5 micro">
                @if ($avail['status'] === 'in')
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-ink"></span>
                    <span class="text-ink font-semibold">IN STOCK</span>
                @elseif ($avail['status'] === 'low')
                    <span class="inline-block h-1.5 w-1.5 rounded-full border border-ink"></span>
                    <span class="text-ink font-semibold">LOW STOCK</span>
                @else
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-rule"></span>
                    <span class="text-mid">UNAVAILABLE</span>
                @endif
            </div>

            {{-- Shades Selector --}}
            @if (count($shades))
                <div class="mt-3 border-t border-rule pt-2.5 flex flex-wrap items-center gap-1.5" role="group" aria-label="Select shade">
                    @foreach ($shades as $shade)
                        @if (($shade['type'] ?? 'color') === 'num')
                            <label class="shade-num">
                                <input type="radio" name="shade-{{ $product->id }}" value="{{ $shade['value'] }}" x-model.number="shade" @click="qty = 1">
                                <span aria-hidden="true">{{ $shade['name'] }}</span>
                            </label>
                        @else
                            <label class="shade" style="--shade-color: {{ $shade['color'] ?? '#e5e5e5' }}">
                                <input type="radio" name="shade-{{ $product->id }}" value="{{ $shade['name'] }}" x-model="shade">
                                <span class="shade-dot" aria-hidden="true"></span>
                                <span class="sr-only">{{ $shade['name'] }}</span>
                            </label>
                        @endif
                    @endforeach
                    <span class="micro ml-1 text-mid font-medium" x-text="shade" aria-live="polite">{{ $shades[0]['name'] }}</span>
                </div>
            @endif
        </div>

        {{-- Pricing & Action Section --}}
        <div class="mt-4 border-t border-ink pt-3.5">
            <div class="flex items-baseline justify-between gap-2">
                <div>
                    {{-- Retail Pricing State --}}
                    <div x-show="mode === 'retail'" class="flex flex-col">
                        <div class="flex items-baseline gap-1">
                            <span class="price text-base sm:text-lg font-bold text-ink">
                                ₦{{ number_format($product->retail_price) }}
                            </span>
                            <span class="micro text-mid font-semibold">RETAIL</span>
                        </div>
                        <p class="text-2xs text-mid mt-0.5">Port Harcourt Dispatch</p>
                    </div>

                    {{-- Wholesale Pricing State --}}
                    <div x-show="mode === 'wholesale'" x-cloak class="flex flex-col">
                        <div class="flex items-baseline gap-1">
                            <span class="price text-base sm:text-lg font-bold text-ink" x-text="'₦' + formatNaira(price())">
                                ₦{{ number_format($product->unitPrice('wholesale', $product->moq)) }}
                            </span>
                            <span class="micro text-mid font-semibold">/ MOQ: {{ $product->moq }} Pcs</span>
                        </div>
                        <p class="text-2xs text-mid mt-0.5">MOQ {{ $product->moq }} PCS @if($product->pack_note) · {{ strtoupper($product->pack_note) }} @endif</p>
                    </div>
                </div>

                {{-- Action Button --}}
                @if ($avail['status'] !== 'out')
                    <button
                        type="button"
                        class="micro border border-ink px-3 py-2 font-bold uppercase transition-colors duration-150 flex items-center gap-1 cursor-pointer"
                        :class="added ? 'bg-ink text-paper' : 'bg-paper text-ink hover:bg-ink hover:text-paper'"
                        @click="open = !open"
                        :aria-expanded="open"
                    >
                        <span x-text="added ? 'ADDED ✓' : (open ? 'CLOSE ✕' : '+ ADD')">+ ADD</span>
                    </button>
                @endif
            </div>

            {{-- Quick-add Quantity & Order Drawer --}}
            @if ($avail['status'] !== 'out')
                <div x-show="open" x-cloak x-collapse.duration.200ms class="mt-3 border-t border-rule pt-3">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="micro text-mid font-semibold">QUANTITY:</span>
                        <div class="flex items-center border border-ink bg-paper">
                            <button
                                type="button"
                                class="px-2.5 py-1 text-xs hover:bg-mist transition-colors"
                                @click="qty = Math.max({{ $isWholesale ? $product->moq : 1 }}, qty - 1)"
                                aria-label="Decrease quantity"
                            >−</button>
                            <input
                                type="number"
                                class="price w-10 border-x border-ink bg-transparent py-1 text-center text-xs font-bold focus:outline-none"
                                min="{{ $isWholesale ? $product->moq : 1 }}"
                                x-model.number="qty"
                                :aria-label="'Quantity of ' + {{ \Illuminate\Support\Js::from($product->name) }}"
                            >
                            <button
                                type="button"
                                class="px-2.5 py-1 text-xs hover:bg-mist transition-colors"
                                @click="qty++"
                                aria-label="Increase quantity"
                            >+</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-2xs border-b border-rule pb-2 mb-3">
                        <span class="text-mid">Subtotal:</span>
                        <span class="price font-bold text-ink" x-text="'₦' + formatNaira(total())"></span>
                    </div>

                    <button
                        type="button"
                        class="btn-black w-full py-2.5 text-2xs font-bold uppercase flex items-center justify-center gap-2"
                        @click="add($event); open = false"
                        :disabled="busy"
                    >
                        <span x-text="busy ? 'ADDING…' : 'CONFIRM TO ORDER'">CONFIRM TO ORDER</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</article>
