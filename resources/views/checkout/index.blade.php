@extends('layouts.checkout')

@section('title', 'Checkout — Prince Mega Agency')

@section('checkout-content')
@php
    $summary = $cart['summary'];
    $lines = $cart['lines'];
@endphp

<section class="py-12 lg:py-16">
    <p class="micro text-mid"><a href="{{ route('shop.index') }}" class="hover:underline">SHOP</a> — CHECKOUT</p>
    <h1 class="display-xl mt-4 text-5xl lg:text-7xl">YOUR ORDER</h1>

    <div class="mt-10 grid gap-12 lg:grid-cols-12">
        {{-- Order review --}}
        <div class="lg:col-span-7">
            <div class="border-t border-ink">
                @forelse ($lines as $line)
                    <div class="flex items-start justify-between gap-6 border-b border-rule py-6">
                        <div class="flex gap-4">
                            <div class="h-24 w-20 shrink-0 overflow-hidden bg-mist">
                                <img src="{{ $line['image'] }}" alt="" class="h-full w-full object-cover" width="80" height="96" loading="lazy">
                            </div>
                            <div>
                                <p class="micro text-mid">{{ strtoupper($line['micro_label']) }}</p>
                                <p class="mt-1 text-sm">{{ $line['name'] }}</p>
                                @if ($line['shade'])
                                    <p class="micro mt-1 text-mid">SHADE — {{ strtoupper($line['shade']) }}</p>
                                @endif
                                <p class="micro mt-1 text-mid">QTY {{ $line['quantity'] }} · {{ strtoupper($line['mode']) }}</p>
                            </div>
                        </div>
                        <p class="price text-sm font-semibold">₦{{ number_format($line['line_total']) }}</p>
                    </div>
                @empty
                    <div class="border-b border-rule py-16 text-center">
                        <p class="text-sm">Your order is empty.</p>
                        <a href="{{ route('shop.index') }}" class="btn-outline mt-6 inline-flex">RETURN TO SHOP</a>
                    </div>
                @endforelse
            </div>

            {{-- Delivery destination --}}
            <div class="mt-10">
                <p class="micro text-mid">DELIVERY DESTINATION</p>
                <div class="mt-4 grid gap-x-8 gap-y-2 sm:grid-cols-2">
                    @foreach (\App\Support\DeliveryEngine::destinations() as $city => $geo)
                        <label class="flex items-center justify-between border-b border-rule py-3 text-sm {{ $loop->first ? '' : 'opacity-60' }}">
                            <span class="flex items-center gap-3">
                                <input type="radio" name="destination" value="{{ $city }}" @checked($loop->first) class="h-3.5 w-3.5 appearance-none border border-ink checked:bg-ink">
                                {{ $city }}
                            </span>
                            <span class="micro text-mid">{{ strtoupper($geo['state']) }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="micro mt-4 text-mid">RATES RESOLVE PER ZONE AT COMPILE — WHOLESALE ROUTES SHIP FREE IN PH</p>
            </div>
        </div>

        {{-- Sticky compile panel --}}
        <div class="lg:col-span-5">
            <div class="border border-ink p-6 lg:sticky lg:top-32">
                <p class="micro text-mid">ORDER SUMMARY</p>
                <dl class="mt-5 space-y-3">
                    <div class="flex justify-between">
                        <dt class="micro text-mid">SUBTOTAL</dt>
                        <dd class="price text-sm">₦{{ number_format($summary['subtotal']) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="micro text-mid">DELIVERY</dt>
                        <dd class="price text-sm">{{ $summary['delivery'] > 0 ? '₦'.number_format($summary['delivery']) : 'FREE' }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-ink pt-3">
                        <dt class="micro">TOTAL</dt>
                        <dd class="price text-xl font-semibold">₦{{ number_format($summary['total']) }}</dd>
                    </div>
                </dl>

                <div class="mt-6">
                    @include('components.receipt-upload')
                </div>

                <div class="mt-6">
                    @include('components.whatsapp-checkout')
                </div>

                <p class="micro mt-4 text-mid">BANK TRANSFER COMPLETES THIS ORDER — RECEIPT ATTACHMENT JOINS THE PRIORITY PACKING QUEUE</p>
            </div>
        </div>
    </div>
</section>
@endsection
