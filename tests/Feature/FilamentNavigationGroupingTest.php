<?php

it('assigns website content navigation groups to content resources', function () {
    expect(file_get_contents(app_path('Filament/Resources/HomeSections/HomeSectionResource.php')))
        ->toContain("\$navigationGroup = 'Website Content';");

    expect(file_get_contents(app_path('Filament/Resources/AboutSections/AboutSectionResource.php')))
        ->toContain("\$navigationGroup = 'Website Content';");

    expect(file_get_contents(app_path('Filament/Resources/GalleryItems/GalleryItemResource.php')))
        ->toContain("\$navigationGroup = 'Website Content';");
});

it('assigns catalog navigation groups to product resources', function () {
    expect(file_get_contents(app_path('Filament/Resources/Products/ProductResource.php')))
        ->toContain("\$navigationGroup = 'Catalog';");

    expect(file_get_contents(app_path('Filament/Resources/ProductCategories/ProductCategoryResource.php')))
        ->toContain("\$navigationGroup = 'Catalog';");
});

it('assigns the settings navigation group to site settings', function () {
    expect(file_get_contents(app_path('Filament/Pages/ManageSiteSetting.php')))
        ->toContain("\$navigationGroup = 'Settings';");
});
