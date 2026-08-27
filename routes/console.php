<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::call(function () {
    \App\Models\ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();
})->daily()->name('prune-activity-logs');
