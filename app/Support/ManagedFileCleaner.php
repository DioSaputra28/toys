<?php

namespace App\Support;

use App\Models\AboutSectionItem;
use App\Models\Banner;
use App\Models\GalleryItem;
use App\Models\HomePoster;
use App\Models\HomeSectionItem;
use App\Models\ProductImage;
use App\Settings\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagedFileCleaner
{
    public static function cleanupIfUnused(?string $path): void
    {
        $normalizedPath = self::normalizePath($path);

        if ($normalizedPath === null) {
            return;
        }

        if (self::isStillReferenced($normalizedPath)) {
            return;
        }

        Storage::disk('public')->delete($normalizedPath);
    }

    public static function normalizePath(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $trimmedPath = trim($path);

        if ($trimmedPath === '') {
            return null;
        }

        if (preg_match('/^(?:[a-z][a-z0-9+.-]*:|\/\/)/i', $trimmedPath) === 1) {
            return null;
        }

        if (str_starts_with($trimmedPath, 'storage/')) {
            $trimmedPath = Str::after($trimmedPath, 'storage/');
        }

        return ltrim($trimmedPath, '/');
    }

    public static function cleanupChangedAttributes(Model $model, array $attributes): void
    {
        foreach ($attributes as $attribute) {
            if (! $model->wasChanged($attribute)) {
                continue;
            }

            $previousValues = method_exists($model, 'getPrevious') ? $model->getPrevious() : [];
            $oldPath = $previousValues[$attribute] ?? null;
            $newPath = $model->getAttribute($attribute);

            if (self::normalizePath($oldPath) === self::normalizePath($newPath)) {
                continue;
            }

            self::cleanupIfUnused($oldPath);
        }
    }

    public static function cleanupDeletedAttributes(Model $model, array $attributes): void
    {
        foreach ($attributes as $attribute) {
            self::cleanupIfUnused($model->getAttribute($attribute));
        }
    }

    private static function isStillReferenced(string $path): bool
    {
        foreach (self::modelAttributeMap() as $modelClass => $attribute) {
            $hasReference = $modelClass::query()
                ->whereNotNull($attribute)
                ->pluck($attribute)
                ->contains(fn (?string $storedPath): bool => self::normalizePath($storedPath) === $path);

            if ($hasReference) {
                return true;
            }
        }

        $siteSetting = app(SiteSetting::class);

        return in_array($path, array_filter([
            self::normalizePath($siteSetting->logo_path),
            self::normalizePath($siteSetting->favicon_path),
        ]), true);
    }

    private static function modelAttributeMap(): array
    {
        return [
            GalleryItem::class => 'image_path',
            Banner::class => 'image_path',
            HomePoster::class => 'image_path',
            HomeSectionItem::class => 'image_path',
            AboutSectionItem::class => 'image_path',
            ProductImage::class => 'image_path',
        ];
    }
}
