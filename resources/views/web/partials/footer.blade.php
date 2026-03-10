@php
    $siteSetting = app(\App\Settings\SiteSetting::class);
    $logoPath = trim((string) ($siteSetting->logo_path ?? ''));
    $logoUrl = $logoPath !== ''
        ? (\Illuminate\Support\Str::startsWith($logoPath, ['http://', 'https://'])
            ? $logoPath
            : asset('storage/'.ltrim($logoPath, '/')))
        : null;

    $footerConnectLinks = collect([
        ['label' => 'Instagram', 'url' => $siteSetting->instagram_url],
        ['label' => 'Facebook', 'url' => $siteSetting->facebook_url],
        ['label' => 'YouTube', 'url' => $siteSetting->youtube_url],
        ['label' => 'TikTok', 'url' => $siteSetting->tiktok_url],
        ['label' => 'Twitter / X', 'url' => $siteSetting->twitter_url],
        ['label' => 'LinkedIn', 'url' => $siteSetting->linkedin_url],
        ['label' => 'Threads', 'url' => $siteSetting->threads_url],
        ['label' => 'Other', 'url' => $siteSetting->other_social_url],
    ])->filter(fn (array $item): bool => filled($item['url']))->values();
@endphp

<footer class="bg-editorial-dark text-white pt-16 pb-8 px-6 lg:px-12 mt-12">
    <div class="max-w-[1440px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-14 border-b border-gray-700 pb-10">
            <div class="col-span-1 md:col-span-2">
                <h2 class="text-4xl font-bold tracking-tighter mb-4 font-serif">JOIN THE CLUB</h2>
                <p class="text-gray-400 mb-6 max-w-md text-lg">Subscribe for editorial stories, product updates, and curated play ideas.</p>
                <form class="flex gap-2 max-w-md">
                    <input class="flex-1 bg-white/10 border-none rounded-lg px-4 py-3 text-white placeholder:text-gray-500 focus:ring-2 focus:ring-primary" placeholder="Your email address" type="email">
                    <button class="bg-primary text-editorial-dark px-6 py-3 rounded-lg font-bold hover:bg-white transition-colors" type="button">Sign Up</button>
                </form>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-5 text-primary uppercase tracking-widest">Menu</h3>
                <ul class="space-y-3">
                    <li><a class="text-gray-400 hover:text-white transition-colors" href="/">Beranda</a></li>
                    <li><a class="text-gray-400 hover:text-white transition-colors" href="/about">About</a></li>
                    <li><a class="text-gray-400 hover:text-white transition-colors" href="/products">Produk</a></li>
                    <li><a class="text-gray-400 hover:text-white transition-colors" href="/gallery">Galeri</a></li>
                    <li><a class="text-gray-400 hover:text-white transition-colors" href="/contact">Kontak</a></li>
                </ul>
            </div>

            @if ($footerConnectLinks->isNotEmpty())
                <div>
                    <h3 class="text-lg font-bold mb-5 text-primary uppercase tracking-widest">Connect</h3>
                    <ul class="space-y-3">
                        @foreach ($footerConnectLinks as $footerConnectLink)
                            <li>
                                <a class="text-gray-400 hover:text-white transition-colors" href="{{ $footerConnectLink['url'] }}" rel="noreferrer" target="_blank">
                                    {{ $footerConnectLink['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-5">
            <a class="flex items-center gap-2" href="/">
                @if ($logoUrl)
                    <img alt="{{ $siteSetting->site_name }} logo" class="h-12 w-auto object-contain" src="{{ $logoUrl }}">
                @endif
                <h2 class="text-xl font-bold tracking-tighter uppercase">{{ $siteSetting->site_name }}</h2>
            </a>
            <div class="text-sm text-gray-500">© 2026 Toy Boutique. All rights reserved.</div>
        </div>
    </div>
</footer>
