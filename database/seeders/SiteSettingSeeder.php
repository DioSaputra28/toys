<?php

namespace Database\Seeders;

use App\Settings\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $siteSetting = app(SiteSetting::class);

        $siteSetting->site_name = 'Atma Toys & Dolls';
        $siteSetting->logo_path = null;
        $siteSetting->favicon_path = null;
        $siteSetting->whatsapp_number = '6281234567890';
        $siteSetting->phone_number = '0812 3456 7890';
        $siteSetting->contact_email = 'admin@gmail.com';
        $siteSetting->location = "Jl. Melati No. 18\nBandung, Jawa Barat";
        $siteSetting->opening_hours = "Senin - Jumat: 09.00 - 18.00\nSabtu - Minggu: 10.00 - 19.00";
        $siteSetting->map_embed_html = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.1105482786!2d107.56075461451177!3d-6.903273917150415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e68f9f6d3c15%3A0x401e8f1fc28c5c0!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1731090000000!5m2!1sen!2sid" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
        $siteSetting->instagram_url = 'https://instagram.com/atmatoys';
        $siteSetting->facebook_url = 'https://facebook.com/atmatoys';
        $siteSetting->youtube_url = 'https://youtube.com/@atmatoys';
        $siteSetting->tiktok_url = 'https://tiktok.com/@atmatoys';
        $siteSetting->twitter_url = 'https://x.com/atmatoys';
        $siteSetting->linkedin_url = 'https://linkedin.com/company/atmatoys';
        $siteSetting->threads_url = 'https://threads.net/@atmatoys';
        $siteSetting->other_social_url = 'https://pinterest.com/atmatoys';
        $siteSetting->save();
    }
}
