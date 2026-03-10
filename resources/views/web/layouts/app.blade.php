<!DOCTYPE html>
<html lang="en">
@php
    $siteSetting = app(\App\Settings\SiteSetting::class);
    $floatingWhatsappNumber = trim((string) ($siteSetting->whatsapp_number ?? ''));
    $floatingWhatsappUrl = $floatingWhatsappNumber !== ''
        ? 'https://wa.me/'.$floatingWhatsappNumber.'?text='.rawurlencode('Halo, saya ingin bertanya tentang produk di '.$siteSetting->site_name.'.')
        : null;
    $faviconPath = trim((string) ($siteSetting->favicon_path ?? ''));
    $faviconUrl = $faviconPath !== ''
        ? (\Illuminate\Support\Str::startsWith($faviconPath, ['http://', 'https://'])
            ? $faviconPath
            : asset('storage/'.ltrim($faviconPath, '/')))
        : null;
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toy Boutique')</title>
    @if ($faviconUrl)
        <link href="{{ $faviconUrl }}" rel="icon" type="image/png">
    @endif

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#13ecec',
                        'background-light': '#f6f8f8',
                        'background-dark': '#102222',
                        'editorial-terracotta': '#E07A5F',
                        'editorial-mustard': '#F2CC8F',
                        'editorial-teal': '#3D5A80',
                        'editorial-dark': '#293241',
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        body: ['Space Grotesk', 'sans-serif'],
                        sans: ['Space Grotesk', 'sans-serif'],
                    },
                    borderRadius: {
                        DEFAULT: '0.25rem',
                        lg: '0.5rem',
                        xl: '0.75rem',
                        '2xl': '1rem',
                        full: '9999px',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
        }

        h1,
        h2,
        h3,
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f6f8f8;
        }

        ::-webkit-scrollbar-thumb {
            background: #13ecec;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0fbdbd;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-editorial-dark dark:text-slate-100 antialiased overflow-x-hidden">
<div class="relative flex min-h-screen w-full flex-col group/design-root">
    @include('web.partials.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('web.partials.footer')

    @if ($floatingWhatsappUrl !== null)
        <a
            class="group fixed bottom-5 right-4 z-50 inline-flex items-center gap-3 overflow-hidden rounded-full border border-white/60 bg-[#25D366] px-4 py-3 text-editorial-dark shadow-[0_16px_40px_rgba(37,211,102,0.32)] transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] hover:bg-[#31e173] focus:outline-none focus:ring-4 focus:ring-[#25D366]/30 sm:bottom-7 sm:right-6 sm:px-5 sm:py-3.5"
            data-floating-whatsapp
            href="{{ $floatingWhatsappUrl }}"
            rel="noreferrer"
            target="_blank"
        >
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/18 text-white ring-1 ring-white/30 transition-transform duration-300 group-hover:scale-105">
                <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19.05 4.91A9.82 9.82 0 0 0 12.06 2C6.59 2 2.13 6.45 2.13 11.93c0 1.75.46 3.47 1.33 4.98L2 22l5.24-1.37a9.9 9.9 0 0 0 4.72 1.2h.01c5.47 0 9.92-4.45 9.93-9.93A9.86 9.86 0 0 0 19.05 4.91Zm-7.08 15.24h-.01a8.27 8.27 0 0 1-4.21-1.15l-.3-.18-3.11.81.83-3.03-.2-.31a8.23 8.23 0 0 1-1.27-4.37c0-4.56 3.71-8.27 8.28-8.27a8.2 8.2 0 0 1 5.85 2.42 8.2 8.2 0 0 1 2.42 5.85c0 4.57-3.72 8.28-8.28 8.28Zm4.53-6.19c-.25-.12-1.47-.72-1.69-.8-.23-.08-.39-.12-.56.12-.16.25-.64.8-.78.97-.14.16-.28.19-.53.06a6.78 6.78 0 0 1-1.99-1.23 7.54 7.54 0 0 1-1.38-1.72c-.14-.25-.02-.38.1-.5.11-.11.25-.28.37-.42.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.84-.2-.48-.4-.41-.56-.42h-.48a.92.92 0 0 0-.67.31c-.23.25-.88.86-.88 2.09 0 1.23.9 2.43 1.03 2.6.12.16 1.76 2.69 4.26 3.77.59.25 1.05.4 1.41.51.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.22-.16-.47-.28Z" />
                </svg>
            </span>
            <span class="min-w-0">
                <span class="block text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-editorial-dark/75">Need help?</span>
                <span class="block whitespace-nowrap text-sm font-semibold text-editorial-dark sm:text-[0.95rem]">Chat WhatsApp</span>
            </span>
        </a>
    @endif
</div>
</body>
</html>
