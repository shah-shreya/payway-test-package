<?php

namespace payway\payway;

use Illuminate\Support\ServiceProvider;

class PaywayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'payway');
        $this->mergeConfigFrom(__DIR__.'/config/payway.php', 'payway');
        $this->publishes([
            __DIR__.'/config/payway.php' => config_path('payway.php'),
            __DIR__.'/views' => resource_path('views/vendor/payway'),
            __DIR__.'/Http/Controllers' => app_path('Http/Controllers/payway'),
        ]);
    }
    public function register()
    {
        
    }
}


