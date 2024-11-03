<?php

namespace Rezak\KavenegarSMS;

use Illuminate\Support\ServiceProvider;

class KavenegarSMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(KavenegarSMS::class, function ($app) {
            return new KavenegarSMS(config('kavenegar.api_key'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/kavenegar.php' => config_path('kavenegar.php'),
        ], 'config');
    }
}
