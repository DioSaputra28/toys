<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Str::password(16),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => 'admin',
            ],
        );

        $this->call([
            SiteSettingSeeder::class,
            ProductStressSeeder::class,
            HomeStressSeeder::class,
            AboutContentSeeder::class,
            GalleryStressSeeder::class,
        ]);
    }
}
