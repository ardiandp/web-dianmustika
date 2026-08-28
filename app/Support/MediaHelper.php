<?php

namespace App\Support;

class MediaHelper
{
    public static function title(?string $originalName): string
    {
        if (! $originalName) {
            return '';
        }

        $name = pathinfo($originalName, PATHINFO_FILENAME);

        $name = strtolower($name);
        $name = trim($name);

        // Buang suffix dimensi seperti -900x600, _800x800, -800x800, x900
        $name = preg_replace('/[-_]\d+x\d+$/', '', $name);
        $name = preg_replace('/^[\d]+[-_]/', '', $name);

        // Kata umum yang sebaiknya dibuang dari judul
        $name = preg_replace('/[-_]+/', ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        $name = trim($name);

        $words = array_filter(explode(' ', $name), fn ($w) => ! in_array(strtolower($w), ['900', '600', '800x800', '1200x800', 'retina']));

        $name = implode(' ', $words);

        return $name === '' ? $originalName : ucwords($name);
    }
}
