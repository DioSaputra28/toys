<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomePoster;
use App\Models\HomeSection;
use Filament\Widgets\Widget;

class HomepageOverview extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.homepage-overview';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $posterCounts = HomePoster::query()
            ->where('is_active', true)
            ->selectRaw('insert_after_section_key, count(*) as aggregate')
            ->groupBy('insert_after_section_key')
            ->pluck('aggregate', 'insert_after_section_key');

        return [
            'sections' => HomeSection::query()
                ->withCount([
                    'items as active_items_count' => fn ($query) => $query->where('is_active', true),
                ])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(function (HomeSection $section) use ($posterCounts): array {
                    return [
                        'title' => $section->title ?: str($section->section_key)->replace('_', ' ')->title()->toString(),
                        'sectionKey' => $section->section_key,
                        'isActive' => $section->is_active,
                        'activeItemsCount' => $section->active_items_count,
                        'posterCount' => (int) ($posterCounts[$section->section_key] ?? 0),
                        'editUrl' => HomeSectionResource::getUrl('edit', ['record' => $section]),
                    ];
                })
                ->all(),
        ];
    }
}
