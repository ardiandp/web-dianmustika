<?php

namespace App\Services;

use App\Models\Setting;

class WhatsAppService
{
    /**
     * Get normalized WhatsApp number (starts with 62, digits only).
     * If a custom number is provided, it normalizes that number.
     * Otherwise, it falls back to the site-wide setting.
     */
    public static function number(?string $customNumber = null): string
    {
        $raw = trim((string) ($customNumber ?: Setting::get('whatsapp', '6281234567890')));

        // Strip non-digit characters (+, -, spaces, parentheses, dots)
        $clean = preg_replace('/[^0-9]/', '', $raw);

        // Normalize leading 0 to 62 (Indonesian country code)
        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        return $clean !== '' ? $clean : '6281234567890';
    }

    /**
     * Build wa.me URL with pre-filled message and optional custom destination number.
     */
    public static function url(string $message = '', ?string $customNumber = null): string
    {
        $number = self::number($customNumber);

        return 'https://wa.me/'.$number.($message !== '' ? '?text='.rawurlencode($message) : '');
    }

    /**
     * Format number for human-readable display (+62 812-3456-7890).
     */
    public static function display(?string $customNumber = null): string
    {
        $number = self::number($customNumber);

        if (str_starts_with($number, '62')) {
            $rest = substr($number, 2);
            $part1 = substr($rest, 0, 3);
            $part2 = substr($rest, 3, 4);
            $part3 = substr($rest, 7);

            return '+62 '.$part1.($part2 !== '' ? '-'.$part2 : '').($part3 !== '' ? '-'.$part3 : '');
        }

        return $number;
    }
}
