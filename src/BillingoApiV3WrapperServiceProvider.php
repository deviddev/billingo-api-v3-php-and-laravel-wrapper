<?php

namespace Deviddev\BillingoApiV3Wrapper;

use Illuminate\Support\ServiceProvider;

class BillingoApiV3WrapperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('billingo-api-v3-wrapper.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'billingo-api-v3-wrapper');

        // Register the main class to use with the facade
        $this->app->singleton('billingo-api-v3-wrapper', function () {
            return new BillingoApiV3Wrapper;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [BillingoApiV3Wrapper::class];
    }
}
