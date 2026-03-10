@extends('web.layouts.app')

@section('title', 'Kontak - Toy Boutique')

@section('content')
    @php
        $siteSetting = app(\App\Settings\SiteSetting::class);

        $socialLinks = collect([
            ['label' => 'Instagram', 'url' => $siteSetting->instagram_url],
            ['label' => 'Facebook', 'url' => $siteSetting->facebook_url],
            ['label' => 'TikTok', 'url' => $siteSetting->tiktok_url],
            ['label' => 'YouTube', 'url' => $siteSetting->youtube_url],
            ['label' => 'Twitter / X', 'url' => $siteSetting->twitter_url],
            ['label' => 'LinkedIn', 'url' => $siteSetting->linkedin_url],
            ['label' => 'Threads', 'url' => $siteSetting->threads_url],
            ['label' => 'Other', 'url' => $siteSetting->other_social_url],
        ])->filter(fn (array $item): bool => filled($item['url']))->values();

        $hasLocation = filled($siteSetting->location);
        $hasOpeningHours = filled($siteSetting->opening_hours);
        $hasEmail = filled($siteSetting->contact_email);
        $hasPhone = filled($siteSetting->phone_number);
        $hasWhatsapp = filled($siteSetting->whatsapp_number);
        $hasSocialLinks = $socialLinks->isNotEmpty();
        $hasContactSidebar = $hasLocation || $hasOpeningHours || $hasEmail || $hasPhone || $hasWhatsapp || $hasSocialLinks;
        $hasMapEmbed = filled($siteSetting->map_embed_html);
        $phoneHref = $hasPhone ? preg_replace('/[^\d+]/', '', $siteSetting->phone_number) : null;
    @endphp

    <section class="pt-16 pb-8 px-6 lg:px-12 max-w-[1440px] mx-auto text-center">
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-editorial-dark dark:text-white uppercase">
            Kontak <span class="text-editorial-terracotta">Kami</span>
        </h1>
        <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto font-light">
            We love hearing from fellow play enthusiasts. Reach out for collaborations, inquiries, or just to say hello.
        </p>
    </section>

    <section class="w-full max-w-[1440px] mx-auto grid grid-cols-1 {{ $hasContactSidebar ? 'lg:grid-cols-2' : '' }} min-h-[640px]">
        <div class="bg-editorial-teal text-white p-10 lg:p-20 flex flex-col justify-center">
            <h2 class="text-4xl font-bold mb-2">Get in Touch</h2>
            <p class="mb-10 text-blue-100 font-light">Fill out the form below and we'll get back to you shortly.</p>
            <form class="space-y-8">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-blue-200">Name</label>
                    <input class="w-full bg-transparent border-0 border-b-2 border-blue-300/30 focus:border-white focus:ring-0 px-0 py-3 text-xl placeholder-blue-300/30" placeholder="Jane Doe" type="text">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-blue-200">Email</label>
                    <input class="w-full bg-transparent border-0 border-b-2 border-blue-300/30 focus:border-white focus:ring-0 px-0 py-3 text-xl placeholder-blue-300/30" placeholder="jane@example.com" type="email">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-blue-200">Subject</label>
                    <select class="w-full bg-transparent border-0 border-b-2 border-blue-300/30 focus:border-white focus:ring-0 px-0 py-3 text-xl text-white transition-colors">
                        <option class="text-editorial-dark" value="">Select a topic</option>
                        <option class="text-editorial-dark" value="order">Order Inquiry</option>
                        <option class="text-editorial-dark" value="wholesale">Wholesale</option>
                        <option class="text-editorial-dark" value="press">Press &amp; Media</option>
                        <option class="text-editorial-dark" value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-blue-200">Message</label>
                    <textarea class="w-full bg-transparent border-0 border-b-2 border-blue-300/30 focus:border-white focus:ring-0 px-0 py-3 text-xl placeholder-blue-300/30 resize-none" placeholder="How can we help?" rows="3"></textarea>
                </div>
                <button class="inline-flex items-center justify-center px-8 py-4 bg-white text-editorial-teal font-bold uppercase tracking-widest hover:bg-editorial-mustard hover:text-editorial-dark transition-all" type="button">
                    Send Message
                </button>
            </form>
        </div>

        @if ($hasContactSidebar)
            <div class="bg-[#FDFCF6] dark:bg-[#1a2c2c] p-10 lg:p-20 flex flex-col justify-center">
                <div class="space-y-10">
                    @if ($hasLocation || $hasOpeningHours)
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4">Visit Our Stores</h3>
                            <h4 class="text-3xl font-bold text-editorial-terracotta mb-2">{{ $siteSetting->site_name }}</h4>
                            @if ($hasLocation)
                                <p class="text-xl text-editorial-dark dark:text-gray-300 font-medium leading-relaxed">{!! nl2br(e($siteSetting->location)) !!}</p>
                            @endif
                            @if ($hasOpeningHours)
                                <p class="mt-2 text-gray-500 font-mono text-sm">{!! nl2br(e($siteSetting->opening_hours)) !!}</p>
                            @endif
                        </div>
                    @endif

                    @if ($hasSocialLinks)
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4">Connect With Us</h3>
                            <div class="flex flex-wrap gap-x-8 gap-y-4">
                                @foreach ($socialLinks as $socialLink)
                                    <a class="text-xl font-bold hover:text-editorial-terracotta transition-colors" href="{{ $socialLink['url'] }}" target="_blank" rel="noreferrer">
                                        {{ $socialLink['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($hasEmail || $hasPhone || $hasWhatsapp)
                        <div class="mt-10 p-6 bg-white dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-lg">
                            <p class="text-sm text-gray-500 mb-2 font-bold uppercase">Customer Service</p>

                            @if ($hasEmail)
                                <a class="text-xl font-medium text-editorial-dark dark:text-gray-200 hover:text-editorial-teal transition-colors block" href="mailto:{{ $siteSetting->contact_email }}">{{ $siteSetting->contact_email }}</a>
                            @endif

                            @if ($hasPhone)
                                <a class="text-xl font-medium text-editorial-dark dark:text-gray-200 hover:text-editorial-teal transition-colors block {{ $hasEmail ? 'mt-1' : '' }}" href="tel:{{ $phoneHref }}">{{ $siteSetting->phone_number }}</a>
                            @endif

                            @if ($hasWhatsapp)
                                <a class="text-xl font-medium text-editorial-dark dark:text-gray-200 hover:text-editorial-teal transition-colors block {{ $hasEmail || $hasPhone ? 'mt-1' : '' }}" href="https://wa.me/{{ $siteSetting->whatsapp_number }}" target="_blank" rel="noreferrer">WhatsApp: {{ $siteSetting->whatsapp_number }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </section>

    @if ($hasMapEmbed)
        <section class="px-6 lg:px-12 py-12 max-w-[1440px] mx-auto">
            <div class="relative w-full overflow-hidden rounded-lg border-4 border-editorial-mustard shadow-xl [&_iframe]:block [&_iframe]:h-[500px] [&_iframe]:w-full">
                {!! $siteSetting->map_embed_html !!}
            </div>
        </section>
    @endif
@endsection
