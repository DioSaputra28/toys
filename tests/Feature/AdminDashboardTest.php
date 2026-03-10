<?php

use App\Models\GalleryItem;
use App\Models\HomePoster;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\User;
use App\Settings\SiteSetting;

it('renders the admin dashboard with operational content widgets', function () {
    config()->set('app.env', 'local');

    $user = User::factory()->create();

    $category = ProductCategory::query()->create([
        'name' => 'Wooden Toys',
        'slug' => 'wooden-toys',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'name' => 'Luna Keepsake Train',
        'slug' => 'luna-keepsake-train',
        'description' => 'A handcrafted keepsake train set.',
        'display_price' => 'Rp 189.000',
        'is_featured' => true,
        'is_active' => true,
    ]);

    ProductImage::query()->create([
        'product_id' => $product->id,
        'image_path' => 'https://example.com/train.jpg',
        'alt_text' => 'Train image',
        'is_primary' => true,
        'show_in_gallery' => true,
        'sort_order' => 1,
        'is_active' => true,
    ]);

    GalleryItem::query()->create([
        'title' => 'Gallery One',
        'image_path' => 'https://example.com/gallery.jpg',
        'alt_text' => 'Gallery image',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $heroSection = HomeSection::query()->create([
        'section_key' => 'hero',
        'title' => 'Hero',
        'subtitle' => 'Lead story',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    HomeSectionItem::query()->create([
        'home_section_id' => $heroSection->id,
        'title' => 'Hero Slide',
        'image_path' => 'https://example.com/hero.jpg',
        'image_alt' => 'Hero image',
        'link_url' => 'https://example.com',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    HomePoster::query()->create([
        'title' => 'Poster Feature',
        'image_path' => 'https://example.com/poster.jpg',
        'link_url' => 'https://example.com/poster',
        'insert_after_section_key' => 'hero',
        'size_variant' => 'portrait',
        'display_style' => 'overlay_text',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $siteSetting = app(SiteSetting::class);
    $siteSetting->site_name = 'toys and dolls';
    $siteSetting->whatsapp_number = '6281234567890';
    $siteSetting->contact_email = 'hello@example.com';
    $siteSetting->save();

    $response = $this
        ->actingAs($user)
        ->get('/admin');

    $response->assertSuccessful();
    $response->assertSee('Website Snapshot');
    $response->assertSee('Content Alerts');
    $response->assertSee('Quick Actions');
    $response->assertSee('Homepage Overview');
    $response->assertSee('Recent Updates');
    $response->assertSee('Luna Keepsake Train');
    $response->assertSee('WhatsApp is ready');
});
