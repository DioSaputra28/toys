<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSiteSetting;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Filament\Resources\Products\ProductResource;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.quick-actions';

    protected int|string|array $columnSpan = ['md' => 4, 'xl' => 4];

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Manage Products',
                    'description' => 'Update catalog entries, pricing, and imagery.',
                    'url' => ProductResource::getUrl(),
                ],
                [
                    'label' => 'Home Sections',
                    'description' => 'Curate hero blocks, editorial picks, and feature stories.',
                    'url' => HomeSectionResource::getUrl(),
                ],
                [
                    'label' => 'Posters',
                    'description' => 'Refresh poster rails that appear between homepage sections.',
                    'url' => HomeSectionResource::getUrl('posters'),
                ],
                [
                    'label' => 'Gallery',
                    'description' => 'Upload or reorder gallery images for public pages.',
                    'url' => GalleryItemResource::getUrl(),
                ],
                [
                    'label' => 'Site Settings',
                    'description' => 'Review WhatsApp, contact details, and branding assets.',
                    'url' => ManageSiteSetting::getUrl(),
                ],
            ],
        ];
    }
}
