@extends('web.layouts.app')

@section('title', 'About - Toy Boutique')

@section('content')
    @php
        $defaultHeroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCBF4d7DbcOwXt-Q9oI1SHheCqLPQVa39-cyPpUD42BksBC1WTLUCTvAuYethfojvPsrbCg-gYOlveGM_RfUBkVU7RWKSQoXRSuILesvJzpNVIl9ODBghOv1J5qU4diiE4Od46evLW9YrC6xpMmjjDg0IHrvz0VD1kOAga0hQWby5zslxVNGgPym1qXvOU5elBltAWNHQRuFYzJnuoAWNZ5Tb8zLb4bvrWQIwjburKILrPp0BVWqE79W3jRjrbdozALicWrYjhulisC';
        $defaultStoryImageOne = 'https://lh3.googleusercontent.com/aida-public/AB6AXuANsUxvlapDdwfifKCHibm9QXpsX_PZon22LWCh7_IkBalmnpUAjOa8kF258sfyVo9WSpUDHXayD2w-1Iaz91ItTCAVr4JuG26eEcKknXtl0PcK2TghfS2nVzRoaH5o2oAPhOfSAvsoOhjDwsQBZdL1hQuP1PjQSX7YtuCMf-iDkFN1p5u4CGV0ic7XfurDqv7-JRYZSKUlMbRrl29yC1BLOWjgS-ILMV1CN4sPfFt1aE1_TdyV5aml750kKjdNyr-lRNXKhVoVE4Ih';
        $defaultStoryImageTwo = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGMqzJgXaPi0MAf_ocFO_ywotygJ-_mMzKtaQC8OsyN2QT9x8qYjHJvcaaYq6uqDrlxr9NRn0FcVJtLnov39f-dil3vrlH2jYfDHG2QgsDHCv-BaybPHR-tyl2BV7qYywYBIpVlV-DZIM7PEa6Ibay4Cf-4p-tNor8kummhq1aoLoZt5YA784sJHzu41Jfm-WECmO3-e3kJ-huDvwwUYMKmPH_dMBY1Jqq2Zztq70tC5z7fcIEXXqHfdKOdg-TOufj09b-DBS2YPV6';

        $heroEyebrow = $heroSection?->eyebrow ?: 'Established 1985';
        $heroTitleMain = $heroSection?->title ?: 'TENTANG';
        $heroTitleHighlight = $heroSection?->highlight_text ?: 'KAMI';
        $heroSubtitle = $heroSection?->subtitle ?: 'More than a toy store. We are curators of childhood wonder, crafting memories one wooden block at a time.';
        $heroImageUrl = $heroImage?->image_url ?: $defaultHeroImage;
        $heroImageAlt = $heroImage?->image_alt ?: 'About hero image';

        $quoteIcon = 'format_quote';
        $quoteText = $quoteSection?->title ?: 'We believe that the best toys are 90% child and 10% toy.';
        $quoteAuthor = $quoteSection?->subtitle ?: 'Elena Rossi, Founder';

        $storyTitleMain = $storySection?->title ?: 'OUR';
        $storyTitleHighlight = $storySection?->highlight_text ?: 'STORY';
        $storyParagraphValues = $storyParagraphs
            ->map(fn ($paragraph) => $paragraph->description ?: $paragraph->title)
            ->filter()
            ->values();

        if ($storyParagraphValues->isEmpty()) {
            $storyParagraphValues = collect([
                'It began in a small garage in 1985. Tired of fragile toys flooding the market, our founder Elena set out to create something different.',
                'What started as simple wooden cars has grown into a curated boutique of dolls, puzzles, and playsets. Our core philosophy remains unchanged.',
                'Every piece in our boutique is hand-selected from artisans who share our values of sustainability and timeless design.',
            ]);
        }

        $storyImageOne = $storyImages->get(0)?->image_url ?: $defaultStoryImageOne;
        $storyImageOneAlt = $storyImages->get(0)?->image_alt ?: 'Story image one';
        $storyImageTwo = $storyImages->get(1)?->image_url ?: $defaultStoryImageTwo;
        $storyImageTwoAlt = $storyImages->get(1)?->image_alt ?: 'Story image two';

        $storyFeatureIcon = $storyFeature?->icon ?: 'handyman';
        $storyFeatureText = $storyFeature?->description ?: ($storyFeature?->title ?: 'Handmade with precision and love.');

        $renderedValueCards = $valueCards
            ->map(function ($card) {
                return [
                    'icon' => $card->icon ?: 'verified',
                    'title' => $card->title ?: 'Untitled Value',
                    'description' => $card->description ?: '',
                    'accent_color' => $card->accent_color ?: 'teal',
                ];
            })
            ->filter(fn ($card) => $card['title'] !== '' || $card['description'] !== '')
            ->values();

        if ($renderedValueCards->isEmpty()) {
            $renderedValueCards = collect([
                [
                    'icon' => 'forest',
                    'title' => 'Sustainable',
                    'description' => 'We prioritize renewable materials like FSC-certified wood and organic cotton.',
                    'accent_color' => 'teal',
                ],
                [
                    'icon' => 'palette',
                    'title' => 'Artistic',
                    'description' => 'Toys should be beautiful enough to live on your shelf, not just in a toy box.',
                    'accent_color' => 'mustard',
                ],
                [
                    'icon' => 'verified',
                    'title' => 'Safe',
                    'description' => 'Safety is non-negotiable: non-toxic paints and rigorous finishing standards.',
                    'accent_color' => 'terracotta',
                ],
            ]);
        }
    @endphp

    <section class="relative w-full px-6 py-12 lg:px-12 lg:pt-24 lg:pb-24 overflow-hidden">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 flex flex-col gap-8 lg:pr-12">
                <div class="flex items-center gap-4">
                    <span class="h-px w-12 bg-editorial-terracotta"></span>
                    <span class="text-editorial-terracotta font-bold uppercase tracking-[0.2em] text-sm">{{ $heroEyebrow }}</span>
                </div>
                <h1 class="text-6xl md:text-8xl lg:text-9xl font-black leading-[0.85] tracking-tighter text-editorial-dark dark:text-white">
                    {{ $heroTitleMain }} <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-editorial-terracotta to-editorial-mustard italic font-serif pr-4">{{ $heroTitleHighlight }}</span>
                </h1>
                <p class="text-xl md:text-2xl font-light leading-relaxed max-w-xl text-gray-600 dark:text-gray-300 border-l-4 border-editorial-mustard pl-6 mt-4">
                    {{ $heroSubtitle }}
                </p>
            </div>
            <div class="lg:col-span-5 relative mt-10 lg:mt-0">
                <div class="relative aspect-[4/5] w-full rounded-tl-[80px] rounded-br-[20px] overflow-hidden shadow-2xl">
                    <img alt="{{ $heroImageAlt }}" class="h-full w-full object-cover" src="{{ $heroImageUrl }}">
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-editorial-dark text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <span class="material-symbols-outlined text-6xl text-editorial-mustard mb-6">{{ $quoteIcon }}</span>
            <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif italic leading-tight mb-8">
                "{{ $quoteText }}"
            </h2>
            <p class="font-bold tracking-widest uppercase text-editorial-mustard">{{ $quoteAuthor }}</p>
        </div>
    </section>

    <section class="py-24 px-6 lg:px-12 max-w-[1440px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
            <div class="lg:col-span-5 flex flex-col justify-center order-2 lg:order-1">
                <h2 class="text-5xl lg:text-7xl font-bold mb-8 text-editorial-dark dark:text-white">{{ $storyTitleMain }} <span class="text-editorial-teal italic font-serif">{{ $storyTitleHighlight }}</span></h2>
                @if ($storySection?->subtitle)
                    <p class="mb-8 max-w-xl text-lg leading-relaxed text-gray-600 dark:text-gray-300">{{ $storySection->subtitle }}</p>
                @endif
                <div class="space-y-6 text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    @foreach ($storyParagraphValues as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-7 relative order-1 lg:order-2">
                <div class="grid grid-cols-2 gap-4 md:gap-8">
                    <div class="space-y-4 md:space-y-8 mt-12">
                        <div class="relative aspect-[3/4] w-full rounded-2xl overflow-hidden bg-gray-200">
                            <img alt="{{ $storyImageOneAlt }}" class="h-full w-full object-cover" src="{{ $storyImageOne }}">
                        </div>
                    </div>
                    <div class="space-y-4 md:space-y-8">
                        <div class="bg-editorial-terracotta p-6 rounded-2xl text-white mt-0 md:mt-20">
                            <span class="material-symbols-outlined text-4xl mb-2">{{ $storyFeatureIcon }}</span>
                            <p class="font-serif text-xl">{{ $storyFeatureText }}</p>
                        </div>
                        <div class="relative aspect-[3/4] w-full rounded-2xl overflow-hidden bg-gray-200">
                            <img alt="{{ $storyImageTwoAlt }}" class="h-full w-full object-cover" src="{{ $storyImageTwo }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 lg:px-12 max-w-[1440px] mx-auto">
        @if ($valuesSection?->title)
            <h2 class="mb-10 text-4xl md:text-5xl font-bold tracking-tighter text-editorial-dark dark:text-white">{{ $valuesSection->title }}</h2>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($renderedValueCards as $card)
                @php
                    $accentColor = match ($card['accent_color']) {
                        'mustard' => 'text-editorial-mustard bg-editorial-mustard/20',
                        'terracotta' => 'text-editorial-terracotta bg-editorial-terracotta/10',
                        default => 'text-editorial-teal bg-editorial-teal/10',
                    };
                @endphp
                <article class="group relative bg-white dark:bg-background-dark p-8 rounded-xl border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-all duration-300">
                    <div class="h-16 w-16 rounded-full flex items-center justify-center mb-6 {{ $accentColor }}">
                        <span class="material-symbols-outlined text-3xl">{{ $card['icon'] }}</span>
                    </div>
                    <h3 class="text-3xl font-serif font-bold mb-4 text-editorial-dark dark:text-white">{{ $card['title'] }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $card['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="py-24 px-6 lg:px-12 bg-editorial-teal relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10 text-white">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-serif">Ready to start your collection?</h2>
            <p class="text-xl md:text-2xl font-light mb-10 text-white/80 max-w-2xl mx-auto">
                Join us in bringing back the magic of tangible play. Explore our latest arrivals today.
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a class="bg-editorial-mustard text-editorial-dark px-8 py-4 rounded-full font-bold hover:bg-white transition-colors uppercase tracking-wider text-sm" href="/products">
                    Shop New Arrivals
                </a>
                <a class="bg-transparent border border-white text-white px-8 py-4 rounded-full font-bold hover:bg-white hover:text-editorial-teal transition-colors uppercase tracking-wider text-sm" href="/gallery">
                    Explore Gallery
                </a>
            </div>
        </div>
    </section>
@endsection
