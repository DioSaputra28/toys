<?php

namespace App\Models\Concerns;

use App\Support\ManagedFileCleaner;

trait InteractsWithManagedFiles
{
    public static function bootInteractsWithManagedFiles(): void
    {
        static::updated(function ($model): void {
            ManagedFileCleaner::cleanupChangedAttributes($model, static::managedFileAttributes());
        });

        static::deleted(function ($model): void {
            ManagedFileCleaner::cleanupDeletedAttributes($model, static::managedFileAttributes());
        });
    }

    protected static function managedFileAttributes(): array
    {
        return ['image_path'];
    }
}
