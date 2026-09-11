<div class="border-b border-rule bg-paper" x-data>
    <div class="mx-auto flex max-w-[1400px] flex-wrap items-center gap-x-8 gap-y-2 px-5 py-3 md:px-8">
        <span class="micro text-mid">SHOP FROM</span>

        <div class="flex flex-1 flex-wrap items-center gap-x-6 gap-y-2" role="group" aria-label="Inventory location">
            @foreach ($hubOptions as $option)
                <button
                    type="button"
                    class="group flex items-center gap-2 text-left"
                    :class="hub === {{ $option['id'] }} || (!hub && {{ $option['is_default'] ? 'true' : 'false' }}) ? 'opacity-100' : 'opacity-45 hover:opacity-80'"
                    @click="
                        @if ($option['is_default'])
                            hub = ''; persist('hub');
                        @else
                            hub = {{ $option['id'] }}; persist('hub');
                        @endif
                        fetch('{{ route('shop.hub') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                            body: JSON.stringify({ hub_id: {{ $option['is_default'] ? 'null' : $option['id'] }} })
                        }).then(() => window.location.reload())
                    "
                    @if (!empty($option['is_default'])) aria-pressed="true" @endif
                >
                    <span class="block h-1.5 w-1.5 border border-ink transition-colors duration-150" :class="hub === {{ $option['id'] }} || (!hub && {{ $option['is_default'] ? 'true' : 'false' }}) ? 'bg-ink' : 'bg-transparent'"></span>
                    <span class="micro">{{ strtoupper($option['name']) }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>
