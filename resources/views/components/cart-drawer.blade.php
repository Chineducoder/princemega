<div
    x-data="cartDrawer()"
    x-init="lines = @json($cart['lines'])"
    @open-drawer.window="drawerOpen = true"
    x-cloak
    class="fixed inset-0 z-50 pointer-events-none"
>
    {{-- Scrim --}}
    <div
        class="absolute inset-0 bg-ink/40 transition-opacity duration-200"
        :class="drawerOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0'"
        @click="drawerOpen = false"
        x-show="drawerOpen"
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    <aside
        class="absolute inset-y-0 right-0 flex w-full flex-col bg-paper transition-transform duration-300 ease-out-quart pointer-events-auto"
        :class="drawerOpen ? 'translate-x-0' : 'translate-x-full'"
        role="dialog"
        aria-modal="true"
        aria-label="Your order"
        @keydown.escape.window="drawerOpen = false"
        x-trap.inert.noscroll="drawerOpen"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-ink px-6 py-5">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-brand">YOUR ORDER</h2>
                <p class="micro mt-1 text-mid" aria-live="polite">
                    <span x-text="String($data.shop?.cart?.count ?? 0).padStart(2, '0')">00</span> ITEMS
                </p>
            </div>
            <button type="button" class="micro" @click="drawerOpen = false">Close</button>
        </div>

        {{-- Lines — Alpine-rendered so updates need no reload --}}
        <div class="scroll-thin flex-1 overflow-y-auto px-6">
            <template x-for="line in lines" :key="line.id">
                <div class="flex gap-4 border-b border-rule py-5">
                    <div class="h-20 w-16 shrink-0 overflow-hidden bg-mist">
                        <img :src="line.image" alt="" class="h-full w-full object-cover" loading="lazy" width="64" height="80">
                    </div>
                    <div class="flex-1">
                        <p class="micro text-mid" x-text="line.micro_label"></p>
                        <p class="mt-0.5 text-sm leading-snug" x-text="line.name"></p>
                        <p class="micro mt-1 text-mid">
                            <span x-show="line.shade">SHADE — <span x-text="line.shade"></span> · </span>
                            <span x-text="line.mode ? line.mode.toUpperCase() : ''"></span>
                        </p>
                        <div class="mt-2.5 flex items-center justify-between">
                            <div class="flex items-center border border-ink">
                                <button type="button" class="px-2 py-1 text-xs" @click="decrement(line)" aria-label="Decrease quantity">−</button>
                                <span class="price min-w-8 border-x border-ink px-2 py-1 text-center text-2xs" x-text="line.quantity"></span>
                                <button type="button" class="px-2 py-1 text-xs" @click="increment(line)" aria-label="Increase quantity">+</button>
                            </div>
                            <p class="price text-sm font-semibold" x-text="'₦' + format(line.line_total)"></p>
                        </div>
                    </div>
                    <button type="button" class="micro self-start text-mid hover:text-ink" @click="remove(line)" :aria-label="'Remove '+line.name">✕</button>
                </div>
            </template>

            <div x-show="!lines.length" class="py-16 text-center">
                <p class="text-sm">Your order is empty.</p>
                <p class="micro mt-2 text-mid">ADD PRODUCTS TO COMPILE A REQUEST</p>
            </div>
        </div>

        {{-- Footer: totals + receipt + CTA --}}
        <div class="border-t border-ink px-6 py-5">
            <dl class="space-y-2">
                <div class="flex items-center justify-between">
                    <dt class="micro text-mid">SUBTOTAL</dt>
                    <dd class="price text-sm" x-text="'₦' + format($data.shop?.cart?.subtotal ?? 0)">₦0</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="micro text-mid">DELIVERY</dt>
                    <dd class="price text-sm">
                        <span x-text="($data.shop?.cart?.delivery ?? 0) > 0 ? '₦' + format($data.shop?.cart?.delivery) : 'FREE'">FREE</span>
                    </dd>
                </div>
                <div class="flex items-center justify-between border-t border-ink pt-3">
                    <dt class="micro">TOTAL</dt>
                    <dd class="price text-base font-semibold" x-text="'₦' + format($data.shop?.cart?.total ?? 0)">₦0</dd>
                </div>
            </dl>

            @include('components.receipt-upload')

            <div class="mt-4 grid grid-cols-1 gap-2">
                <a href="{{ route('checkout.index') }}" class="btn-outline w-full text-center">
                    REVIEW &amp; CHECKOUT
                </a>
                <button type="button" class="btn-black w-full" @click="exportOrder()" :disabled="exporting">
                    <span x-text="exporting ? 'COMPILING…' : 'COMPILE ORDER & EXPORT TO WHATSAPP'">COMPILE ORDER &amp; EXPORT TO WHATSAPP</span>
                </button>
            </div>
            <p class="micro mt-3 text-center text-mid">A WHATSAPP WINDOW OPENS WITH YOUR COMPILED ORDER</p>
        </div>
    </aside>
</div>
