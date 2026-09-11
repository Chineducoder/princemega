@php
    $shadeUid = $uid ?? $product->id;
@endphp

<div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Select shade">
    @foreach ($shades as $shade)
        @if (($shade['type'] ?? 'color') === 'num')
            <label class="shade-num">
                <input type="radio" name="shade-{{ $shadeUid }}" value="{{ $shade['value'] }}" x-model="shade">
                <span aria-hidden="true">{{ $shade['name'] }}</span>
            </label>
        @else
            <label class="shade" style="--shade-color: {{ $shade['color'] ?? '#e5e5e5' }}">
                <input type="radio" name="shade-{{ $shadeUid }}" value="{{ $shade['name'] }}" x-model="shade">
                <span class="shade-dot" aria-hidden="true"></span>
                <span class="sr-only">{{ $shade['name'] }}</span>
            </label>
        @endif
    @endforeach
    <span class="micro ml-1 text-mid" x-text="shade" aria-live="polite">{{ $shades[0]['name'] ?? '' }}</span>
</div>
