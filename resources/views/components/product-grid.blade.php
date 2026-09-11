@php
    $gridMode = $mode ?? 'retail';
@endphp

<div class="grid grid-cols-2 gap-x-4 gap-y-12 md:gap-x-6 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
    @foreach ($products as $product)
        <x-product-card :product="$product" :mode="$gridMode" :availability="$availability" />
    @endforeach
</div>
