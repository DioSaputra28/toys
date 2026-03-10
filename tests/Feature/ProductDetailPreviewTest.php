<?php

use App\Models\Product;
use App\Models\ProductCategory;
use App\Settings\SiteSetting;

use function Pest\Laravel\get;

it('renders a database-backed product detail page', function () {
    $category = ProductCategory::query()->create([
        'name' => 'Wooden Toys',
        'slug' => 'wooden-toys',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Luna Keepsake Train',
        'slug' => 'luna-keepsake-train',
        'description' => 'A handcrafted keepsake train set designed for slow play.',
        'display_price' => 'Rp 189.000',
        'is_featured' => true,
        'is_active' => true,
    ]);

    $product->images()->createMany([
        [
            'image_path' => 'https://example.com/luna-primary.jpg',
            'alt_text' => 'Luna primary image',
            'is_primary' => true,
            'show_in_gallery' => true,
            'sort_order' => 0,
            'is_active' => true,
        ],
        [
            'image_path' => 'https://example.com/luna-secondary.jpg',
            'alt_text' => 'Luna secondary image',
            'is_primary' => false,
            'show_in_gallery' => true,
            'sort_order' => 1,
            'is_active' => true,
        ],
    ]);

    $related = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Poppy Story Blocks',
        'slug' => 'poppy-story-blocks',
        'description' => 'Related product',
        'display_price' => 'Rp 92.000',
        'is_featured' => false,
        'is_active' => true,
    ]);

    $related->images()->create([
        'image_path' => 'https://example.com/poppy.jpg',
        'alt_text' => 'Poppy image',
        'is_primary' => true,
        'show_in_gallery' => true,
        'sort_order' => 0,
        'is_active' => true,
    ]);

    $siteSetting = app(SiteSetting::class);
    $siteSetting->whatsapp_number = '6281234567890';
    $siteSetting->save();

    $response = get(route('products.show', $product->slug));

    $response->assertSuccessful();
    $response->assertSee('Luna Keepsake Train');
    $response->assertSee('Rp 189.000');
    $response->assertSee('Pesan via WhatsApp');
    $response->assertSee('Related Products');
    $response->assertSee('Poppy Story Blocks');
});

it('returns not found for inactive products on product detail page', function () {
    $category = ProductCategory::query()->create([
        'name' => 'Hidden Category',
        'slug' => 'hidden-category',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Hidden Product',
        'slug' => 'hidden-product',
        'description' => null,
        'display_price' => 'Rp 100.000',
        'is_featured' => false,
        'is_active' => false,
    ]);

    get(route('products.show', $product->slug))->assertNotFound();
});
