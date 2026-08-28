<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Media Library maintenance
Schedule::command('media:prune-orphans --force')->weekly()->sundays()->at('03:00');
Schedule::command('media:thumbnails --force')->weekly()->sundays()->at('03:30');

// Bersihkan log aktivitas lebih dari 6 bulan
Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();
})->weekly()->sundays()->at('04:00');

