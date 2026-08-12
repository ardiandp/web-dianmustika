<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->city();

        return [
            'name' => 'Dian Mustika '.$name,
            'slug' => Str::slug($name),
            'address' => $this->faker->streetAddress().', '.$name,
            'description' => $this->faker->paragraphs(2, true),
            'phone' => $this->faker->numerify('021#######'),
            'whatsapp' => '628'.$this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'google_maps_url' => 'https://maps.google.com/?q='.$name,
            'google_maps_embed' => null,
            'opening_hours' => [
                'Senin - Jumat' => '09.00 - 21.00',
                'Sabtu - Minggu' => '09.00 - 22.00',
            ],
            'image' => null,
            'alt_text' => 'Dian Mustika '.$name,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
