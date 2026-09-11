@php
    $statusMap = [
        'in' => ['label' => 'IN STOCK', 'marker' => 'bg-ink'],
        'low' => ['label' => 'LOW STOCK', 'marker' => 'border border-ink bg-transparent'],
        'out' => ['label' => 'UNAVAILABLE AT THIS HUB', 'marker' => 'border border-rule'],
    ];
    $stockConfig = array_key_exists($status, $statusMap) ? $statusMap[$status] : $statusMap['out'];
@endphp

<p class="micro flex items-center gap-2{{ ($status ?? 'out') === 'out' ? ' text-mid' : '' }}">
    <span class="inline-block h-1 w-1 {{ $stockConfig['marker'] }}" aria-hidden="true"></span>
    {{ $stockConfig['label'] }}@isset($hub) · {{ strtoupper($hub) }}@endisset
</p>
