<?php

use App\Models\GalleryItem;

use function Pest\Laravel\get;

it('renders gallery items from the database', function () {
    GalleryItem::query()->create([
        'title' => 'Playroom Editorial',
        'image_path' => 'https://example.com/gallery-1.jpg',
        'alt_text' => 'Gallery image one',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    GalleryItem::query()->create([
        'title' => 'Quiet Shelf',
        'image_path' => 'https://example.com/gallery-2.jpg',
        'alt_text' => 'Gallery image two',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $response = get(route('gallery'));

    $response->assertSuccessful();
    $response->assertSee('data-gallery-dynamic');
    $response->assertSee('Playroom Editorial');
    $response->assertSee('https://example.com/gallery-1.jpg', false);
    $response->assertSee('https://example.com/gallery-2.jpg', false);
});

it('shows the empty state when there are no active gallery items', function () {
    GalleryItem::query()->create([
        'title' => 'Inactive image',
        'image_path' => 'https://example.com/inactive-gallery.jpg',
        'alt_text' => 'Inactive image',
        'sort_order' => 1,
        'is_active' => false,
    ]);

    $response = get(route('gallery'));

    $response->assertSuccessful();
    $response->assertSee('No gallery images yet');
    $response->assertDontSee('Inactive image');
});
