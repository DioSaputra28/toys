<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleryItems = GalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (GalleryItem $galleryItem): GalleryItem {
                $galleryItem->setAttribute('image_url', $this->resolveMediaUrl($galleryItem->image_path));
                $galleryItem->setAttribute('image_alt_text', $galleryItem->alt_text ?: ($galleryItem->title ?: 'Gallery image'));

                return $galleryItem;
            })
            ->filter(fn (GalleryItem $galleryItem): bool => filled($galleryItem->image_url))
            ->values();

        return view('web.gallery', [
            'galleryItems' => $galleryItems,
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
