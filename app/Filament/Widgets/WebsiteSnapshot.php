<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSiteSetting;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Filament\Resources\ProductCategories\ProductCategoryResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\GalleryItem;
use App\Models\HomePoster;
use App\Models\HomeSection;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Settings\SiteSetting;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WebsiteSnapshot extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Website Snapshot';

    protected ?string $description = 'The first glance numbers that tell you whether the storefront is populated and ready.';

    protected int|array|null $columns = ['md' => 2, 'xl' => 3];

    protected function getStats(): array
    {
        $siteSetting = app(SiteSetting::class);
        $activeProducts = Product::query()->where('is_active', true)->count();
        $featuredProducts = Product::query()->where('is_active', true)->where('is_featured', true)->count();
        $activeCategories = ProductCategory::query()->where('is_active', true)->count();
        $activeGalleryItems = GalleryItem::query()->where('is_active', true)->count();
        $activeHomeSections = HomeSection::query()->where('is_active', true)->count();
        $activePosters = HomePoster::query()->where('is_active', true)->count();
        $whatsAppReady = filled($siteSetting->whatsapp_number);

        return [
            Stat::make('Active Products', $activeProducts)
                ->description("{$featuredProducts} featured product ready to push")
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp, IconPosition::Before)
                ->color('success')
                ->icon(Heroicon::OutlinedShoppingBag)
                ->url(ProductResource::getUrl()),
            Stat::make('Categories', $activeCategories)
                ->description('Active collection filters on the catalog page')
                ->descriptionIcon(Heroicon::OutlinedSquares2x2, IconPosition::Before)
                ->color('info')
                ->icon(Heroicon::OutlinedTag)
                ->url(ProductCategoryResource::getUrl()),
            Stat::make('Gallery Items', $activeGalleryItems)
                ->description('Visual assets available for the public gallery')
                ->descriptionIcon(Heroicon::OutlinedPhoto, IconPosition::Before)
                ->color($activeGalleryItems > 0 ? 'info' : 'gray')
                ->icon(Heroicon::OutlinedPhoto)
                ->url(GalleryItemResource::getUrl()),
            Stat::make('Home Sections', $activeHomeSections)
                ->description('Homepage blocks that are currently live')
                ->descriptionIcon(Heroicon::OutlinedHome, IconPosition::Before)
                ->color('warning')
                ->icon(Heroicon::OutlinedHome)
                ->url(HomeSectionResource::getUrl()),
            Stat::make('Posters', $activePosters)
                ->description('Poster cards rotating between homepage sections')
                ->descriptionIcon(Heroicon::OutlinedRectangleGroup, IconPosition::Before)
                ->color($activePosters > 0 ? 'success' : 'gray')
                ->icon(Heroicon::OutlinedRectangleGroup)
                ->url(HomeSectionResource::getUrl('posters')),
            Stat::make('WhatsApp Status', $whatsAppReady ? 'Ready' : 'Missing')
                ->description($whatsAppReady ? 'WhatsApp is ready' : 'Add the number in Site Settings')
                ->descriptionIcon(
                    $whatsAppReady ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationCircle,
                    IconPosition::Before,
                )
                ->color($whatsAppReady ? 'success' : 'danger')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->url(ManageSiteSetting::getUrl()),
        ];
    }
}
