<?php

use App\Models\HomePoster;
use App\Models\HomeSection;

it('renders between-section posters as a carousel inside the content container', function () {
    HomeSection::query()->create([
        'section_key' => 'hero',
        'title' => 'Hero',
        'subtitle' => null,
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 4))->each(function (int $number): void {
        HomePoster::query()->create([
            'title' => 'Poster Feature '.$number,
            'image_path' => 'https://example.com/poster-'.$number.'.jpg',
            'link_url' => '/products?page='.$number,
            'insert_after_section_key' => 'hero',
            'size_variant' => match ($number) {
                1 => 'portrait',
                2 => 'landscape',
                3 => 'full',
                default => 'portrait',
            },
            'display_style' => 'overlay_text',
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('data-poster-carousel="hero"');
    $response->assertSee('data-poster-track');
    $response->assertSee('data-poster-free-scroll');
    $response->assertSee('data-poster-slide');
    $response->assertSee('draggable="false"', false);
    $response->assertDontSee('data-poster-prev');
    $response->assertDontSee('data-poster-next');
});

it('respects display style while rendering poster slides', function () {
    HomeSection::query()->create([
        'section_key' => 'hero',
        'title' => 'Hero',
        'subtitle' => null,
        'sort_order' => 1,
        'is_active' => true,
    ]);

    HomePoster::query()->create([
        'title' => 'Overlay Poster',
        'image_path' => 'https://example.com/poster-overlay.jpg',
        'link_url' => '/products?poster=overlay',
        'insert_after_section_key' => 'hero',
        'size_variant' => 'portrait',
        'display_style' => 'overlay_text',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    HomePoster::query()->create([
        'title' => 'Image Only Poster',
        'image_path' => 'https://example.com/poster-image-only.jpg',
        'link_url' => '/products?poster=image-only',
        'insert_after_section_key' => 'hero',
        'size_variant' => 'landscape',
        'display_style' => 'image_only',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Overlay Poster');
    $response->assertSee('data-poster-style="overlay_text"');
    $response->assertSee('data-poster-style="image_only"');
    $response->assertSee('Image Only Poster', false);
});
