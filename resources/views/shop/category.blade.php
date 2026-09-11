@extends('layouts.shop')

@section('title', $category->name.' — Prince Mega Agency')
@section('description', 'Shop '.$category->name.' at Prince Mega Agency — retail and wholesale from Port Harcourt.')

@section('shop-content')
    <section class="border-b border-ink">
        <div class="mx-auto flex max-w-[1400px] flex-wrap items-end justify-between gap-4 px-5 py-12 md:px-8 lg:py-16">
            <div>
                <p class="micro text-mid"><a href="{{ route('shop.index') }}" class="hover:underline">SHOP</a> — {{ strtoupper($category->name) }}</p>
                <h1 class="display-xl mt-4 text-6xl lg:text-8xl">{{ strtoupper($category->name) }}</h1>
            </div>
            <p class="micro text-mid">{{ sprintf('%02d', count($products)) }} PRODUCTS · PRICES IN ₦</p>
        </div>
    </section>

    <section class="border-b border-ink" aria-label="Departments">
        <div class="mx-auto max-w-[1400px] px-5 md:px-8">
            <ul class="scroll-thin flex divide-x divide-rule overflow-x-auto">
                @foreach ($categories as $cat)
                    <li class="shrink-0">
                        <a href="{{ route('shop.category', $cat->slug) }}" class="block px-6 py-6 transition-colors duration-200 hover:bg-mist md:px-10 {{ $cat->id === $category->id ? 'bg-ink text-paper' : '' }}">
                            <span class="text-lg uppercase tracking-tight md:text-2xl">{{ strtoupper($cat->nav_label ?? $cat->name) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mx-auto max-w-[1400px] px-5 py-14 md:px-8 lg:py-20">
        @if (count($products))
            <x-product-grid :products="$products" :mode="$mode" :availability="$availability" />
        @else
            <p class="py-20 text-center text-sm text-mid">STOCK ARRIVING IN THIS DEPARTMENT.</p>
        @endif
    </section>
@endsection
