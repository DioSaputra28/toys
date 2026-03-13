<?php

use App\Models\Product;
use App\Models\ProductCategory;

use function Pest\Laravel\get;

it('marks the current top-level page as active in the navbar', function () {
    $response = get(route('gallery'));

    $response->assertSuccessful();
    $response->assertSee('href="'.route('gallery').'"', false);
    $response->assertSee('aria-current="page"', false);
    $response->assertSee('text-editorial-terracotta', false);
});

it('keeps the products link active on the product detail page', function () {
    $category = ProductCategory::query()->create([
        'name' => 'Wooden Toys',
        'slug' => 'wooden-toys',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Moon Rabbit',
        'slug' => 'moon-rabbit',
        'description' => 'A detailed handcrafted toy for storytelling moments.',
        'display_price' => 'Rp 259.000',
        'is_active' => true,
        'is_featured' => true,
    ]);

    $product->images()->create([
        'image_path' => 'https://example.com/moon-rabbit.jpg',
        'alt_text' => 'Moon Rabbit toy',
        'sort_order' => 0,
        'is_primary' => true,
        'show_in_gallery' => true,
        'is_active' => true,
    ]);

    $response = get(route('products.show', $product));

    $response->assertSuccessful();
    $response->assertSee('href="'.route('products').'"', false);
    $response->assertSee('aria-current="page"', false);
    $response->assertSee('text-editorial-terracotta', false);
});
