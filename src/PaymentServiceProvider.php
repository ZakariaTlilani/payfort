<?php

namespace zakariatlilani\payfort;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'payment');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');




        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/Payfort.php' => config_path('Payfort.php'),
            ], 'config');

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/Payfort.php', 'payments');

        // Register the main class to use with the facade
        $this->app->singleton('payment', function ($app) {
            return new Payfort($app);
        });
    }
}