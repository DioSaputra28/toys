<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\HomeCategoryPick;
use App\Models\HomePoster;
use App\Models\HomeSection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private const SECTION_KEYS = [
        'hero',
        'marquee',
        'editorial_picks',
        'quote',
        'story_feature',
        'category_links',
    ];

    public function index(): View
    {
        $sections = HomeSection::query()
            ->where('is_active', true)
            ->whereIn('section_key', self::SECTION_KEYS)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->with([
                'items' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->get();

        $duplicateSectionKeys = $sections
            ->groupBy('section_key')
            ->filter(fn ($group) => $group->count() > 1)
            ->keys()
            ->values();

        $sections = $sections->unique('section_key')->values();
        $activeSectionKeys = $sections->pluck('section_key')->all();

        $sections->each(function (HomeSection $section): void {
            $section->items->each(function ($item): void {
                $item->setAttribute('image_url', $this->resolveMediaUrl($item->image_path));
            });
        });

        $betweenSectionBanners = Banner::query()
            ->where('is_active', true)
            ->where('placement', 'home_between_sections')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (Banner $banner): Banner {
                $banner->setAttribute('image_url', $this->resolveMediaUrl($banner->image_path));

                return $banner;
            });

        $orphanBetweenBanners = $betweenSectionBanners
            ->filter(fn (Banner $banner): bool => ! in_array((string) $banner->insert_after_section_key, $activeSectionKeys, true))
            ->values();

        $betweenSectionBanners = $betweenSectionBanners
            ->filter(fn (Banner $banner): bool => in_array((string) $banner->insert_after_section_key, $activeSectionKeys, true))
            ->groupBy('insert_after_section_key');

        $betweenSectionPosters = HomePoster::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (HomePoster $poster): HomePoster {
                $poster->setAttribute('image_url', $this->resolveMediaUrl($poster->image_path));

                return $poster;
            });

        $orphanBetweenPosters = $betweenSectionPosters
            ->filter(fn (HomePoster $poster): bool => ! in_array((string) $poster->insert_after_section_key, $activeSectionKeys, true))
            ->values();

        $betweenSectionPosters = $betweenSectionPosters
            ->filter(fn (HomePoster $poster): bool => in_array((string) $poster->insert_after_section_key, $activeSectionKeys, true))
            ->groupBy('insert_after_section_key');

        $categoryPicks = HomeCategoryPick::query()
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->with([
                'category' => fn ($query) => $query
                    ->with([
                        'products' => fn ($productQuery) => $productQuery
                            ->where('is_active', true)
                            ->orderByDesc('is_featured')
                            ->orderBy('id')
                            ->with([
                                'images' => fn ($imageQuery) => $imageQuery
                                    ->where('is_active', true)
                                    ->orderByDesc('is_primary')
                                    ->orderBy('sort_order'),
                            ]),
                    ]),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (HomeCategoryPick $pick): HomeCategoryPick {
                $pick->setAttribute('cover_image_url', $this->resolveCategoryCover($pick));

                return $pick;
            });

        return view('web.home', [
            'sections' => $sections,
            'duplicateSectionKeys' => $duplicateSectionKeys,
            'betweenSectionBanners' => $betweenSectionBanners,
            'orphanBetweenBanners' => $orphanBetweenBanners,
            'betweenSectionPosters' => $betweenSectionPosters,
            'orphanBetweenPosters' => $orphanBetweenPosters,
            'categoryPicks' => $categoryPicks,
        ]);
    }

    private function resolveCategoryCover(HomeCategoryPick $pick): ?string
    {
        $firstProduct = $pick->category?->products->first();
        $firstImage = $firstProduct?->images->first();

        return $this->resolveMediaUrl($firstImage?->image_path);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
