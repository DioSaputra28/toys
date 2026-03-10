<?php

use App\Settings\SiteSetting;

it('renders a floating whatsapp button when the site setting has a whatsapp number', function () {
    $siteSetting = app(SiteSetting::class);
    $siteSetting->whatsapp_number = '6281234567890';
    $siteSetting->save();

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('data-floating-whatsapp');
    $response->assertSee('https://wa.me/6281234567890');
    $response->assertSee('Chat WhatsApp');
});

it('does not render a floating whatsapp button when the site setting has no whatsapp number', function () {
    $siteSetting = app(SiteSetting::class);
    $siteSetting->whatsapp_number = null;
    $siteSetting->save();

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertDontSee('data-floating-whatsapp');
    $response->assertDontSee('Chat WhatsApp');
});
