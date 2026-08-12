<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DummyImage
{
    public static function store(string $directory, string $seed, int $width = 1200, int $height = 800): ?string
    {
        $filename = Str::slug($seed).'-'.$width.'x'.$height.'.jpg';
        $path = trim($directory, '/').'/'.$filename;

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        $body = self::download("https://picsum.photos/seed/{$seed}/{$width}/{$height}");

        if ($body === null) {
            $body = self::download("https://placehold.co/{$width}x{$height}.jpg", [
                'Accept' => 'image/*',
            ]);
        }

        if ($body !== null) {
            Storage::disk('public')->put($path, $body);

            return $path;
        }

        return null;
    }

    public static function avatar(string $seed): ?string
    {
        $filename = 'avatar-'.Str::slug($seed).'.jpg';
        $path = 'testimonials/'.$filename;

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        $number = (abs(crc32($seed)) % 70) + 1;
        $body = self::download("https://i.pravatar.cc/150?img={$number}");

        if ($body !== null) {
            Storage::disk('public')->put($path, $body);

            return $path;
        }

        return null;
    }

    private static function download(string $url, array $headers = []): ?string
    {
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $response = Http::connectTimeout(10)
                    ->timeout(25)
                    ->withHeaders($headers)
                    ->get($url);

                if ($response->successful()) {
                    return $response->body();
                }
            } catch (\Throwable $e) {
                //
            }

            usleep(600000);
        }

        return null;
    }
}
