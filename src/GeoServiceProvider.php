<?php

namespace GeoService;

use GeoService\Http\Client;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/geo-service.php', 'geo-service');

        $this->publishes([
            __DIR__ . '/../config/geo-service.php' => config_path('geo-service.php'),
        ]);

        $this->app->when(Client::class)
            ->needs('$config')
            ->giveConfig('geo-service');
    }
}