<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            LocationSeeder::class,
            GallerySeeder::class,
            TestimonialSeeder::class,
            ArticleCategorySeeder::class,
            ArticleSeeder::class,
            FaqSeeder::class,
            SeoMetadataSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@dianmustika.test'],
            [
                'name' => 'Admin Dian Mustika',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $this->call([
            PermissionSeeder::class,
        ]);
    }
}
