<?php

namespace Projovi\ProjoviEazypay\Providers;

use Illuminate\Support\ServiceProvider;
use Projovi\ProjoviEazypay;

class ProjoviEazypayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/projovieazypay.php' => config_path('projovieazypay.php')], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__ . '/../../config/projovieazypay.php';
        $this->mergeConfigFrom($config, 'projovieazypay');
        $this->app->singleton('ProjoviEazypay', ProjoviEazypay::class);
    }
    public function provides()
    {
        // return ['ProjoviEazypay'];
    }
}
