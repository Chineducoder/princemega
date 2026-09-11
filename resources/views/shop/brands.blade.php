@extends('layouts.shop')

@section('title', 'Brands — Prince Mega Agency')
@section('description', 'The brands stocked at Prince Mega Agency — authenticity guaranteed, retail and wholesale.')

@section('shop-content')
    <section class="border-b border-ink">
        <div class="mx-auto max-w-[1400px] px-5 py-12 md:px-8 lg:py-16">
            <p class="micro text-mid">THE AGENCY WALL</p>
            <h1 class="display-xl mt-4 text-6xl lg:text-8xl">BRANDS</h1>
        </div>
    </section>

    <section class="mx-auto max-w-[1400px] px-5 py-14 md:px-8 lg:py-20">
        <ul class="divide-y divide-rule border-y border-ink">
            @forelse ($brands as $brand)
                <li class="flex items-baseline justify-between py-6 transition-colors duration-200 hover:bg-mist">
                    <span class="text-xl tracking-tight md:text-3xl">{{ $brand->name }}</span>
                    <span class="micro text-mid">{{ sprintf('%02d', $brand->products_count) }} PRODUCTS</span>
                </li>
            @empty
                <li class="py-10 text-sm text-mid">BRAND WALL LOADING.</li>
            @endforelse
        </ul>
    </section>
@endsection
