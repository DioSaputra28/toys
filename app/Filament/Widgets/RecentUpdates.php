<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\GalleryItem;
use App\Models\HomeSection;
use App\Models\Product;
use Filament\Widgets\Widget;

class RecentUpdates extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.recent-updates';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'groups' => [
                [
                    'title' => 'Products',
                    'items' => Product::query()
                        ->latest('updated_at')
                        ->limit(4)
                        ->get()
                        ->map(fn (Product $product): array => [
                            'label' => $product->name,
                            'meta' => $product->updated_at?->diffForHumans() ?? 'Just now',
                            'url' => ProductResource::getUrl('edit', ['record' => $product]),
                        ])
                        ->all(),
                ],
                [
                    'title' => 'Homepage',
                    'items' => HomeSection::query()
                        ->latest('updated_at')
                        ->limit(4)
                        ->get()
                        ->map(fn (HomeSection $section): array => [
                            'label' => $section->title ?: str($section->section_key)->replace('_', ' ')->title()->toString(),
                            'meta' => $section->updated_at?->diffForHumans() ?? 'Just now',
                            'url' => HomeSectionResource::getUrl('edit', ['record' => $section]),
                        ])
                        ->all(),
                ],
                [
                    'title' => 'Gallery',
                    'items' => GalleryItem::query()
                        ->latest('updated_at')
                        ->limit(4)
                        ->get()
                        ->map(fn (GalleryItem $item): array => [
                            'label' => $item->title ?: 'Untitled gallery item',
                            'meta' => $item->updated_at?->diffForHumans() ?? 'Just now',
                            'url' => GalleryItemResource::getUrl(),
                        ])
                        ->all(),
                ],
            ],
        ];
    }
}
