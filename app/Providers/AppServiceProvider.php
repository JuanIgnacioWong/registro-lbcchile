<?php

namespace App\Providers;

use App\Models\PlatformSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('public-submission', function (Request $request) {
            return Limit::perMinute(8)->by($request->ip());
        });

        RateLimiter::for('corrections-submission', function (Request $request) {
            return Limit::perMinute(8)->by($request->ip());
        });

        RateLimiter::for('public-options', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        $settings = [];

        try {
            $settings = Cache::remember('platform:shared-settings', now()->addMinutes(5), function (): array {
                return PlatformSetting::values([
                    'platform_name',
                    'brand_primary',
                    'brand_secondary',
                    'brand_accent',
                    'institutional_logo',
                ]);
            });
        } catch (\Throwable) {
            $settings = [];
        }

        View::share('sharedSettings', $settings);
    }
}
