<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        // Force No-Cache Headers on All Responses
        Response::macro('nocache', function ($content = '', $status = 200, array $headers = []) {
            $headers = array_merge([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ], $headers);

            return Response::make($content, $status, $headers);
        });
    }
}
