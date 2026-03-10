<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site_settings.site_name', 'toys and dolls');
        $this->migrator->add('site_settings.logo_path', null);
        $this->migrator->add('site_settings.favicon_path', null);
        $this->migrator->add('site_settings.whatsapp_number', null);
        $this->migrator->add('site_settings.phone_number', null);
        $this->migrator->add('site_settings.instagram_url', null);
        $this->migrator->add('site_settings.facebook_url', null);
        $this->migrator->add('site_settings.youtube_url', null);
        $this->migrator->add('site_settings.tiktok_url', null);
        $this->migrator->add('site_settings.twitter_url', null);
        $this->migrator->add('site_settings.linkedin_url', null);
        $this->migrator->add('site_settings.threads_url', null);
        $this->migrator->add('site_settings.other_social_url', null);
    }
};
