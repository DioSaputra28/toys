@extends('web.layouts.app')

@section('title', $product->name.' - Toy Boutique')

@section('content')
    @php
        $primaryImage = $galleryImages->first();
        $secondaryImages = $galleryImages->slice(1);
        $productDetails = [
            'Category' => $product->category?->name,
            'Status' => $product->is_active ? 'Available' : 'Unavailable',
            'Featured' => $product->is_featured ? 'Yes' : 'No',
        ];
    @endphp

    <section class="bg-white dark:bg-background-dark pt-12 pb-8 px-6 lg:px-12 border-b border-gray-100 dark:border-gray-800">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col gap-5">
                <nav aria-label="Breadcrumb" class="flex items-center gap-3 text-xs uppercase tracking-[0.24em] text-gray-500">
                    <a class="transition-colors hover:text-editorial-dark dark:hover:text-white" href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a class="transition-colors hover:text-editorial-dark dark:hover:text-white" href="{{ route('products') }}">Products</a>
                    <span>/</span>
                    <span class="text-editorial-dark dark:text-white">{{ $product->name }}</span>
                </nav>

                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
                    <div>
                        <span class="text-sm font-bold tracking-widest uppercase text-editorial-teal mb-2 block">{{ $product->category?->name ?: 'Uncategorized' }}</span>
                        <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-editorial-dark dark:text-white leading-[0.9]">
                            {{ $product->name }}
                        </h1>
                    </div>
                    <p class="text-lg text-gray-500 max-w-md text-left lg:text-right">
                        Product details sourced directly from your catalog database.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 py-12 lg:px-12 max-w-[1440px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 gap-5">
                    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-[0_24px_65px_-32px_rgba(41,50,65,0.38)]">
                        <div class="aspect-[5/4] bg-gray-100">
                            @if ($primaryImage)
                                <img alt="{{ $primaryImage['alt'] }}" class="h-full w-full object-cover" src="{{ $primaryImage['url'] }}">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-editorial-mustard/50 to-editorial-teal/35"></div>
                            @endif
                        </div>
                    </div>

                    @if ($secondaryImages->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                            @foreach ($secondaryImages as $image)
                                <div class="relative overflow-hidden rounded-[1.5rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-[0_16px_45px_-28px_rgba(41,50,65,0.32)]">
                                    <div class="aspect-[4/5] bg-gray-100">
                                        <img alt="{{ $image['alt'] }}" class="h-full w-full object-cover transition-transform duration-700 hover:scale-105" src="{{ $image['url'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-24 rounded-[2rem] border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-7 md:p-8 shadow-[0_24px_60px_-34px_rgba(41,50,65,0.35)]">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="inline-flex items-center gap-2 rounded-full bg-editorial-mustard/70 px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-editorial-dark">
                            Product Detail
                        </span>
                        @if ($product->is_featured)
                            <span class="inline-flex items-center gap-2 rounded-full bg-editorial-dark px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-white">
                                Featured
                            </span>
                        @endif
                    </div>

                    <div class="flex items-end justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div>
                            <p class="text-sm uppercase tracking-[0.24em] text-gray-500">Price</p>
                            <p class="mt-2 text-4xl font-bold text-editorial-dark dark:text-white">{{ $product->formatted_display_price ?: '-' }}</p>
                        </div>
                    </div>

                    @if ($product->description)
                        <div class="py-6 border-b border-gray-100 dark:border-gray-800">
                            <p class="text-lg leading-relaxed text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="py-6 border-b border-gray-100 dark:border-gray-800 space-y-4">
                        @foreach ($productDetails as $label => $value)
                            @if ($value)
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xs uppercase tracking-[0.24em] text-gray-500">{{ $label }}</span>
                                    <span class="text-sm font-semibold text-editorial-dark dark:text-white text-right">{{ $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if ($whatsappUrl)
                        <div class="pt-6">
                            <a class="inline-flex w-full items-center justify-center gap-3 rounded-2xl bg-[#25D366] px-6 py-4 text-sm font-bold uppercase tracking-[0.22em] text-white transition-transform hover:-translate-y-0.5 hover:bg-[#1fb45a]" href="{{ $whatsappUrl }}" rel="noopener noreferrer" target="_blank">
                                <span class="material-symbols-outlined text-base">chat</span>
                                Pesan via WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="px-6 pb-16 lg:px-12 max-w-[1440px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <span class="text-sm font-bold tracking-widest uppercase text-editorial-teal mb-2 block">Continue Browsing</span>
                    <h2 class="text-4xl md:text-6xl font-bold tracking-tighter text-editorial-dark dark:text-white">Related Products</h2>
                </div>
                <a class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-bold uppercase tracking-wider text-gray-600 hover:text-editorial-dark hover:border-editorial-dark transition-colors" href="{{ route('products') }}">
                    Back to Catalog
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($relatedProducts as $relatedProduct)
                    <a class="rounded-xl overflow-hidden bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 group shadow-[0_18px_45px_-30px_rgba(41,50,65,0.28)] block" href="{{ route('products.show', $relatedProduct->slug) }}">
                        <div class="h-[260px] relative overflow-hidden bg-gray-100">
                            @if ($relatedProduct->image_url)
                                <img alt="{{ $relatedProduct->image_alt }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $relatedProduct->image_url }}">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-editorial-mustard/50 to-editorial-teal/35"></div>
                            @endif
                        </div>
                        <div class="p-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-widest text-gray-500">{{ $relatedProduct->category?->name ?: 'Uncategorized' }}</p>
                                <h3 class="mt-2 text-2xl font-bold leading-tight text-editorial-dark dark:text-white">{{ $relatedProduct->name }}</h3>
                            </div>
                            <span class="text-sm md:text-base font-bold text-editorial-dark dark:text-white whitespace-nowrap">{{ $relatedProduct->formatted_display_price ?: '-' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
