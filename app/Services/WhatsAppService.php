<?php

namespace App\Services;

use App\Models\Setting;

class WhatsAppService
{
    public static function number(): string
    {
        return Setting::get('whatsapp') ?: '6281234567890';
    }

    public static function url(string $message = ''): string
    {
        $number = preg_replace('/[^0-9]/', '', self::number());

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }

    public static function display(): string
    {
        $number = self::number();

        if (str_starts_with($number, '62')) {
            return '+62 '.substr($number, 2, 3).'-'.substr($number, 5, 4).'-'.substr($number, 9);
        }

        return $number;
    }
}
