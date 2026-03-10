<?php

use App\Models\GalleryItem;
use App\Settings\SiteSetting;
use Illuminate\Support\Facades\Storage;

it('deletes an old local image file when a gallery item is updated', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gallery/original.jpg', 'old');
    Storage::disk('public')->put('gallery/replacement.jpg', 'new');

    $item = GalleryItem::query()->create([
        'title' => 'Gallery item',
        'image_path' => 'gallery/original.jpg',
        'alt_text' => 'Original image',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $item->update([
        'image_path' => 'gallery/replacement.jpg',
    ]);

    expect(Storage::disk('public')->exists('gallery/original.jpg'))->toBeFalse();
    expect(Storage::disk('public')->exists('gallery/replacement.jpg'))->toBeTrue();
});

it('deletes a local image file when a gallery item is deleted', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gallery/delete-me.jpg', 'file');

    $item = GalleryItem::query()->create([
        'title' => 'Gallery item',
        'image_path' => 'gallery/delete-me.jpg',
        'alt_text' => 'Delete me',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $item->delete();

    expect(Storage::disk('public')->exists('gallery/delete-me.jpg'))->toBeFalse();
});

it('keeps a shared image file when another record still references it', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gallery/shared.jpg', 'shared');

    $firstItem = GalleryItem::query()->create([
        'title' => 'First gallery item',
        'image_path' => 'gallery/shared.jpg',
        'alt_text' => 'Shared image',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    GalleryItem::query()->create([
        'title' => 'Second gallery item',
        'image_path' => 'gallery/shared.jpg',
        'alt_text' => 'Shared image',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $firstItem->delete();

    expect(Storage::disk('public')->exists('gallery/shared.jpg'))->toBeTrue();
});

it('keeps a shared image file when another record references the same path with a leading slash', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gallery/shared-variant.jpg', 'shared');

    $firstItem = GalleryItem::query()->create([
        'title' => 'First gallery item',
        'image_path' => 'gallery/shared-variant.jpg',
        'alt_text' => 'Shared image',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    GalleryItem::query()->create([
        'title' => 'Second gallery item',
        'image_path' => '/gallery/shared-variant.jpg',
        'alt_text' => 'Shared image',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $firstItem->delete();

    expect(Storage::disk('public')->exists('gallery/shared-variant.jpg'))->toBeTrue();
});

it('deletes replaced site setting logo and favicon files', function () {
    Storage::fake('public');
    Storage::disk('public')->put('settings/branding/logo-old.png', 'old logo');
    Storage::disk('public')->put('settings/branding/logo-new.png', 'new logo');
    Storage::disk('public')->put('settings/branding/favicon-old.png', 'old favicon');
    Storage::disk('public')->put('settings/branding/favicon-new.png', 'new favicon');

    $siteSetting = app(SiteSetting::class);
    $siteSetting->logo_path = 'settings/branding/logo-old.png';
    $siteSetting->favicon_path = 'settings/branding/favicon-old.png';
    $siteSetting->save();

    $siteSetting->logo_path = 'settings/branding/logo-new.png';
    $siteSetting->favicon_path = 'settings/branding/favicon-new.png';
    $siteSetting->save();

    expect(Storage::disk('public')->exists('settings/branding/logo-old.png'))->toBeFalse();
    expect(Storage::disk('public')->exists('settings/branding/favicon-old.png'))->toBeFalse();
    expect(Storage::disk('public')->exists('settings/branding/logo-new.png'))->toBeTrue();
    expect(Storage::disk('public')->exists('settings/branding/favicon-new.png'))->toBeTrue();
});

it('allows protocol-relative and external image urls without touching local storage', function () {
    Storage::fake('public');
    Storage::disk('public')->put('gallery/local-keep.jpg', 'keep');

    $item = GalleryItem::query()->create([
        'title' => 'External gallery item',
        'image_path' => '//example.com/image.jpg',
        'alt_text' => 'External image',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $item->update([
        'image_path' => 'https://example.com/image-new.jpg',
    ]);

    expect(Storage::disk('public')->exists('gallery/local-keep.jpg'))->toBeTrue();
});
