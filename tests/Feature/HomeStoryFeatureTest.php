<?php

use App\Filament\Resources\HomeSections\RelationManagers\ItemsRelationManager;
use App\Models\HomeSection;

use function Pest\Laravel\get;

it('renders a story feature highlight with supporting stories', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'story_feature',
        'title' => 'Crafted for Memories',
        'subtitle' => 'Stories worth exploring.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 5))->each(function (int $number) use ($section): void {
        $section->items()->create([
            'title' => 'Story CTA '.$number,
            'image_path' => 'https://example.com/story-'.$number.'.jpg',
            'image_alt' => 'Story image '.$number,
            'link_url' => '/about?story='.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    $response = get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('data-story-feature-highlight');
    $response->assertSee('data-story-feature-supporting');
    $response->assertSee('Story CTA 1');
    $response->assertSee('Story CTA 4');
    $response->assertDontSee('Story CTA 5');
});

it('keeps the story feature stable when only one item exists', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'story_feature',
        'title' => 'Crafted for Memories',
        'subtitle' => 'Stories worth exploring.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $section->items()->create([
        'title' => 'Story CTA 1',
        'image_path' => 'https://example.com/story-1.jpg',
        'image_alt' => 'Story image 1',
        'link_url' => '/about?story=1',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('data-story-feature-highlight');
    $response->assertDontSee('data-story-feature-supporting');
});

it('prevents adding more than four active story feature items from admin logic', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'story_feature',
        'title' => 'Crafted for Memories',
        'subtitle' => 'Stories worth exploring.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 4))->each(function (int $number) use ($section): void {
        $section->items()->create([
            'title' => 'Story CTA '.$number,
            'image_path' => 'https://example.com/story-'.$number.'.jpg',
            'image_alt' => 'Story image '.$number,
            'link_url' => '/about?story='.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    expect(ItemsRelationManager::canCreateForOwnerRecord($section))->toBeFalse();
});

it('still allows adding story feature items when active items are below four', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'story_feature',
        'title' => 'Crafted for Memories',
        'subtitle' => 'Stories worth exploring.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 3))->each(function (int $number) use ($section): void {
        $section->items()->create([
            'title' => 'Story CTA '.$number,
            'image_path' => 'https://example.com/story-'.$number.'.jpg',
            'image_alt' => 'Story image '.$number,
            'link_url' => '/about?story='.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    expect(ItemsRelationManager::canCreateForOwnerRecord($section))->toBeTrue();
});

it('prevents adding more than seven active editorial picks from admin logic', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'editorial_picks',
        'title' => 'Editor Picks',
        'subtitle' => 'A curated set of featured products.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 7))->each(function (int $number) use ($section): void {
        $section->items()->create([
            'title' => 'Editorial Pick '.$number,
            'image_path' => 'https://example.com/editorial-'.$number.'.jpg',
            'image_alt' => 'Editorial image '.$number,
            'link_url' => '/products?pick='.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    expect(ItemsRelationManager::canCreateForOwnerRecord($section))->toBeFalse();
});

it('still allows adding editorial picks when active items are below seven', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'editorial_picks',
        'title' => 'Editor Picks',
        'subtitle' => 'A curated set of featured products.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    collect(range(1, 6))->each(function (int $number) use ($section): void {
        $section->items()->create([
            'title' => 'Editorial Pick '.$number,
            'image_path' => 'https://example.com/editorial-'.$number.'.jpg',
            'image_alt' => 'Editorial image '.$number,
            'link_url' => '/products?pick='.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    expect(ItemsRelationManager::canCreateForOwnerRecord($section))->toBeTrue();
});

it('hides the relation manager for category links sections', function () {
    $section = HomeSection::query()->create([
        'section_key' => 'category_links',
        'title' => 'Shop by Category',
        'subtitle' => 'Browse category cards from curated picks.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    expect(ItemsRelationManager::canViewForOwnerRecord($section))->toBeFalse()
        ->and(ItemsRelationManager::canCreateForOwnerRecord($section))->toBeFalse();
});
