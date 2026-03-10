<?php

test('site settings group branding and social media together', function () {
    $source = file_get_contents(app_path('Filament/Pages/ManageSiteSetting.php'));

    expect($source)
        ->toContain('use Filament\\Schemas\\Components\\Group;')
        ->toContain('Group::make([')
        ->toContain("Section::make('Branding')")
        ->toContain("Section::make('Social Media')")
        ->toContain("Section::make('Contact')");
});
