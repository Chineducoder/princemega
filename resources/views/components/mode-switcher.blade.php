<div class="inline-flex items-stretch border border-ink bg-white shadow-sm" role="group" aria-label="Purchasing mode" x-data>
    <button
        type="button"
        class="micro px-5 py-2.5 transition-colors duration-200 cursor-pointer"
        :class="mode === 'retail' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium hover:bg-mist'"
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
        class="micro border-l border-ink px-5 py-2.5 transition-colors duration-200 cursor-pointer"
        :class="mode === 'wholesale' ? 'bg-black text-white font-bold' : 'bg-white text-[#4A4A4A] hover:text-black font-medium hover:bg-mist'"
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
