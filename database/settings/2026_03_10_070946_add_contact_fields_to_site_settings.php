<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site_settings.contact_email', null);
        $this->migrator->add('site_settings.location', null);
        $this->migrator->add('site_settings.opening_hours', null);
        $this->migrator->add('site_settings.map_embed_html', null);
    }
};
