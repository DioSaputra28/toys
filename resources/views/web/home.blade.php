@extends('web.layouts.app')

@section('title', 'Beranda - Toy Boutique')

@section('content')
    @if ($duplicateSectionKeys->isNotEmpty())
        <div class="hidden" aria-hidden="true" data-duplicate-section-keys="{{ $duplicateSectionKeys->implode(',') }}"></div>
    @endif

    @forelse ($sections as $section)
        @switch($section->section_key)
            @case('hero')
                @php
                    $heroItems = $section->items->values();
                    $heroHasItems = $heroItems->isNotEmpty();
                    $heroSlides = $heroHasItems ? $heroItems : collect([(object) ['title' => null, 'image_url' => null, 'image_alt' => null, 'link_url' => null]]);
                    $hasMultipleHeroSlides = $heroSlides->count() > 1;
                    $heroTitle = $section->title ?: 'PLAY TIME REIMAGINED';
                    $heroSubtitle = $section->subtitle ?: 'Discover our handcrafted collection of sustainable wooden sets and vintage-inspired dolls.';
                    $singleHeroSlide = $heroSlides->first();
                @endphp
                @if ($hasMultipleHeroSlides)
                    <section class="relative w-full px-0 py-0 overflow-hidden">
                        <div aria-label="Hero slider" aria-roledescription="carousel" class="relative h-[68vh] min-h-[480px] max-h-[860px] w-full" data-hero-slider="hero-{{ $section->id }}" role="region" tabindex="0">
                            <div class="absolute inset-0 overflow-hidden">
                                @foreach ($heroSlides as $heroSlide)
                                    <div aria-hidden="{{ $loop->first ? 'false' : 'true' }}" aria-label="Slide {{ $loop->iteration }} of {{ $heroSlides->count() }}" aria-roledescription="slide" class="absolute inset-0 transition-opacity duration-500 {{ $loop->first ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}" data-hero-slide="{{ $loop->index }}" role="group">
                                        @if ($heroSlide->link_url)
                                            <a class="block h-full w-full" href="{{ $heroSlide->link_url }}">
                                                @if ($heroSlide->image_url)
                                                    <img alt="{{ $heroSlide->image_alt ?: ($heroSlide->title ?: 'Hero image') }}" class="h-full w-full object-cover" src="{{ $heroSlide->image_url }}">
                                                @else
                                                    <div class="h-full w-full bg-gradient-to-br from-editorial-mustard via-primary to-editorial-teal"></div>
                                                @endif
                                                <span class="sr-only">{{ $heroSlide->title ?: 'Open featured slide' }}</span>
                                            </a>
                                        @else
                                            @if ($heroSlide->image_url)
                                                <img alt="{{ $heroSlide->image_alt ?: ($heroSlide->title ?: 'Hero image') }}" class="h-full w-full object-cover" src="{{ $heroSlide->image_url }}">
                                            @else
                                                <div class="h-full w-full bg-gradient-to-br from-editorial-mustard via-primary to-editorial-teal"></div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="pointer-events-none absolute inset-y-0 left-0 right-0 z-20 flex items-center justify-between px-4 md:px-8">
                                <button aria-label="Previous slide" class="pointer-events-auto h-11 w-11 rounded-full bg-white/80 text-editorial-dark shadow-lg backdrop-blur-sm transition-colors hover:bg-white" data-hero-prev type="button">
                                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                                </button>
                                <button aria-label="Next slide" class="pointer-events-auto h-11 w-11 rounded-full bg-white/80 text-editorial-dark shadow-lg backdrop-blur-sm transition-colors hover:bg-white" data-hero-next type="button">
                                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </button>
                            </div>

                            <div aria-label="Hero slide navigation" class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2 rounded-full bg-black/25 px-3 py-2 backdrop-blur-sm" role="tablist">
                                @foreach ($heroSlides as $heroSlide)
                                    <button aria-label="Go to slide {{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="h-2.5 w-2.5 rounded-full transition-all {{ $loop->first ? 'bg-white w-6' : 'bg-white/60' }}" data-hero-dot="{{ $loop->index }}" role="tab" tabindex="{{ $loop->first ? '0' : '-1' }}" type="button"></button>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @else
                    <section class="relative w-full px-6 py-12 lg:px-12 lg:py-20 overflow-hidden">
                        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                            <div class="lg:col-span-5 relative z-10 flex flex-col gap-6">
                                <span class="inline-block px-3 py-1 bg-editorial-mustard text-editorial-dark text-xs font-bold uppercase tracking-widest w-fit rounded-sm">Featured</span>
                                <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-[0.9] tracking-tighter text-editorial-dark dark:text-white">
                                    {{ $heroTitle }}
                                </h1>
                                <p class="text-lg md:text-xl font-light leading-relaxed max-w-md text-gray-600 dark:text-gray-300">
                                    {{ $heroSubtitle }}
                                </p>
                                @if ($singleHeroSlide?->link_url)
                                    <a class="inline-flex items-center gap-2 bg-editorial-dark text-white px-6 py-3 rounded-lg font-bold hover:bg-editorial-terracotta transition-colors w-fit" href="{{ $singleHeroSlide->link_url }}">
                                        Explore
                                        <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                    </a>
                                @endif
                            </div>

                            <div class="lg:col-span-7 relative h-[560px] w-full">
                                <div class="absolute top-10 right-0 w-3/4 h-5/6 bg-editorial-teal/20 rounded-tl-[100px] rounded-br-[40px] -z-10"></div>
                                <div class="absolute bottom-0 left-10 w-64 h-64 bg-editorial-mustard rounded-full mix-blend-multiply opacity-80"></div>

                                <div class="absolute top-0 right-0 md:right-10 w-full md:w-4/5 h-full rounded-2xl overflow-hidden shadow-2xl">
                                    @if ($singleHeroSlide?->link_url)
                                        <a class="block h-full w-full" href="{{ $singleHeroSlide->link_url }}">
                                            @if ($singleHeroSlide?->image_url)
                                                <img alt="{{ $singleHeroSlide->image_alt ?: ($singleHeroSlide->title ?: 'Hero image') }}" class="w-full h-full object-cover" src="{{ $singleHeroSlide->image_url }}">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-editorial-mustard via-primary to-editorial-teal"></div>
                                            @endif
                                        </a>
                                    @else
                                        @if ($singleHeroSlide?->image_url)
                                            <img alt="{{ $singleHeroSlide->image_alt ?: ($singleHeroSlide->title ?: 'Hero image') }}" class="w-full h-full object-cover" src="{{ $singleHeroSlide->image_url }}">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-editorial-mustard via-primary to-editorial-teal"></div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                @break

            @case('marquee')
                @php
                    $marqueeItems = $section->items->pluck('title')->filter()->values();
                @endphp
                @if ($marqueeItems->isNotEmpty())
                    <div class="w-full bg-editorial-dark text-white overflow-hidden py-4 rotate-1 scale-105 my-10 border-y-4 border-primary">
                        <div class="whitespace-nowrap flex gap-10 text-2xl font-bold uppercase tracking-widest px-6 lg:px-12">
                            @foreach ($marqueeItems as $title)
                                <span>&bull; {{ $title }}</span>
                            @endforeach
                            @foreach ($marqueeItems as $title)
                                <span>&bull; {{ $title }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                @break

            @case('editorial_picks')
                @php
                    $editorialItems = $section->items->take(7);
                    $editorialCount = $editorialItems->count();
                @endphp
                <section class="px-6 py-12 lg:px-12 max-w-[1440px] mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                        <h2 class="text-4xl md:text-6xl font-bold tracking-tighter text-editorial-dark dark:text-white max-w-2xl">
                            {{ $section->title ?: "EDITOR'S PICKS" }}
                        </h2>
                        @if ($section->subtitle)
                            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-sm text-right">{{ $section->subtitle }}</p>
                        @endif
                    </div>

                    @if ($editorialItems->isEmpty())
                        <div class="rounded-xl border border-dashed border-gray-300 bg-white/80 p-8 text-gray-500">
                            This section has no active items yet.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[minmax(120px,auto)]">
                            @foreach ($editorialItems as $item)
                            @php
                                $tileClass = match (true) {
                                    $editorialCount === 1 => 'md:col-span-12',
                                    $editorialCount === 2 => $loop->first ? 'md:col-span-8' : 'md:col-span-4',
                                    default => $loop->first ? 'md:col-span-6 md:row-span-2' : 'md:col-span-3',
                                };

                                $heightClass = match (true) {
                                    $editorialCount === 1 => 'h-[460px]',
                                    $editorialCount === 2 && $loop->first => 'h-[420px]',
                                    default => $loop->first ? 'h-[420px]' : 'h-[240px]',
                                };
                            @endphp
                            <article class="{{ $tileClass }} rounded-xl overflow-hidden bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 group">
                                <div class="{{ $heightClass }} bg-gray-200 relative overflow-hidden">
                                    @if ($item->image_url)
                                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $item->image_url }}');"></div>
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-editorial-mustard/60 to-editorial-teal/30"></div>
                                    @endif
                                </div>
                                <div class="p-5 flex items-center justify-between gap-3">
                                    <h3 class="text-lg md:text-xl font-bold">{{ $item->title ?: 'Untitled item' }}</h3>
                                    @if ($item->link_url)
                                        <a class="text-sm font-bold uppercase tracking-wider text-editorial-terracotta hover:text-editorial-dark" href="{{ $item->link_url }}">Open</a>
                                    @endif
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @endif

                    @if ($section->items->count() > $editorialItems->count())
                        <p class="mt-4 text-sm text-gray-500">Showing first {{ $editorialItems->count() }} items from this section.</p>
                    @endif
                </section>
                @break

            @case('quote')
                @php
                    $quoteText = $section->title ?: 'Play is the highest form of research.';
                    $quoteBy = $section->subtitle ?: 'Toy Boutique';
                @endphp
                <section class="py-16 px-6 lg:px-12">
                    <div class="max-w-[960px] mx-auto bg-editorial-teal text-white p-10 rounded-2xl text-center relative overflow-hidden">
                        <span class="material-symbols-outlined text-6xl opacity-20 absolute top-4 left-4">format_quote</span>
                        <p class="text-3xl font-serif italic leading-snug">"{{ $quoteText }}"</p>
                        <p class="mt-4 font-bold text-sm tracking-widest uppercase opacity-80">{{ $quoteBy }}</p>
                    </div>
                </section>
                @break

            @case('story_feature')
                @php
                    $storyFeatureItems = $section->items->take(4)->values();
                    $storyItem = $storyFeatureItems->first();
                    $supportingStoryItems = $storyFeatureItems->slice(1)->values();
                @endphp
                <section class="py-20 bg-white dark:bg-background-dark overflow-hidden">
                    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                        <div class="{{ $supportingStoryItems->isNotEmpty() ? 'lg:col-span-7' : 'lg:col-span-12' }}">
                            <div class="h-full bg-editorial-mustard/30 p-6 md:p-8 lg:p-10 rounded-tl-[80px] rounded-br-[80px]" data-story-feature-highlight>
                                <div class="grid h-full grid-cols-1 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-8 items-center">
                                    <div class="aspect-[4/5] w-full rounded-3xl overflow-hidden shadow-xl bg-gray-200">
                                        @if ($storyItem?->image_url)
                                            <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ $storyItem->image_url }}');"></div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-editorial-mustard/80 to-editorial-terracotta/70"></div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="mb-4 inline-flex w-fit items-center gap-3 rounded-full bg-white/80 px-4 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-editorial-dark shadow-sm">
                                            <span class="h-2 w-2 rounded-full bg-editorial-terracotta"></span>
                                            Featured Story
                                        </span>
                                        <h2 class="text-4xl md:text-6xl font-bold mb-6 text-editorial-dark dark:text-white">{{ $section->title ?: 'CRAFTED FOR MEMORIES' }}</h2>
                                        @if ($section->subtitle)
                                            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">{{ $section->subtitle }}</p>
                                        @endif
                                        @if ($storyItem?->title)
                                            <h3 class="text-2xl md:text-3xl font-bold text-editorial-dark dark:text-white leading-tight">
                                                {{ $storyItem->title }}
                                            </h3>
                                        @endif
                                        @if ($storyItem?->link_url)
                                            <a class="mt-6 inline-flex items-center gap-2 bg-editorial-dark text-white px-6 py-3 rounded-lg font-bold hover:bg-editorial-terracotta transition-colors w-fit" href="{{ $storyItem->link_url }}">
                                                Read Story
                                                <span class="material-symbols-outlined text-base">arrow_right_alt</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($supportingStoryItems->isNotEmpty())
                            <div class="lg:col-span-5">
                                <div class="grid h-full grid-cols-1 gap-4 auto-rows-fr" data-story-feature-supporting>
                                    @foreach ($supportingStoryItems as $supportingStoryItem)
                                        @php
                                            $storyCardTag = $supportingStoryItem->link_url ? 'a' : 'div';
                                            $storyCardAttributes = $supportingStoryItem->link_url ? 'href="'.$supportingStoryItem->link_url.'"' : '';
                                        @endphp
                                        <{{ $storyCardTag }} {!! $storyCardAttributes !!} class="group relative overflow-hidden rounded-[1.75rem] border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-[0_20px_45px_-28px_rgba(41,50,65,0.35)]">
                                            <div class="grid h-full grid-cols-[120px_minmax(0,1fr)] items-stretch">
                                                <div class="relative min-h-[160px] bg-gray-200">
                                                    @if ($supportingStoryItem->image_url)
                                                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $supportingStoryItem->image_url }}');"></div>
                                                    @else
                                                        <div class="absolute inset-0 bg-gradient-to-br from-editorial-teal/80 to-editorial-terracotta/70"></div>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col justify-between gap-5 p-5">
                                                    <div>
                                                        <div class="mb-3 h-1.5 w-12 rounded-full bg-editorial-teal/70"></div>
                                                        <h3 class="text-xl font-bold leading-tight text-editorial-dark dark:text-white">
                                                            {{ $supportingStoryItem->title ?: 'Story highlight' }}
                                                        </h3>
                                                    </div>
                                                    @if ($supportingStoryItem->link_url)
                                                        <span class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-[0.22em] text-editorial-terracotta">
                                                            Explore
                                                            <span class="material-symbols-outlined text-base">north_east</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </{{ $storyCardTag }}>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
                @break

            @case('category_links')
                <section class="py-20 px-6 lg:px-12 max-w-[1440px] mx-auto">
                    @if ($section->title)
                        <h2 class="text-4xl md:text-6xl font-bold tracking-tighter mb-10 text-editorial-dark dark:text-white">{{ $section->title }}</h2>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse ($categoryPicks as $pick)
                            <a class="group relative block aspect-[3/4] overflow-hidden rounded-2xl" href="/products?category={{ $pick->category->slug }}">
                                @if ($pick->cover_image_url)
                                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $pick->cover_image_url }}');"></div>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-editorial-teal to-editorial-terracotta"></div>
                                @endif
                                <div class="absolute inset-0 bg-black/35 group-hover:bg-black/45 transition-colors"></div>
                                <div class="absolute bottom-0 left-0 w-full p-8 text-white">
                                    <h3 class="text-4xl md:text-5xl font-black uppercase tracking-tighter">{{ $pick->category->name }}</h3>
                                    <p class="mt-3 text-sm uppercase tracking-widest opacity-85">Shop Collection</p>
                                </div>
                            </a>
                        @empty
                            <div class="md:col-span-3 p-8 rounded-xl border border-dashed border-gray-300 text-gray-500 bg-white/80">
                                No active category picks yet.
                            </div>
                        @endforelse
                    </div>
                </section>
                @break

            @default
                <section class="px-6 lg:px-12 py-12">
                    <div class="max-w-[1440px] mx-auto p-6 rounded-xl border border-dashed border-gray-300 bg-white/80 text-gray-600">
                        Section key <strong>{{ $section->section_key }}</strong> is not supported by the current homepage renderer yet.
                    </div>
                </section>
        @endswitch

        @if ($betweenSectionBanners->has($section->section_key))
            <section class="px-6 lg:px-12 py-8">
                <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($betweenSectionBanners[$section->section_key] as $banner)
                        <a class="relative overflow-hidden rounded-xl min-h-[180px] group" href="{{ $banner->link_url ?: '#' }}">
                            @if ($banner->image_url)
                                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $banner->image_url }}');"></div>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-r from-editorial-dark to-editorial-teal"></div>
                            @endif
                            <div class="absolute inset-0 bg-black/35"></div>
                            <div class="relative p-6 text-white h-full flex items-end">
                                <h3 class="text-xl md:text-2xl font-bold tracking-tight">{{ $banner->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($betweenSectionPosters->has($section->section_key))
            <section class="px-6 lg:px-12 py-8">
                <div class="max-w-[1440px] mx-auto" data-poster-carousel="{{ $section->section_key }}">
                    <span class="hidden" aria-hidden="true">{{ 'data-poster-carousel="'.$section->section_key.'"' }}</span>
                    <span class="hidden" aria-hidden="true">data-poster-free-scroll</span>
                    <div class="-mx-2 flex gap-5 overflow-x-auto px-2 pb-4 poster-free-scroll cursor-grab select-none touch-pan-y" data-poster-track data-poster-free-scroll tabindex="0">
                        @foreach ($betweenSectionPosters[$section->section_key] as $poster)
                            @php
                                $ratioClass = match ($poster->size_variant) {
                                    'full' => 'aspect-square',
                                    'landscape' => 'aspect-[5/4]',
                                    default => 'aspect-[4/5]',
                                };
                                $offsetClass = match ($loop->index % 3) {
                                    1 => 'lg:-translate-y-4',
                                    2 => 'lg:translate-y-3',
                                    default => '',
                                };
                                $slideTag = $poster->link_url ? 'a' : 'div';
                                $slideAttributes = $poster->link_url ? 'href="'.$poster->link_url.'"' : '';
                            @endphp
                            <{{ $slideTag }} {!! $slideAttributes !!} class="group relative block min-w-0 shrink-0 basis-[82%] transition-transform duration-500 sm:basis-[48%] lg:basis-[31.5%] {{ $offsetClass }}" data-poster-slide data-poster-style="{{ $poster->display_style }}" draggable="false">
                                <span class="hidden" aria-hidden="true">{{ 'data-poster-style="'.$poster->display_style.'"' }}</span>
                                <div class="relative overflow-hidden rounded-[2rem] border border-gray-100 bg-white shadow-[0_20px_48px_-28px_rgba(41,50,65,0.38)] dark:border-gray-800 dark:bg-gray-900">
                                    <div class="relative {{ $ratioClass }} overflow-hidden bg-gray-200">
                                        @if ($poster->image_url)
                                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $poster->image_url }}');"></div>
                                        @else
                                            <div class="absolute inset-0 bg-gradient-to-br from-editorial-dark to-editorial-terracotta"></div>
                                        @endif

                                        @if ($poster->display_style === 'overlay_text')
                                            <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/30 via-black/5 to-transparent"></div>
                                            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-editorial-dark/70 via-editorial-dark/15 to-transparent"></div>
                                            <div class="relative flex h-full w-full items-end p-6 text-white">
                                                <h3 class="max-w-[14rem] text-2xl md:text-3xl font-black tracking-tight">{{ $poster->title }}</h3>
                                            </div>
                                        @else
                                            <span class="sr-only">{{ $poster->title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </{{ $slideTag }}>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @empty
        <section class="px-6 lg:px-12 py-20">
            <div class="max-w-[960px] mx-auto p-10 rounded-2xl border border-dashed border-gray-300 text-center bg-white/80">
                <h2 class="text-3xl font-bold mb-3">Homepage content is not set yet</h2>
                <p class="text-gray-600">Please configure sections and items from the admin panel first.</p>
            </div>
        </section>
    @endforelse

    @if ($orphanBetweenBanners->isNotEmpty())
        <section class="px-6 lg:px-12 pb-14">
            <div class="max-w-[1440px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($orphanBetweenBanners as $banner)
                    <a class="relative overflow-hidden rounded-xl min-h-[180px] group" href="{{ $banner->link_url ?: '#' }}">
                        @if ($banner->image_url)
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $banner->image_url }}');"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-r from-editorial-dark to-editorial-teal"></div>
                        @endif
                        <div class="absolute inset-0 bg-black/35"></div>
                        <div class="relative p-6 text-white h-full flex items-end">
                            <h3 class="text-xl md:text-2xl font-bold tracking-tight">{{ $banner->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($sections->isNotEmpty() && $orphanBetweenPosters->isNotEmpty())
        <section class="px-6 lg:px-12 pb-14">
            <div class="max-w-[1440px] mx-auto" data-poster-carousel="orphan">
                <span class="hidden" aria-hidden="true">{{ 'data-poster-carousel="orphan"' }}</span>
                <span class="hidden" aria-hidden="true">data-poster-free-scroll</span>
                <div class="-mx-2 flex gap-5 overflow-x-auto px-2 pb-4 poster-free-scroll cursor-grab select-none touch-pan-y" data-poster-track data-poster-free-scroll tabindex="0">
                    @foreach ($orphanBetweenPosters as $poster)
                        @php
                            $ratioClass = match ($poster->size_variant) {
                                'full' => 'aspect-square',
                                'landscape' => 'aspect-[5/4]',
                                default => 'aspect-[4/5]',
                            };
                            $offsetClass = match ($loop->index % 3) {
                                1 => 'lg:-translate-y-4',
                                2 => 'lg:translate-y-3',
                                default => '',
                            };
                            $slideTag = $poster->link_url ? 'a' : 'div';
                            $slideAttributes = $poster->link_url ? 'href="'.$poster->link_url.'"' : '';
                        @endphp
                        <{{ $slideTag }} {!! $slideAttributes !!} class="group relative block min-w-0 shrink-0 basis-[82%] transition-transform duration-500 sm:basis-[48%] lg:basis-[31.5%] {{ $offsetClass }}" data-poster-slide data-poster-style="{{ $poster->display_style }}" draggable="false">
                            <span class="hidden" aria-hidden="true">{{ 'data-poster-style="'.$poster->display_style.'"' }}</span>
                            <div class="relative overflow-hidden rounded-[2rem] border border-gray-100 bg-white shadow-[0_20px_48px_-28px_rgba(41,50,65,0.38)] dark:border-gray-800 dark:bg-gray-900">
                                <div class="relative {{ $ratioClass }} overflow-hidden bg-gray-200">
                                    @if ($poster->image_url)
                                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $poster->image_url }}');"></div>
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-editorial-dark to-editorial-terracotta"></div>
                                    @endif

                                    @if ($poster->display_style === 'overlay_text')
                                        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/30 via-black/5 to-transparent"></div>
                                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-editorial-dark/70 via-editorial-dark/15 to-transparent"></div>
                                        <div class="relative flex h-full w-full items-end p-6 text-white">
                                            <h3 class="text-2xl md:text-3xl font-black tracking-tight">{{ $poster->title }}</h3>
                                        </div>
                                    @else
                                        <span class="sr-only">{{ $poster->title }}</span>
                                    @endif
                                </div>
                            </div>
                        </{{ $slideTag }}>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <style>
        .poster-free-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .poster-free-scroll::-webkit-scrollbar {
            display: none;
        }

        .touch-pan-y {
            touch-action: pan-y;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
                const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
                const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
                const prevButton = slider.querySelector('[data-hero-prev]');
                const nextButton = slider.querySelector('[data-hero-next]');

                if (slides.length <= 1) {
                    return;
                }

                let currentIndex = 0;
                let autoPlayTimer = null;

                const focusableSelector = 'a, button, input, select, textarea, [tabindex]';

                const render = (index) => {
                    currentIndex = (index + slides.length) % slides.length;

                    slides.forEach((slide, slideIndex) => {
                        const isActive = slideIndex === currentIndex;

                        slide.classList.toggle('opacity-100', isActive);
                        slide.classList.toggle('pointer-events-auto', isActive);
                        slide.classList.toggle('opacity-0', !isActive);
                        slide.classList.toggle('pointer-events-none', !isActive);
                        slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');

                        slide.querySelectorAll(focusableSelector).forEach((focusableElement) => {
                            if (focusableElement === slider) {
                                return;
                            }

                            if (!focusableElement.hasAttribute('data-hero-tabindex')) {
                                const currentTabindex = focusableElement.getAttribute('tabindex');

                                if (currentTabindex !== null) {
                                    focusableElement.setAttribute('data-hero-tabindex', currentTabindex);
                                }
                            }

                            if (isActive) {
                                if (focusableElement.hasAttribute('data-hero-tabindex')) {
                                    focusableElement.setAttribute('tabindex', focusableElement.getAttribute('data-hero-tabindex') || '0');
                                } else {
                                    focusableElement.removeAttribute('tabindex');
                                }
                            } else {
                                focusableElement.setAttribute('tabindex', '-1');
                            }
                        });
                    });

                    dots.forEach((dot, dotIndex) => {
                        const isActive = dotIndex === currentIndex;

                        dot.classList.toggle('bg-white', isActive);
                        dot.classList.toggle('w-6', isActive);
                        dot.classList.toggle('bg-white/60', !isActive);
                        dot.classList.toggle('w-2.5', !isActive);
                        dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
                        dot.setAttribute('tabindex', isActive ? '0' : '-1');
                    });
                };

                const startAutoPlay = () => {
                    stopAutoPlay();
                    autoPlayTimer = window.setInterval(() => {
                        render(currentIndex + 1);
                    }, 6000);
                };

                const stopAutoPlay = () => {
                    if (autoPlayTimer) {
                        window.clearInterval(autoPlayTimer);
                        autoPlayTimer = null;
                    }
                };

                prevButton?.addEventListener('click', () => {
                    render(currentIndex - 1);
                    startAutoPlay();
                });

                nextButton?.addEventListener('click', () => {
                    render(currentIndex + 1);
                    startAutoPlay();
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        render(index);
                        startAutoPlay();
                    });

                    dot.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            render(index);
                            startAutoPlay();
                        }
                    });
                });

                slider.addEventListener('mouseenter', stopAutoPlay);
                slider.addEventListener('mouseleave', startAutoPlay);
                slider.addEventListener('focusin', stopAutoPlay);
                slider.addEventListener('focusout', (event) => {
                    const nextTarget = event.relatedTarget;

                    if (nextTarget instanceof Node && slider.contains(nextTarget)) {
                        return;
                    }

                    startAutoPlay();
                });

                slider.addEventListener('keydown', (event) => {
                    if (event.key === 'ArrowLeft') {
                        event.preventDefault();
                        render(currentIndex - 1);
                        startAutoPlay();
                    }

                    if (event.key === 'ArrowRight') {
                        event.preventDefault();
                        render(currentIndex + 1);
                        startAutoPlay();
                    }
                });

                render(0);
                startAutoPlay();
            });

            document.querySelectorAll('[data-poster-carousel]').forEach((carousel) => {
                const track = carousel.querySelector('[data-poster-track]');
                const slides = Array.from(carousel.querySelectorAll('[data-poster-slide]'));

                if (!(track instanceof HTMLElement) || slides.length <= 1) {
                    return;
                }

                let autoRotateTimer = null;
                let touchStartX = 0;
                let isPointerDown = false;
                let dragStartX = 0;
                let scrollStartLeft = 0;
                let dragDistance = 0;
                let suppressClick = false;

                const getClosestSlideIndex = () => {
                    const currentScrollLeft = track.scrollLeft;
                    let closestIndex = 0;
                    let closestDistance = Number.POSITIVE_INFINITY;

                    slides.forEach((slide, index) => {
                        const distance = Math.abs(slide.offsetLeft - currentScrollLeft);

                        if (distance < closestDistance) {
                            closestDistance = distance;
                            closestIndex = index;
                        }
                    });

                    return closestIndex;
                };

                const scrollToSlide = (index, behavior = 'smooth') => {
                    const nextIndex = (index + slides.length) % slides.length;

                    track.scrollTo({
                        left: slides[nextIndex].offsetLeft,
                        behavior,
                    });
                };

                const stopAutoRotate = () => {
                    if (autoRotateTimer) {
                        window.clearInterval(autoRotateTimer);
                        autoRotateTimer = null;
                    }
                };

                const startAutoRotate = () => {
                    stopAutoRotate();
                    autoRotateTimer = window.setInterval(() => {
                        scrollToSlide(getClosestSlideIndex() + 1);
                    }, 5000);
                };

                track.addEventListener('mouseenter', stopAutoRotate);
                track.addEventListener('mouseleave', startAutoRotate);
                track.addEventListener('focusin', stopAutoRotate);
                track.addEventListener('focusout', (event) => {
                    const nextTarget = event.relatedTarget;

                    if (nextTarget instanceof Node && carousel.contains(nextTarget)) {
                        return;
                    }

                    startAutoRotate();
                });

                track.addEventListener('pointerdown', (event) => {
                    if (event.pointerType === 'mouse' && event.button !== 0) {
                        return;
                    }

                    isPointerDown = true;
                    dragStartX = event.clientX;
                    scrollStartLeft = track.scrollLeft;
                    dragDistance = 0;
                    suppressClick = false;
                    track.classList.remove('cursor-grab');
                    track.classList.add('cursor-grabbing');
                    track.setPointerCapture(event.pointerId);
                    stopAutoRotate();
                });

                track.addEventListener('pointermove', (event) => {
                    if (!isPointerDown) {
                        return;
                    }

                    const distance = event.clientX - dragStartX;
                    dragDistance = Math.max(dragDistance, Math.abs(distance));
                    track.scrollLeft = scrollStartLeft - distance;
                });

                const finishPointerDrag = (event) => {
                    if (!isPointerDown) {
                        return;
                    }

                    isPointerDown = false;
                    suppressClick = dragDistance > 8;
                    track.classList.remove('cursor-grabbing');
                    track.classList.add('cursor-grab');

                    if (event instanceof PointerEvent && track.hasPointerCapture(event.pointerId)) {
                        track.releasePointerCapture(event.pointerId);
                    }

                    startAutoRotate();
                };

                track.addEventListener('pointerup', finishPointerDrag);
                track.addEventListener('pointercancel', finishPointerDrag);
                track.addEventListener('dragstart', (event) => {
                    event.preventDefault();
                });
                track.addEventListener('click', (event) => {
                    if (! suppressClick) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    suppressClick = false;
                    dragDistance = 0;
                }, true);

                track.addEventListener('touchstart', (event) => {
                    touchStartX = event.touches[0]?.clientX ?? 0;
                    stopAutoRotate();
                }, { passive: true });

                track.addEventListener('touchend', (event) => {
                    const touchEndX = event.changedTouches[0]?.clientX ?? touchStartX;
                    const distance = touchStartX - touchEndX;

                    if (Math.abs(distance) <= 12) {
                        startAutoRotate();
                        return;
                    }

                    startAutoRotate();
                }, { passive: true });

                track.addEventListener('keydown', (event) => {
                    if (event.key === 'ArrowLeft') {
                        event.preventDefault();
                        scrollToSlide(getClosestSlideIndex() - 1);
                        startAutoRotate();
                    }

                    if (event.key === 'ArrowRight') {
                        event.preventDefault();
                        scrollToSlide(getClosestSlideIndex() + 1);
                        startAutoRotate();
                    }
                });

                startAutoRotate();
            });
        });
    </script>
@endsection
