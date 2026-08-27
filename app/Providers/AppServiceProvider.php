<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-4');

        Event::listen(Login::class, function (Login $event) {
            try {
                ActivityLog::create([
                    'user_id' => $event->user->id,
                    'user_name' => $event->user->name,
                    'action' => 'login',
                    'module' => 'auth',
                    'description' => 'Login ke sistem',
                    'ip_address' => request()->ip(),
                    'user_agent' => mb_substr(request()->userAgent() ?? '', 0, 500),
                ]);
            } catch (\Throwable $e) { report($e); }
        });

        Event::listen(Logout::class, function (Logout $event) {
            try {
                $user = $event->user;
                ActivityLog::create([
                    'user_id' => $user?->id,
                    'user_name' => $user?->name ?? 'Unknown',
                    'action' => 'logout',
                    'module' => 'auth',
                    'description' => 'Logout dari sistem',
                    'ip_address' => request()->ip(),
                    'user_agent' => mb_substr(request()->userAgent() ?? '', 0, 500),
                ]);
            } catch (\Throwable $e) { report($e); }
        });

        Event::listen(Failed::class, function (Failed $event) {
            try {
                ActivityLog::create([
                    'user_id' => null,
                    'user_name' => $event->credentials['email'] ?? 'Unknown',
                    'action' => 'login_failed',
                    'module' => 'auth',
                    'description' => 'Gagal login: ' . ($event->credentials['email'] ?? 'unknown'),
                    'ip_address' => request()->ip(),
                    'user_agent' => mb_substr(request()->userAgent() ?? '', 0, 500),
                ]);
            } catch (\Throwable $e) { report($e); }
        });
    }
}
