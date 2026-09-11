import Alpine from 'alpinejs';

window.Alpine = Alpine;

/*
 * Prince Mega Agency — storefront interactivity.
 * Alpine components are registered as plain functions so the markup
 * stays declarative and the bundle stays tiny (no build-step magic).
 */

/* ------------------------------------------------------------------ */
/* Storefront state: purchasing mode, hub, cart drawer, toasts         */
/* ------------------------------------------------------------------ */
Alpine.data('shop', () => ({
    mode: document.documentElement.dataset.initialMode || 'retail',
    hub: document.documentElement.dataset.initialHub || '',
    drawerOpen: false,
    receiptName: '',
    toasts: [],
    cart: { count: 0, subtotal: 0, delivery: 0, total: 0, currency: 'NGN' },
    _toastId: 0,

    init() {
        this.$watch('mode', () => this.persist('mode'));
        this.$watch('hub', () => this.persist('hub'));
        window.addEventListener('cart:updated', (e) => {
            this.cart = e.detail;
        });
        window.addEventListener('toast', (e) => {
            this.toast(e.detail);
        });
        this.refreshCart();
    },

    persist(key) {
        try {
            window.localStorage.setItem('pma.' + key, this[key]);
        } catch {
            /* storage unavailable — session state is enough */
        }
    },

    toast(message) {
        const id = ++this._toastId;
        this.toasts.push({ id, message });
        setTimeout(() => {
            this.toasts = this.toasts.filter((t) => t.id !== id);
        }, 2600);
    },

    formatNaira(value) {
        return new Intl.NumberFormat('en-NG').format(Number(value) || 0);
    },

    /* Server renders the authoritative summary; client mirrors it after mutations */
    async refreshCart() {
        try {
            const res = await fetch('/cart/summary', {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) return;
            this.cart = await res.json();
        } catch {
            /* network hiccup — keep last known state */
        }
    },

    async updateLine(lineId, quantity) {
        const qty = Math.max(1, parseInt(quantity, 10) || 1);
        try {
            const res = await fetch('/cart/' + lineId, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ quantity: qty }),
            });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.cart = data.summary;
            window.dispatchEvent(new CustomEvent('lines:refresh', { detail: data.lines }));
        } catch {
            this.toast('Could not update quantity');
        }
    },

    async removeLine(lineId) {
        try {
            const res = await fetch('/cart/' + lineId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
            });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.cart = data.summary;
            window.dispatchEvent(new CustomEvent('lines:refresh', { detail: data.lines }));
        } catch {
            this.toast('Could not remove item');
        }
    },

    async exportOrder() {
        if (this.exporting) return;
        this.exporting = true;
        try {
            const res = await fetch('/checkout/whatsapp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    mode: this.mode,
                    hub_id: this.hub || null,
                }),
            });
            if (!res.ok) throw new Error();
            const data = await res.json();
            if (data.redirect_url) {
                window.open(data.redirect_url, '_blank', 'noopener');
            }
        } catch {
            this.toast('Could not compile order — retry');
        } finally {
            this.exporting = false;
        }
    },
    exporting: false,
}));

/* ------------------------------------------------------------------ */
/* Receipt upload node — reveals the chosen filename, no cloud widget  */
/* ------------------------------------------------------------------ */
Alpine.data('receiptUpload', () => ({
    name: '',
    size: '',
    change(event) {
        const file = event.target.files && event.target.files[0];
        if (!file) {
            this.name = '';
            this.size = '';
            return;
        }
        this.name = file.name;
        this.size = (file.size / 1024 / 1024).toFixed(1) + ' MB';
    },
    clear(event) {
        event.stopPropagation();
        const input = this.$refs.file;
        input.value = '';
        this.name = '';
        this.size = '';
    },
}));

/* ------------------------------------------------------------------ */
/* Pricing tiers — shared by product cards and the product page        */
/* ------------------------------------------------------------------ */
Alpine.data('tiered', (tiers) => ({
    tiers: Array.isArray(tiers) ? tiers : [],

    tierFor(qty) {
        let current = this.tiers[0];
        for (const tier of this.tiers) {
            if (qty >= tier.min) current = tier;
        }
        return current;
    },

    unitPrice(qty) {
        return this.tierFor(qty)?.price ?? 0;
    },
}));

/* ------------------------------------------------------------------ */
/* Product card: shades, quick-add, wishlist                           */
/* ------------------------------------------------------------------ */
Alpine.data('productCard', (config) => ({
    open: false,
    qty: config.moq || 1,
    shade: config.shades && config.shades.length ? config.shades[0].name : null,
    hover: false,
    added: false,
    busy: false,

    currentMode() {
        return this.mode || config.mode || 'retail';
    },

    shadeValue() {
        const selected = (config.shades || []).find((s) => s.name === this.shade);
        return selected ? selected.value : null;
    },

    price() {
        if (this.currentMode() === 'wholesale') {
            const tiers = config.tiers || [];
            let current = tiers[0];
            for (const tier of tiers) {
                if (this.qty >= tier.min) current = tier;
            }
            return current ? current.price : config.price;
        }
        return config.price;
    },

    total() {
        return this.price() * this.qty;
    },

    async add(event) {
        if (event) event.stopPropagation();
        if (this.busy) return;
        this.busy = true;
        try {
            const res = await fetch(config.cartUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    product_id: config.id,
                    shade: this.shadeValue(),
                    quantity: this.qty,
                    mode: this.currentMode(),
                }),
            });
            if (!res.ok) throw new Error('Request failed');
            const data = await res.json();
            document.dispatchEvent(
                new CustomEvent('cart:updated', { detail: data.summary }),
            );
            if (config.toast) {
                document.dispatchEvent(
                    new CustomEvent('toast', { detail: config.toast }),
                );
            }
            this.added = true;
            setTimeout(() => (this.added = false), 1800);
        } catch {
            document.dispatchEvent(
                new CustomEvent('toast', { detail: 'Could not add — please retry' }),
            );
        } finally {
            this.busy = false;
        }
    },
}));

/* ------------------------------------------------------------------ */
/* Cart drawer: live line editing without a page reload                */
/* ------------------------------------------------------------------ */
Alpine.data('cartDrawer', () => ({
    lines: window.__cartLines || [],
    init() {
        window.addEventListener('lines:refresh', (e) => {
            this.lines = e.detail;
        });
    },
    format(value) {
        return new Intl.NumberFormat('en-NG').format(Number(value) || 0);
    },
    increment(line) {
        this.$data.shop.updateLine(line.id, line.quantity + 1);
    },
    decrement(line) {
        if (line.quantity <= 1) {
            this.remove(line);
            return;
        }
        this.$data.shop.updateLine(line.id, line.quantity - 1);
    },
    remove(line) {
        this.$data.shop.removeLine(line.id);
    },
}));

window.Alpine = Alpine;
Alpine.start();
