<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeSections extends ListRecords
{
    protected static string $resource = HomeSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageBanners')
                ->label('Manage banners')
                ->url(HomeSectionResource::getUrl('banners')),
            Action::make('managePosters')
                ->label('Manage posters')
                ->url(HomeSectionResource::getUrl('posters')),
            Action::make('manageCategoryPicks')
                ->label('Manage category picks')
                ->url(HomeSectionResource::getUrl('home-category-picks')),
            CreateAction::make(),
        ];
    }
}
