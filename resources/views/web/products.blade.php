@extends('web.layouts.app')

@section('title', 'Produk - Toy Boutique')

@section('content')
    <section class="bg-white dark:bg-background-dark pt-12 pb-8 px-6 lg:px-12 border-b border-gray-100 dark:border-gray-800">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
                <div>
                    <span class="text-sm font-bold tracking-widest uppercase text-editorial-teal mb-2 block">Collection</span>
                    <h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-editorial-dark dark:text-white leading-[0.9]">
                        KATALOG <br><span class="text-editorial-terracotta">PRODUK</span>
                    </h1>
                </div>
                <div class="w-full lg:w-auto">
                    <p class="text-lg text-gray-500 max-w-md text-left lg:text-right mb-3">
                        Curated playthings for the modern imagination. Explore our collection and refine results with filters.
                    </p>
                    <p class="text-sm font-medium text-editorial-dark dark:text-white text-left lg:text-right">
                        Showing <span class="font-bold">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span>
                        of <span class="font-bold">{{ $products->total() }}</span> products
                    </p>
                </div>
            </div>

            <form action="{{ route('products') }}" class="flex flex-col lg:flex-row gap-4 lg:gap-6 border-y border-gray-100 dark:border-gray-800 py-4" method="GET">
                <div class="flex-1">
                    <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2" for="search">Search</label>
                    <input class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-editorial-teal focus:ring-editorial-teal/30" id="search" name="search" placeholder="Search product name..." type="text" value="{{ $searchTerm }}">
                </div>

                <div class="w-full lg:w-64">
                    <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2" for="category">Category</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-editorial-teal focus:ring-editorial-teal/30" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option @selected($selectedCategory === $category->slug) value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-56">
                    <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2" for="sort">Sort</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-editorial-teal focus:ring-editorial-teal/30" id="sort" name="sort">
                        <option @selected($selectedSort === 'featured') value="featured">Featured</option>
                        <option @selected($selectedSort === 'price_asc') value="price_asc">Harga: Rendah ke Tinggi</option>
                        <option @selected($selectedSort === 'price_desc') value="price_desc">Harga: Tinggi ke Rendah</option>
                        <option @selected($selectedSort === 'newest') value="newest">Newest</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button class="rounded-lg bg-editorial-dark text-white px-5 py-2.5 text-sm font-bold uppercase tracking-wider hover:bg-editorial-terracotta transition-colors" type="submit">
                        Apply
                    </button>
                    <a class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-bold uppercase tracking-wider text-gray-600 hover:text-editorial-dark hover:border-editorial-dark transition-colors" href="{{ route('products') }}">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </section>

    <section class="px-6 py-12 lg:px-12 max-w-[1440px] mx-auto">
        @if ($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white/80 px-8 py-14 text-center">
                <h2 class="text-2xl font-bold mb-3">No products found</h2>
                <p class="text-gray-500 mb-6">Try changing the search keyword, category filter, or sort option.</p>
                <a class="inline-flex items-center gap-2 rounded-lg bg-editorial-dark text-white px-5 py-2.5 text-sm font-bold uppercase tracking-wider hover:bg-editorial-terracotta transition-colors" href="{{ route('products') }}">
                    Show all
                    <span class="material-symbols-outlined text-base">refresh</span>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[minmax(280px,auto)]">
                @foreach ($products as $product)
                    @php
                        $isFeaturedTile = $loop->first && $products->currentPage() === 1;
                    @endphp

                    <a class="{{ $isFeaturedTile ? 'lg:col-span-2' : '' }} rounded-xl overflow-hidden bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 group block" href="{{ route('products.show', $product->slug) }}">
                        <div class="{{ $isFeaturedTile ? 'h-[360px]' : 'h-[250px]' }} relative overflow-hidden bg-gray-100">
                            @if ($product->image_url)
                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $product->image_url }}');"></div>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-editorial-mustard/50 to-editorial-teal/35"></div>
                            @endif

                            @if ($product->is_featured)
                                <span class="absolute top-3 left-3 bg-editorial-mustard text-editorial-dark px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded">Featured</span>
                            @endif
                        </div>

                        <div class="p-5 flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-bold leading-tight text-editorial-dark dark:text-white">{{ $product->name }}</h3>
                                <p class="text-xs uppercase tracking-widest text-gray-500 mt-1">{{ $product->category?->name }}</p>
                                @if ($product->description)
                                    <p class="text-sm text-gray-500 mt-3 line-clamp-2">{{ $product->description }}</p>
                                @endif
                            </div>
                            <span class="text-sm md:text-base font-bold text-editorial-dark dark:text-white whitespace-nowrap">{{ $product->formatted_display_price ?: '-' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
@endsection
