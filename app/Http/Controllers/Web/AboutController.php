<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    private const SECTION_KEYS = [
        'hero',
        'quote',
        'story',
        'values',
    ];

    public function index(): View
    {
        $sections = AboutSection::query()
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
            ->get()
            ->unique('section_key')
            ->values();

        $sections->each(function (AboutSection $section): void {
            $section->items->each(function ($item): void {
                $item->setAttribute('image_url', $this->resolveMediaUrl($item->image_path));
            });
        });

        $sectionsByKey = $sections->keyBy('section_key');

        $heroSection = $sectionsByKey->get('hero');
        $quoteSection = $sectionsByKey->get('quote');
        $storySection = $sectionsByKey->get('story');
        $valuesSection = $sectionsByKey->get('values');

        $heroImage = $heroSection?->items->firstWhere('item_type', 'hero_image')
            ?? $heroSection?->items->first();

        $storyParagraphs = $storySection?->items
            ?->where('item_type', 'story_paragraph')
            ->values()
            ?? collect();

        $storyImages = $storySection?->items
            ?->where('item_type', 'story_image')
            ->values()
            ?? collect();

        $storyFeature = $storySection?->items->firstWhere('item_type', 'story_feature')
            ?? $storySection?->items->firstWhere('item_type', 'feature_card');

        $valueCards = $valuesSection?->items
            ?->where('item_type', 'value_card')
            ->values();

        if ($valueCards === null || $valueCards->isEmpty()) {
            $valueCards = $valuesSection?->items?->values() ?? collect();
        }

        return view('web.about', [
            'heroSection' => $heroSection,
            'heroImage' => $heroImage,
            'quoteSection' => $quoteSection,
            'storySection' => $storySection,
            'storyParagraphs' => $storyParagraphs,
            'storyImages' => $storyImages,
            'storyFeature' => $storyFeature,
            'valuesSection' => $valuesSection,
            'valueCards' => $valueCards,
        ]);
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
