<?php

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\Banner;

it('does not render top banners on the homepage even when records exist', function () {
    collect(range(1, 3))->each(function (int $number): void {
        Banner::query()->create([
            'title' => 'Top Banner '.$number,
            'placement' => 'home_top',
            'image_path' => 'https://example.com/banner-'.$number.'.jpg',
            'link_url' => 'https://example.com/banner-'.$number,
            'sort_order' => $number,
            'is_active' => true,
        ]);
    });

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertDontSee('Top Banner 1');
    $response->assertDontSee('Top Banner 2');
    $response->assertDontSee('Top Banner 3');
    $response->assertDontSee('data-top-banners');
});

it('does not expose a banners management page from the home section resource', function () {
    expect(HomeSectionResource::getPages())
        ->toHaveKey('banners');
});
