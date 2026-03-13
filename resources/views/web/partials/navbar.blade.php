@php
    $siteSetting = app(\App\Settings\SiteSetting::class);
    $logoPath = trim((string) ($siteSetting->logo_path ?? ''));
    $logoUrl = $logoPath !== ''
        ? (\Illuminate\Support\Str::startsWith($logoPath, ['http://', 'https://'])
            ? $logoPath
            : asset('storage/'.ltrim($logoPath, '/')))
        : null;
    $navLinks = [
        ['label' => 'Beranda', 'route' => 'home', 'pattern' => 'home'],
        ['label' => 'About', 'route' => 'about', 'pattern' => 'about'],
        ['label' => 'Produk', 'route' => 'products', 'pattern' => 'products*'],
        ['label' => 'Galeri', 'route' => 'gallery', 'pattern' => 'gallery'],
        ['label' => 'Kontak', 'route' => 'contact', 'pattern' => 'contact'],
    ];
@endphp

<header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 dark:bg-background-dark/90">
    <div class="flex items-center justify-between px-6 py-4 lg:px-12">
        <a class="flex items-center gap-2" href="{{ route('home') }}">
            @if ($logoUrl)
                <img alt="{{ $siteSetting->site_name }} logo" class="h-10 w-auto object-contain" src="{{ $logoUrl }}">
            @endif
            <h2 class="text-2xl font-bold tracking-tighter uppercase text-editorial-dark dark:text-white">{{ $siteSetting->site_name }}</h2>
        </a>

        <nav class="hidden md:flex items-center gap-8 lg:gap-10">
            @foreach ($navLinks as $navLink)
                @php
                    $isActive = request()->routeIs($navLink['pattern']);
                @endphp
                <a
                    @class([
                        'text-sm font-medium uppercase tracking-widest transition-colors relative pb-1',
                        'text-editorial-terracotta after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-full after:bg-editorial-terracotta after:content-[""]' => $isActive,
                        'hover:text-editorial-terracotta text-editorial-dark dark:text-white' => ! $isActive,
                    ])
                    aria-current="{{ $isActive ? 'page' : 'false' }}"
                    href="{{ route($navLink['route']) }}"
                >
                    {{ $navLink['label'] }}
                </a>
            @endforeach
        </nav>

    </div>
</header>
