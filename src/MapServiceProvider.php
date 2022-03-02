<?php

namespace LaravelLib\Gmaps;

use Illuminate\Support\ServiceProvider;
use LaravelLib\Gmaps\Commands\MapInstallCommand;

class MapServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'maps');

        $this->app->singleton(
            Map::class,
            function ($app) {
                return new Map($app->view, $app['config']->get('maps'));
            }
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(base_path('resources/views/maps'), 'maps');

        $this->configurePublications();

        $this->app->alias(Map::class, 'map');
    }

    public function configurePublications()
    {
        if (!$this->app->runningInConsole()) return;

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('maps.php'),
        ], 'maps-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/maps')
        ], 'maps-views');

        $this->commands([
            MapInstallCommand::class,
        ]);
    }

    public function provides()
    {
        return ['map', Map::class];
    }
}
