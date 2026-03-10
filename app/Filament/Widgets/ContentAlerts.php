<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSiteSetting;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\GalleryItem;
use App\Models\HomeSection;
use App\Models\Product;
use App\Settings\SiteSetting;
use Filament\Widgets\Widget;

class ContentAlerts extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.content-alerts';

    protected int|string|array $columnSpan = ['md' => 8, 'xl' => 8];

    protected function getViewData(): array
    {
        $siteSetting = app(SiteSetting::class);

        $productsWithoutPrimaryImage = Product::query()
            ->where('is_active', true)
            ->whereDoesntHave('images', fn ($query) => $query
                ->where('is_active', true)
                ->where('is_primary', true))
            ->count();

        $activeSectionsWithoutItems = HomeSection::query()
            ->where('is_active', true)
            ->whereDoesntHave('items', fn ($query) => $query->where('is_active', true))
            ->count();

        $storyFeature = HomeSection::query()
            ->where('section_key', 'story_feature')
            ->first();

        $storyFeatureItemsCount = $storyFeature?->items()
            ->where('is_active', true)
            ->count() ?? 0;

        return [
            'alerts' => collect([
                $productsWithoutPrimaryImage > 0 ? [
                    'tone' => 'danger',
                    'title' => 'Products missing a primary image',
                    'detail' => "{$productsWithoutPrimaryImage} active product still needs a primary image.",
                    'actionLabel' => 'Open Products',
                    'actionUrl' => ProductResource::getUrl(),
                ] : null,
                $activeSectionsWithoutItems > 0 ? [
                    'tone' => 'warning',
                    'title' => 'Active sections without items',
                    'detail' => "{$activeSectionsWithoutItems} active homepage section has no live items yet.",
                    'actionLabel' => 'Review Sections',
                    'actionUrl' => HomeSectionResource::getUrl(),
                ] : null,
                blank($siteSetting->whatsapp_number) ? [
                    'tone' => 'danger',
                    'title' => 'WhatsApp number is missing',
                    'detail' => 'The storefront CTA and contact page cannot point to WhatsApp yet.',
                    'actionLabel' => 'Open Site Settings',
                    'actionUrl' => ManageSiteSetting::getUrl(),
                ] : null,
                blank($siteSetting->contact_email) ? [
                    'tone' => 'warning',
                    'title' => 'Contact email is still empty',
                    'detail' => 'Add a public email so visitors can reach the team from the contact page.',
                    'actionLabel' => 'Open Site Settings',
                    'actionUrl' => ManageSiteSetting::getUrl(),
                ] : null,
                GalleryItem::query()->where('is_active', true)->doesntExist() ? [
                    'tone' => 'neutral',
                    'title' => 'Gallery is still empty',
                    'detail' => 'No active gallery images are available on the public site.',
                    'actionLabel' => 'Open Gallery',
                    'actionUrl' => GalleryItemResource::getUrl(),
                ] : null,
                $storyFeature && $storyFeatureItemsCount < 2 ? [
                    'tone' => 'neutral',
                    'title' => 'Story Feature needs more support',
                    'detail' => "Story Feature only has {$storyFeatureItemsCount} active item right now.",
                    'actionLabel' => 'Edit Story Feature',
                    'actionUrl' => HomeSectionResource::getUrl('edit', ['record' => $storyFeature]),
                ] : null,
            ])->filter()->values()->all(),
        ];
    }
}
