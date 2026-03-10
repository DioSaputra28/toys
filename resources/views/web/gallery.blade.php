@extends('web.layouts.app')

@section('title', 'Galeri - Toy Boutique')

@section('content')
    @php
        $featuredItem = $galleryItems->first();
        $supportingItems = $galleryItems->slice(1, 4)->values();
        $remainingItems = $galleryItems->slice(5)->values();
    @endphp

    <section class="bg-white dark:bg-background-dark pt-12 pb-10 px-6 lg:px-12 border-b border-gray-100 dark:border-gray-800">
        <div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row justify-between items-end gap-6">
            <div>
                <span class="text-sm font-bold tracking-widest uppercase text-editorial-teal mb-2 block">Curated Visual Journal</span>
                <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-editorial-dark dark:text-white leading-[0.9]">
                    GALERI <br><span class="text-editorial-terracotta">MOMENTS</span>
                </h1>
            </div>
            <p class="text-lg text-gray-500 max-w-md text-left lg:text-right">
                A visual collection of playful stories, textures, and timeless details from our world of handcrafted toys.
            </p>
        </div>
    </section>

    <section class="px-6 py-12 lg:px-12 max-w-[1440px] mx-auto" data-gallery-dynamic>
        @if ($galleryItems->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white/80 px-8 py-14 text-center">
                <h2 class="text-2xl font-bold mb-3">No gallery images yet</h2>
                <p class="text-gray-500 mb-6">Add active gallery items from the admin panel to populate this page.</p>
                <a class="inline-flex items-center gap-2 rounded-lg bg-editorial-dark text-white px-5 py-2.5 text-sm font-bold uppercase tracking-wider hover:bg-editorial-terracotta transition-colors" href="{{ route('products') }}">
                    Explore Products
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[220px]">
                @if ($featuredItem)
                    <article class="lg:col-span-2 lg:row-span-2 rounded-xl overflow-hidden relative group">
                        <img alt="{{ $featuredItem->image_alt_text }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $featuredItem->image_url }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-white">
                            <h3 class="text-3xl font-bold">{{ $featuredItem->title ?: 'Playroom Editorial' }}</h3>
                            <p class="text-white/85">Timeless corners for boundless imagination</p>
                        </div>
                    </article>
                @endif

                @foreach ($supportingItems as $supportingItem)
                    <article class="rounded-xl overflow-hidden bg-gray-200">
                        <img alt="{{ $supportingItem->image_alt_text }}" class="h-full w-full object-cover" src="{{ $supportingItem->image_url }}">
                    </article>
                @endforeach

            </div>

            @if ($remainingItems->isNotEmpty())
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[220px]">
                    @foreach ($remainingItems as $remainingItem)
                        <article class="rounded-xl overflow-hidden bg-gray-200 group">
                            <img alt="{{ $remainingItem->image_alt_text }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $remainingItem->image_url }}">
                        </article>
                    @endforeach
                </div>
            @endif
        @endif
    </section>
@endsection
