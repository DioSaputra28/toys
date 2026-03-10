@php
    $siteSetting = app(\App\Settings\SiteSetting::class);
    $logoPath = trim((string) ($siteSetting->logo_path ?? ''));
    $logoUrl = $logoPath !== ''
        ? (\Illuminate\Support\Str::startsWith($logoPath, ['http://', 'https://'])
            ? $logoPath
            : asset('storage/'.ltrim($logoPath, '/')))
        : null;
@endphp

<header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 dark:bg-background-dark/90">
    <div class="flex items-center justify-between px-6 py-4 lg:px-12">
        <a class="flex items-center gap-2" href="/">
            @if ($logoUrl)
                <img alt="{{ $siteSetting->site_name }} logo" class="h-10 w-auto object-contain" src="{{ $logoUrl }}">
            @endif
            <h2 class="text-2xl font-bold tracking-tighter uppercase text-editorial-dark dark:text-white">{{ $siteSetting->site_name }}</h2>
        </a>

        <nav class="hidden md:flex items-center gap-8 lg:gap-10">
            <a class="text-sm font-medium uppercase tracking-widest hover:text-editorial-terracotta transition-colors" href="/">Beranda</a>
            <a class="text-sm font-medium uppercase tracking-widest hover:text-editorial-terracotta transition-colors" href="/about">About</a>
            <a class="text-sm font-medium uppercase tracking-widest hover:text-editorial-terracotta transition-colors" href="/products">Produk</a>
            <a class="text-sm font-medium uppercase tracking-widest hover:text-editorial-terracotta transition-colors" href="/gallery">Galeri</a>
            <a class="text-sm font-medium uppercase tracking-widest hover:text-editorial-terracotta transition-colors" href="/contact">Kontak</a>
        </nav>

    </div>
</header>
