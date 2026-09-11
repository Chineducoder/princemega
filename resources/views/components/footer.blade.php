<footer class="mt-24 border-t border-ink">
    <div class="mx-auto max-w-[1400px] px-5 py-14 md:px-8">
        <div class="grid gap-10 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Prince Mega Agency Logo"
                        class="h-8 w-8 object-contain rounded-sm"
                        width="32"
                        height="32"
                    >
                    <p class="text-sm font-semibold uppercase tracking-brand">PRINCE MEGA AGENCY</p>
                </div>
                <p class="mt-3 max-w-sm text-sm text-mid">
                    Authentic cosmetics, skincare and fragrance — retail, reseller and wholesale supply from Port Harcourt since 2014.
                </p>
                <p class="micro mt-6 text-mid">WHOLESALE • RETAIL • DISTRIBUTION</p>
            </div>

            <div>
                <p class="micro text-mid">PHYSICAL HUBS</p>
                <ul class="mt-4 space-y-3 text-sm">
                    <li>Rumuola Head Office<br><span class="text-2xs text-mid">Rumuola Road, GRA Phase II</span></li>
                    <li>Mile 1 Education Bus-Stop<br><span class="text-2xs text-mid">Diobu, Port Harcourt</span></li>
                    <li>Woji YKC Plaza<br><span class="text-2xs text-mid">Woji Road, GRA Ext.</span></li>
                    <li>Peter Odili Market Square<br><span class="text-2xs text-mid">Trans-Amadi, Port Harcourt</span></li>
                </ul>
            </div>

            <div>
                <p class="micro text-mid">DEPARTMENTS</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('shop.category', 'skincare') }}" class="link-quiet">Skincare</a></li>
                    <li><a href="{{ route('shop.category', 'makeup') }}" class="link-quiet">Makeup</a></li>
                    <li><a href="{{ route('shop.category', 'fragrance') }}" class="link-quiet">Fragrance</a></li>
                    <li><a href="{{ route('shop.category', 'hair') }}" class="link-quiet">Hair</a></li>
                    <li><a href="{{ route('shop.category', 'body') }}" class="link-quiet">Body</a></li>
                    <li><a href="{{ route('shop.category', 'tools') }}" class="link-quiet">Tools</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-14 flex flex-wrap items-center justify-between gap-4 border-t border-rule pt-6">
            <p class="micro text-mid">© {{ date('Y') }} PRINCE MEGA AGENCY — PORT HARCOURT, NIGERIA</p>
            <div class="flex gap-6">
                <a href="#" class="link-quiet">Terms</a>
                <a href="#" class="link-quiet">Privacy</a>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('princemega.whatsapp_number')) }}" target="_blank" rel="noopener" class="link-quiet">WhatsApp</a>
            </div>
        </div>
    </div>
</footer>
