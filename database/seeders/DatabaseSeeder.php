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
        ]);

        User::factory()->create([
            'name' => 'Admin Dian Mustika',
            'email' => 'admin@dianmustika.test',
        ]);
    }
}
