<?php

namespace App\Settings;

use App\Support\ManagedFileCleaner;
use Spatie\LaravelSettings\Settings;

class SiteSetting extends Settings
{
    public string $site_name = 'toys and dolls';

    public ?string $logo_path = null;

    public ?string $favicon_path = null;

    public ?string $whatsapp_number = null;

    public ?string $phone_number = null;

    public ?string $contact_email = null;

    public ?string $location = null;

    public ?string $opening_hours = null;

    public ?string $map_embed_html = null;

    public ?string $instagram_url = null;

    public ?string $facebook_url = null;

    public ?string $youtube_url = null;

    public ?string $tiktok_url = null;

    public ?string $twitter_url = null;

    public ?string $linkedin_url = null;

    public ?string $threads_url = null;

    public ?string $other_social_url = null;

    public function save(): self
    {
        $originalLogoPath = $this->originalValues?->get('logo_path');
        $originalFaviconPath = $this->originalValues?->get('favicon_path');

        $result = parent::save();

        if (ManagedFileCleaner::normalizePath($originalLogoPath) !== ManagedFileCleaner::normalizePath($this->logo_path)) {
            ManagedFileCleaner::cleanupIfUnused($originalLogoPath);
        }

        if (ManagedFileCleaner::normalizePath($originalFaviconPath) !== ManagedFileCleaner::normalizePath($this->favicon_path)) {
            ManagedFileCleaner::cleanupIfUnused($originalFaviconPath);
        }

        return $result;
    }

    public static function group(): string
    {
        return 'site_settings';
    }
}
